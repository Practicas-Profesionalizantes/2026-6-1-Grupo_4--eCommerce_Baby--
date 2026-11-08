<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    private function soloAdmin()
    {
        abort_unless(Auth::check() && (int) Auth::user()->Id_rol === 2, 403);
    }

    public function index()
    {
        $this->soloAdmin();

        return response()->json(DB::table('pedido')->get());
    }

    public function showPedido($id)
    {
        $this->soloAdmin();

        $pedido = DB::table('pedido')->where('Id_pedido', $id)->first();
        abort_unless($pedido, 404);

        return response()->json($pedido);
    }

    public function updatePedido(Request $actualizacion, $id)
    {
        $this->soloAdmin();

        $datos = $actualizacion->validate([
            'Estado_Pedido' => 'required|in:Pendiente,Preparacion,Enviado,Entregado',
        ]);

        abort_unless(DB::table('pedido')->where('Id_pedido', $id)->exists(), 404);

        DB::table('pedido')->where('Id_pedido', $id)->update($datos);

        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => DB::table('pedido')->where('Id_pedido', $id)->first()
        ]);
    }

    public function destroyPedido($id)
    {
        $this->soloAdmin();

        abort_unless(DB::table('pedido')->where('Id_pedido', $id)->exists(), 404);

        DB::table('pedido')->where('Id_pedido', $id)->delete();

        return response()->json(['message' => 'Eliminado con éxito']);
    }

    public function misPedidos()
    {
        $idCliente = DB::table('cliente')
            ->where('Id_usuario', Auth::user()->Id_usuario)
            ->value('Id_cliente');

        if (!$idCliente) {
            return response()->json([]);
        }

        $pedidos = DB::table('pedido as p')
            ->leftJoin('direccion as d', 'd.Id_direccion', '=', 'p.Id_direccion')
            ->where('p.Id_cliente', $idCliente)
            ->orderByDesc('p.Id_pedido')
            ->select(
                'p.Id_pedido',
                'p.Fecha_Pedido',
                'p.Subtotal',
                'p.Descuento_Aplicado',
                'p.Total_Final',
                'p.Estado_Pedido',
                'p.Destinatario_Nombre',
                'p.Destinatario_Apellido',
                'p.Destinatario_Telefono',
                'd.Calle_Numero',
                'd.Calle',
                'd.Numero',
                'd.Departamento',
                'd.Barrio',
                'd.Ciudad',
                'd.Provincia',
                'd.Codigo_Postal'
            )
            ->get();

        if ($pedidos->isEmpty()) {
            return response()->json([]);
        }

        $ids = $pedidos->pluck('Id_pedido')->all();

        $productos = DB::table('detalle_pedido as dp')
            ->join('variante_producto as v', 'v.Id_variante', '=', 'dp.Id_variante')
            ->join('producto as pr', 'pr.Id_producto', '=', 'v.Id_producto')
            ->leftJoin('imagen_producto as i', function ($join) {
                $join->on('i.Id_producto', '=', 'pr.Id_producto')->where('i.Es_Portada', '=', 1);
            })
            ->whereIn('dp.Id_pedido', $ids)
            ->orderBy('dp.Id_detalle')
            ->select(
                'dp.Id_pedido',
                'dp.Cantidad',
                'dp.Precio_Unitario_Cobrado',
                'pr.Nombre as Producto',
                'v.Talle',
                'v.Color',
                'i.URL_Imagen'
            )
            ->get()
            ->groupBy('Id_pedido');

        $envios = DB::table('envio')
            ->whereIn('Id_pedido', $ids)
            ->orderBy('Id_envio')
            ->get()
            ->keyBy('Id_pedido');

        $pagos = DB::table('transaccion_pago as t')
            ->join('metodo_pago as m', 'm.Id_metodo_pago', '=', 't.Id_metodo_pago')
            ->whereIn('t.Id_pedido', $ids)
            ->orderBy('t.Id_transaccion')
            ->select('t.Id_pedido', 'm.Nombre as Metodo', 't.Estado_Pago')
            ->get()
            ->keyBy('Id_pedido');

        $resultado = $pedidos->map(function ($p) use ($productos, $envios, $pagos) {
            $envio = $envios->get($p->Id_pedido);
            $pago = $pagos->get($p->Id_pedido);
            $lista = $productos->get($p->Id_pedido, collect());
            $costoEnvio = (float) $p->Total_Final - ((float) $p->Subtotal - (float) $p->Descuento_Aplicado);

            $lineaCalle = $p->Calle_Numero;
            if ($p->Calle !== null && $p->Calle !== '') {
                $lineaCalle = trim($p->Calle . ' ' . $p->Numero);
                if ($p->Departamento !== null && $p->Departamento !== '') {
                    $lineaCalle .= ', Dpto ' . $p->Departamento;
                }
            }

            return [
                'id' => $p->Id_pedido,
                'fecha' => $p->Fecha_Pedido,
                'estado' => $p->Estado_Pedido,
                'subtotal' => (float) $p->Subtotal,
                'descuento' => (float) $p->Descuento_Aplicado,
                'costo_envio' => max(0, round($costoEnvio, 2)),
                'total' => (float) $p->Total_Final,
                'destinatario' => $p->Destinatario_Nombre !== null ? [
                    'nombre' => $p->Destinatario_Nombre,
                    'apellido' => $p->Destinatario_Apellido,
                    'telefono' => $p->Destinatario_Telefono,
                ] : null,
                'direccion' => $p->Calle_Numero !== null ? [
                    'calle' => $lineaCalle,
                    'barrio' => $p->Barrio,
                    'ciudad' => $p->Ciudad,
                    'provincia' => $p->Provincia,
                    'codigo_postal' => $p->Codigo_Postal,
                ] : null,
                'productos' => $lista->map(function ($x) {
                    return [
                        'nombre' => $x->Producto,
                        'talle' => $x->Talle,
                        'color' => $x->Color,
                        'cantidad' => (int) $x->Cantidad,
                        'precio' => (float) $x->Precio_Unitario_Cobrado,
                        'imagen' => $x->URL_Imagen,
                    ];
                })->values(),
                'envio' => $envio ? [
                    'empresa' => $envio->Empresa_Logistica,
                    'estado' => $envio->Estado_Envio,
                    'seguimiento' => $envio->Numero_Tracking,
                ] : null,
                'pago' => $pago ? [
                    'metodo' => $pago->Metodo,
                    'estado' => $pago->Estado_Pago,
                ] : null,
            ];
        });

        return response()->json($resultado->values());
    }
}