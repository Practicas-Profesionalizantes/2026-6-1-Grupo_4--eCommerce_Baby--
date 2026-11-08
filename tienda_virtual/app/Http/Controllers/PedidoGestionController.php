<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\AvisoAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

class PedidoGestionController extends Controller
{
    private const ESTADOS = ['Pendiente', 'Preparacion', 'Enviado', 'Entregado'];

    private const ESTADO_ENVIO = [
        'Pendiente'   => 'Preparando',
        'Preparacion' => 'Preparando',
        'Enviado'     => 'En viaje',
        'Entregado'   => 'Entregado',
    ];

    private function soloAdmin()
    {
        abort_unless(Auth::check() && (int) Auth::user()->Id_rol === 2, 403);
    }

    public function listar(Request $request)
    {
        $this->soloAdmin();

        $consulta = DB::table('pedido as p')
            ->join('cliente as c', 'c.Id_cliente', '=', 'p.Id_cliente')
            ->leftJoin('envio as e', 'e.Id_pedido', '=', 'p.Id_pedido')
            ->select(
                'p.Id_pedido',
                'p.Fecha_Pedido',
                'p.Total_Final',
                'p.Estado_Pedido',
                'p.Aviso_Admin',
                'c.Nombre',
                'c.Apellido',
                'e.Estado_Envio',
                DB::raw('(select coalesce(sum(d.Cantidad), 0) from detalle_pedido d where d.Id_pedido = p.Id_pedido) as Unidades')
            )
            ->orderByDesc('p.Id_pedido');

        $estado = $request->query('estado');
        if (in_array($estado, array_merge(self::ESTADOS, ['Cancelado']), true)) {
            $consulta->where('p.Estado_Pedido', $estado);
        }

        $filas = $consulta->get()->map(function ($f) {
            return [
                'id'       => $f->Id_pedido,
                'fecha'    => $f->Fecha_Pedido,
                'cliente'  => trim(($f->Nombre ?? '') . ' ' . ($f->Apellido ?? '')),
                'unidades' => (int) $f->Unidades,
                'total'    => (float) $f->Total_Final,
                'estado'   => $f->Estado_Pedido ?? 'Pendiente',
                'envio'    => $f->Estado_Envio,
                'aviso'    => (int) $f->Aviso_Admin,
            ];
        });

        return response()->json($filas->values());
    }

    public function detalle($id)
    {
        $this->soloAdmin();

        $p = DB::table('pedido as p')
            ->join('cliente as c', 'c.Id_cliente', '=', 'p.Id_cliente')
            ->leftJoin('direccion as d', 'd.Id_direccion', '=', 'p.Id_direccion')
            ->where('p.Id_pedido', $id)
            ->select(
                'p.*',
                'c.Id_usuario',
                'c.Nombre as Cliente_Nombre',
                'c.Apellido as Cliente_Apellido',
                'c.Telefono as Cliente_Telefono',
                'c.DNI_CUIT',
                'd.Calle_Numero',
                'd.Calle',
                'd.Numero',
                'd.Departamento',
                'd.Barrio',
                'd.Ciudad',
                'd.Provincia',
                'd.Codigo_Postal'
            )
            ->first();

        abort_unless($p, 404);

        $usuario = User::where('Id_usuario', $p->Id_usuario)->first();

        $productos = DB::table('detalle_pedido as dp')
            ->join('variante_producto as v', 'v.Id_variante', '=', 'dp.Id_variante')
            ->join('producto as pr', 'pr.Id_producto', '=', 'v.Id_producto')
            ->leftJoin('imagen_producto as i', function ($join) {
                $join->on('i.Id_producto', '=', 'pr.Id_producto')->where('i.Es_Portada', '=', 1);
            })
            ->where('dp.Id_pedido', $id)
            ->orderBy('dp.Id_detalle')
            ->select(
                'dp.Cantidad',
                'dp.Precio_Unitario_Cobrado',
                'pr.Nombre as Producto',
                'v.Talle',
                'v.Color',
                'v.Stock_Actual',
                'i.URL_Imagen'
            )
            ->get()
            ->map(function ($x) {
                return [
                    'nombre'   => $x->Producto,
                    'talle'    => $x->Talle,
                    'color'    => $x->Color,
                    'cantidad' => (int) $x->Cantidad,
                    'precio'   => (float) $x->Precio_Unitario_Cobrado,
                    'stock'    => (int) $x->Stock_Actual,
                    'imagen'   => $x->URL_Imagen,
                ];
            })->values();

        $envio = DB::table('envio')->where('Id_pedido', $id)->orderBy('Id_envio')->first();

        $pago = DB::table('transaccion_pago as t')
            ->join('metodo_pago as m', 'm.Id_metodo_pago', '=', 't.Id_metodo_pago')
            ->where('t.Id_pedido', $id)
            ->orderBy('t.Id_transaccion')
            ->select('m.Nombre as Metodo', 't.Estado_Pago', 't.Monto')
            ->first();

        $idFactura = DB::table('factura')->where('Id_pedido', $id)->value('Id_factura');

        $lineaCalle = $p->Calle_Numero;
        if ($p->Calle !== null && $p->Calle !== '') {
            $lineaCalle = trim($p->Calle . ' ' . $p->Numero);
            if ($p->Departamento !== null && $p->Departamento !== '') {
                $lineaCalle .= ', Dpto ' . $p->Departamento;
            }
        }

        $costoEnvio = (float) $p->Total_Final - ((float) $p->Subtotal - (float) $p->Descuento_Aplicado);

        return response()->json([
            'id'          => $p->Id_pedido,
            'fecha'       => $p->Fecha_Pedido,
            'estado'      => $p->Estado_Pedido ?? 'Pendiente',
            'aviso'       => (int) $p->Aviso_Admin,
            'subtotal'    => (float) $p->Subtotal,
            'descuento'   => (float) $p->Descuento_Aplicado,
            'costo_envio' => max(0, round($costoEnvio, 2)),
            'total'       => (float) $p->Total_Final,
            'cliente'     => [
                'nombre'    => trim(($p->Cliente_Nombre ?? '') . ' ' . ($p->Cliente_Apellido ?? '')),
                'telefono'  => $p->Cliente_Telefono,
                'documento' => $p->DNI_CUIT,
                'email'     => $usuario ? $usuario->email : null,
            ],
            'destinatario' => $p->Destinatario_Nombre !== null ? [
                'nombre'   => trim($p->Destinatario_Nombre . ' ' . $p->Destinatario_Apellido),
                'telefono' => $p->Destinatario_Telefono,
            ] : null,
            'direccion' => $p->Calle_Numero !== null ? [
                'calle'         => $lineaCalle,
                'barrio'        => $p->Barrio,
                'ciudad'        => $p->Ciudad,
                'provincia'     => $p->Provincia,
                'codigo_postal' => $p->Codigo_Postal,
            ] : null,
            'productos' => $productos,
            'envio'     => $envio ? [
                'empresa'     => $envio->Empresa_Logistica,
                'seguimiento' => $envio->Numero_Tracking,
                'estado'      => $envio->Estado_Envio,
                'despacho'    => $envio->Fecha_Despacho,
            ] : null,
            'pago' => $pago ? [
                'metodo' => $pago->Metodo,
                'estado' => $pago->Estado_Pago,
                'monto'  => (float) $pago->Monto,
            ] : null,
            'factura' => $idFactura,
        ]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $this->soloAdmin();

        $datos = $request->validate([
            'estado' => ['required', 'in:' . implode(',', self::ESTADOS)],
        ], [
            'estado.required' => 'Elegí un estado.',
            'estado.in'       => 'Ese estado no existe.',
        ]);

        $notificar = false;

        $respuesta = DB::transaction(function () use ($id, $datos, &$notificar) {
            $pedido = DB::table('pedido')->where('Id_pedido', $id)->lockForUpdate()->first();

            if (!$pedido) {
                return response()->json(['message' => 'Pedido no encontrado.'], 404);
            }

            if ($pedido->Estado_Pedido === 'Cancelado') {
                return response()->json(['message' => 'Un pedido cancelado no puede cambiar de estado.'], 422);
            }

            $cambios = ['Estado_Pedido' => $datos['estado']];

            if (($pedido->Estado_Pedido ?? 'Pendiente') !== $datos['estado']) {
                $cambios['Aviso_Cliente'] = 1;
                $notificar = true;
            }

            DB::table('pedido')->where('Id_pedido', $id)->update($cambios);

            $envio = ['Estado_Envio' => self::ESTADO_ENVIO[$datos['estado']]];
            if ($datos['estado'] === 'Enviado') {
                $envio['Fecha_Despacho'] = DB::raw('COALESCE(Fecha_Despacho, CURDATE())');
            }
            DB::table('envio')->where('Id_pedido', $id)->update($envio);

            return response()->json(['message' => 'Estado actualizado a ' . $datos['estado'] . '.']);
        });

        if ($notificar) {
            $this->avisarPorMail((int) $id);
        }

        return $respuesta;
    }

    public function guardarEnvio(Request $request, $id)
    {
        $this->soloAdmin();

        $datos = $request->validate([
            'empresa'  => ['required', 'string', 'max:100'],
            'tracking' => ['nullable', 'string', 'max:100'],
        ], [
            'empresa.required' => 'Escribí la empresa de envío.',
            'empresa.max'      => 'La empresa puede tener hasta 100 caracteres.',
            'tracking.max'     => 'El número de seguimiento puede tener hasta 100 caracteres.',
        ]);

        $pedido = DB::table('pedido')->where('Id_pedido', $id)->first();

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado.'], 404);
        }

        if ($pedido->Estado_Pedido === 'Cancelado') {
            return response()->json(['message' => 'Un pedido cancelado no puede cambiar de envío.'], 422);
        }

        $fila = [
            'Empresa_Logistica' => trim($datos['empresa']),
            'Numero_Tracking'   => isset($datos['tracking']) && trim($datos['tracking']) !== '' ? trim($datos['tracking']) : null,
        ];

        if (DB::table('envio')->where('Id_pedido', $id)->exists()) {
            DB::table('envio')->where('Id_pedido', $id)->update($fila);
        } else {
            DB::table('envio')->insert($fila + ['Id_pedido' => $id, 'Estado_Envio' => 'Preparando']);
        }

        return response()->json(['message' => 'Envío guardado.']);
    }

    public function visto($id)
    {
        $this->soloAdmin();

        DB::table('pedido')->where('Id_pedido', $id)->update(['Aviso_Admin' => 0]);

        return response()->json(['message' => 'Visto']);
    }

    public function contador()
    {
        $this->soloAdmin();

        return response()->json(['total' => DB::table('pedido')->where('Aviso_Admin', 1)->count()]);
    }

    public function avisosCliente()
    {
        $idCliente = DB::table('cliente')
            ->where('Id_usuario', Auth::user()->Id_usuario)
            ->value('Id_cliente');

        if (!$idCliente) {
            return response()->json(['total' => 0, 'lista' => []]);
        }

        $lista = DB::table('pedido as p')
            ->leftJoin('envio as e', 'e.Id_pedido', '=', 'p.Id_pedido')
            ->where('p.Id_cliente', $idCliente)
            ->where('p.Aviso_Cliente', 1)
            ->orderByDesc('p.Id_pedido')
            ->select('p.Id_pedido', 'p.Estado_Pedido', 'e.Empresa_Logistica', 'e.Numero_Tracking')
            ->get()
            ->map(function ($f) {
                return [
                    'id'      => $f->Id_pedido,
                    'mensaje' => self::textoAviso($f->Id_pedido, $f->Estado_Pedido ?? 'Pendiente', $f->Numero_Tracking, $f->Empresa_Logistica),
                ];
            })
            ->values();

        return response()->json(['total' => $lista->count(), 'lista' => $lista]);
    }

    public function avisosVistos()
    {
        $idCliente = DB::table('cliente')
            ->where('Id_usuario', Auth::user()->Id_usuario)
            ->value('Id_cliente');

        if ($idCliente) {
            DB::table('pedido')
                ->where('Id_cliente', $idCliente)
                ->where('Aviso_Cliente', 1)
                ->update(['Aviso_Cliente' => 0]);
        }

        return response()->json(['message' => 'Visto']);
    }

    public function cancelarAdmin($id)
    {
        $this->soloAdmin();

        return $this->cancelar((int) $id, true, null);
    }

    public function cancelarCliente($id)
    {
        $idCliente = DB::table('cliente')
            ->where('Id_usuario', Auth::user()->Id_usuario)
            ->value('Id_cliente');

        abort_unless($idCliente, 404);

        return $this->cancelar((int) $id, false, (int) $idCliente);
    }

    private function cancelar(int $id, bool $porAdmin, ?int $idCliente)
    {
        $notificar = false;
        $avisarAdmin = false;

        $respuesta = DB::transaction(function () use ($id, $porAdmin, $idCliente, &$notificar, &$avisarAdmin) {
            $consulta = DB::table('pedido')->where('Id_pedido', $id)->lockForUpdate();

            if ($idCliente !== null) {
                $consulta->where('Id_cliente', $idCliente);
            }

            $pedido = $consulta->first();

            if (!$pedido) {
                return response()->json(['message' => 'Pedido no encontrado.'], 404);
            }

            $estado = $pedido->Estado_Pedido ?? 'Pendiente';

            if ($estado === 'Cancelado') {
                return response()->json(['message' => 'Este pedido ya está cancelado.'], 422);
            }

            $permitidos = $porAdmin ? ['Pendiente', 'Preparacion', 'Enviado'] : ['Pendiente', 'Preparacion'];

            if (!in_array($estado, $permitidos, true)) {
                $mensaje = $porAdmin
                    ? 'Un pedido entregado no se puede cancelar.'
                    : 'Este pedido ya salió o fue entregado, así que no se puede cancelar desde acá. Escribinos desde Soporte.';

                return response()->json(['message' => $mensaje], 422);
            }

            $detalles = DB::table('detalle_pedido')->where('Id_pedido', $id)->get();

            foreach ($detalles as $d) {
                DB::table('variante_producto')
                    ->where('Id_variante', $d->Id_variante)
                    ->increment('Stock_Actual', (int) $d->Cantidad);

                DB::table('movimiento_stock')->insert([
                    'Id_variante'     => $d->Id_variante,
                    'Tipo_Movimiento' => 'Entrada',
                    'Cantidad'        => (int) $d->Cantidad,
                    'Motivo'          => 'Cancelación del pedido #' . $id,
                ]);
            }

            DB::table('pedido')->where('Id_pedido', $id)->update([
                'Estado_Pedido' => 'Cancelado',
                'Aviso_Admin'   => $porAdmin ? 0 : 1,
                'Aviso_Cliente' => $porAdmin ? 1 : 0,
            ]);

            DB::table('envio')->where('Id_pedido', $id)->update(['Estado_Envio' => 'Cancelado']);

            $notificar = $porAdmin;
            $avisarAdmin = !$porAdmin;

            return response()->json(['message' => 'Pedido cancelado. El stock volvió a las publicaciones.']);
        });

        if ($notificar) {
            $this->avisarPorMail($id);
        }

        if ($avisarAdmin) {
            $this->avisarAdminCancelacion($id);
        }

        return $respuesta;
    }

    private function avisarAdminCancelacion(int $id): void
    {
        try {
            $fila = DB::table('pedido as p')
                ->join('cliente as c', 'c.Id_cliente', '=', 'p.Id_cliente')
                ->where('p.Id_pedido', $id)
                ->select('p.Total_Final', 'c.Nombre', 'c.Apellido')
                ->first();

            if (!$fila) {
                return;
            }

            $productos = DB::table('detalle_pedido as dp')
                ->join('variante_producto as v', 'v.Id_variante', '=', 'dp.Id_variante')
                ->join('producto as pr', 'pr.Id_producto', '=', 'v.Id_producto')
                ->where('dp.Id_pedido', $id)
                ->orderBy('dp.Id_detalle')
                ->select('dp.Cantidad', 'pr.Nombre as Producto', 'v.Talle', 'v.Color')
                ->get()
                ->map(function ($x) {
                    return (int) $x->Cantidad . ' x ' . $x->Producto . ' (talle ' . $x->Talle . ', ' . $x->Color . ')';
                })
                ->all();

            AvisoAdmin::enviar(
                'Kiddy Shop: el cliente canceló el pedido #' . $id,
                array_merge([
                    'El cliente canceló el pedido #' . $id . '.',
                    'Cliente: ' . trim(($fila->Nombre ?? '') . ' ' . ($fila->Apellido ?? '')) . '.',
                    'Total: $ ' . number_format((float) $fila->Total_Final, 0, ',', '.') . '.',
                    'El stock volvió a las publicaciones. Productos:',
                ], $productos),
                url('/admin/pedidos')
            );
        } catch (\Throwable $e) {
            Log::warning('No se pudo avisar al admin de la cancelación del pedido #' . $id . ': ' . $e->getMessage());
        }
    }

    private static function textoAviso($id, string $estado, $tracking, $empresa): string
    {
        $texto = match ($estado) {
            'Enviado'     => '¡Aprobamos tu pedido #' . $id . '! Ya está siendo enviado.',
            'Entregado'   => 'Tu pedido #' . $id . ' figura como entregado. ¡Gracias por tu compra!',
            'Cancelado'   => 'Tu pedido #' . $id . ' fue cancelado. Si tenés dudas, escribinos desde Soporte.',
            'Preparacion' => 'Estamos preparando tu pedido #' . $id . '.',
            default       => 'Tu pedido #' . $id . ' volvió a estar pendiente.',
        };

        if ($estado === 'Enviado' && $tracking) {
            $texto .= ' Seguimiento: ' . $tracking . ($empresa ? ' (' . $empresa . ')' : '') . '.';
        }

        return $texto;
    }

    private function avisarPorMail(int $id): void
    {
        try {
            $fila = DB::table('pedido as p')
                ->join('cliente as c', 'c.Id_cliente', '=', 'p.Id_cliente')
                ->where('p.Id_pedido', $id)
                ->select('p.Estado_Pedido', 'c.Id_usuario', 'c.Nombre')
                ->first();

            if (!$fila) {
                return;
            }

            $usuario = User::where('Id_usuario', $fila->Id_usuario)->first();

            if (!$usuario || !$usuario->email) {
                return;
            }

            $envio = DB::table('envio')->where('Id_pedido', $id)->first();

            $mensaje = self::textoAviso(
                $id,
                $fila->Estado_Pedido ?? 'Pendiente',
                $envio ? $envio->Numero_Tracking : null,
                $envio ? $envio->Empresa_Logistica : null
            );

            $enlace = Route::has('cuenta.pedidos') ? route('cuenta.pedidos') : route('cuenta.index');

            $cuerpo = '<p>Hola ' . e($fila->Nombre) . ',</p>'
                . '<p>' . e($mensaje) . '</p>'
                . '<p><a href="' . e($enlace) . '">Ver mi pedido</a></p>'
                . '<p>Kiddy Shop</p>';

            Mail::html($cuerpo, function ($m) use ($usuario, $id) {
                $m->to($usuario->email)->subject('Kiddy Shop: novedades de tu pedido #' . $id);
            });
        } catch (\Throwable $e) {
            Log::warning('No se pudo enviar el mail del pedido #' . $id . ': ' . $e->getMessage());
        }
    }
}