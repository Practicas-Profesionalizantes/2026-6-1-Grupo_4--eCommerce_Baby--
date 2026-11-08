<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\ClienteModel;
use App\Support\AvisoAdmin;

class CompraController extends Controller
{
    private const PROVINCIAS = [
        'Buenos Aires', 'Ciudad Autónoma de Buenos Aires', 'Catamarca', 'Chaco', 'Chubut',
        'Córdoba', 'Corrientes', 'Entre Ríos', 'Formosa', 'Jujuy', 'La Pampa', 'La Rioja',
        'Mendoza', 'Misiones', 'Neuquén', 'Río Negro', 'Salta', 'San Juan', 'San Luis',
        'Santa Cruz', 'Santa Fe', 'Santiago del Estero', 'Tierra del Fuego', 'Tucumán',
    ];

    private const CONDICIONES_IVA = [
        'Consumidor Final' => 'Consumidor Final',
        'Resp. Inscripto'  => 'Responsable Inscripto',
    ];

    private function clienteActual()
    {
        return ClienteModel::firstOrCreate(
            ['Id_usuario' => Auth::user()->Id_usuario],
            [
                'Nombre'        => '',
                'Apellido'      => '',
                'DNI_CUIT'      => null,
                'Telefono'      => '00000000',
                'Condicion_IVA' => 'Consumidor Final',
            ]
        );
    }

    private function datosFaltantes($cliente)
    {
        $faltan = [];

        if (trim((string) $cliente->Nombre) === '') {
            $faltan[] = 'nombre';
        }
        if (trim((string) $cliente->Apellido) === '') {
            $faltan[] = 'apellido';
        }
        if (in_array($cliente->Telefono, [null, '', '00000000'], true)) {
            $faltan[] = 'teléfono';
        }
        if (!$cliente->DNI_CUIT) {
            $faltan[] = 'DNI o CUIT';
        }
        if (!$cliente->Condicion_IVA) {
            $faltan[] = 'condición frente al IVA';
        }

        return $faltan;
    }

    private function cargarItems()
    {
        $sel = session('compra_items', []);
        if (!is_array($sel) || count($sel) === 0) {
            return collect();
        }

        $ids = array_map(function ($s) {
            return (int) $s['id_variante'];
        }, $sel);

        $filas = DB::table('variante_producto as v')
            ->join('producto as p', 'p.Id_producto', '=', 'v.Id_producto')
            ->whereIn('v.Id_variante', $ids)
            ->select('v.Id_variante', 'v.Id_producto', 'v.Talle', 'v.Color', 'v.Precio_Venta', 'v.Stock_Actual', 'p.Nombre as Producto', 'p.Activo as Producto_Activo')
            ->get()
            ->keyBy('Id_variante');

        $imagenes = DB::table('imagen_producto')
            ->whereIn('Id_producto', $filas->pluck('Id_producto')->unique()->all())
            ->where('Es_Portada', 1)
            ->pluck('URL_Imagen', 'Id_producto');

        $items = collect();

        foreach ($sel as $s) {
            $fila = $filas->get((int) $s['id_variante']);
            if (!$fila || (int) $fila->Stock_Actual < 1 || (int) $fila->Producto_Activo !== 1) {
                continue;
            }

            $cantidad = max(1, (int) ($s['cantidad'] ?? 1));
            $cantidad = min($cantidad, (int) $fila->Stock_Actual);
            $precio   = (float) $fila->Precio_Venta;

            $items->push((object) [
                'id_variante' => (int) $fila->Id_variante,
                'id_producto' => (int) $fila->Id_producto,
                'producto'    => $fila->Producto,
                'talle'       => $fila->Talle,
                'color'       => $fila->Color,
                'precio'      => $precio,
                'cantidad'    => $cantidad,
                'subtotal'    => round($precio * $cantidad, 2),
                'stock'       => (int) $fila->Stock_Actual,
                'imagen'      => $imagenes[$fila->Id_producto] ?? null,
            ]);
        }

        return $items;
    }

    private function salidaSinItems()
    {
        return redirect(session('compra_origen') === 'carrito' ? '/carrito' : '/catalogo');
    }

    public function mostrarProducto($id)
    {
        return view('producto', ['idProducto' => (int) $id]);
    }

    public function comprarCarrito()
    {
        $cliente = $this->clienteActual();

        $idCarrito = DB::table('carrito')
            ->where('Id_cliente', $cliente->Id_cliente)
            ->orderBy('Id_carrito')
            ->value('Id_carrito');

        $lineas = $idCarrito
            ? DB::table('item_carrito as i')
                ->join('variante_producto as v', 'v.Id_variante', '=', 'i.Id_variante')
                ->join('producto as p', 'p.Id_producto', '=', 'v.Id_producto')
                ->where('i.Id_carrito', $idCarrito)
                ->where('v.Stock_Actual', '>=', 1)
                ->where('p.Activo', 1)
                ->orderBy('i.Id_item_carrito')
                ->select('i.Id_variante', 'i.Cantidad')
                ->get()
            : collect();

        if ($lineas->isEmpty()) {
            return redirect('/carrito');
        }

        session([
            'compra_items'  => $lineas->map(function ($l) {
                return ['id_variante' => (int) $l->Id_variante, 'cantidad' => (int) $l->Cantidad];
            })->all(),
            'compra_origen' => 'carrito',
        ]);

        return redirect()->route('checkout.datos');
    }

    public function datosComprador(Request $request)
    {
        if ($request->filled('variante')) {
            $variante = DB::table('variante_producto as v')
                ->join('producto as p', 'p.Id_producto', '=', 'v.Id_producto')
                ->where('v.Id_variante', (int) $request->query('variante'))
                ->where('p.Activo', 1)
                ->select('v.*')
                ->first();

            if (!$variante || $variante->Stock_Actual < 1) {
                return redirect('/catalogo');
            }

            $cantidad = (int) $request->query('cantidad', 1);
            $cantidad = max(1, min($cantidad, (int) $variante->Stock_Actual));

            session([
                'compra_items'  => [['id_variante' => (int) $variante->Id_variante, 'cantidad' => $cantidad]],
                'compra_origen' => 'directo',
            ]);
        }

        $cliente  = $this->clienteActual();
        $telefono = $cliente->Telefono === '00000000' ? '' : $cliente->Telefono;

        $direccion = DB::table('direccion')
            ->where('Id_cliente', $cliente->Id_cliente)
            ->where('Tipo', 'Envio')
            ->orderByDesc('Id_direccion')
            ->first();

        $domicilio = [];
        if ($direccion) {
            $domicilio = [
                'ciudad'        => $direccion->Ciudad,
                'provincia'     => $direccion->Provincia,
                'codigo_postal' => $direccion->Codigo_Postal ?? '',
            ];
            if ($direccion->Calle !== null && $direccion->Calle !== '') {
                $domicilio['calle']        = $direccion->Calle;
                $domicilio['numero']       = $direccion->Numero ?? '';
                $domicilio['departamento'] = $direccion->Departamento ?? '';
                $domicilio['barrio']       = $direccion->Barrio ?? '';
            }
        }

        $form = array_merge([
            'nombre'        => $cliente->Nombre,
            'apellido'      => $cliente->Apellido,
            'telefono'      => $telefono,
            'dni'           => $cliente->DNI_CUIT ?? '',
            'condicion_iva' => $cliente->Condicion_IVA ?? 'Consumidor Final',
        ], $domicilio, session('checkout.form', []));

        return view('comprador', [
            'form'        => $form,
            'provincias'  => self::PROVINCIAS,
            'condiciones' => self::CONDICIONES_IVA,
        ]);
    }

    public function guardarDatosComprador(Request $request)
    {
        $cliente = $this->clienteActual();

        $reglas = [
            'nombre'        => ['required', 'string', 'max:50', 'regex:/^[\pL\s\'.\-]+$/u'],
            'apellido'      => ['required', 'string', 'max:50', 'regex:/^[\pL\s\'.\-]+$/u'],
            'telefono'      => [
                'required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/',
                function ($attribute, $value, $fail) {
                    $digitos = preg_replace('/\D/', '', $value);
                    if (strlen($digitos) < 8 || strlen($digitos) > 15) {
                        $fail('El teléfono tiene que tener entre 8 y 15 números.');
                    }
                },
            ],
            'calle'         => ['required', 'string', 'max:40'],
            'numero'        => ['required', 'string', 'max:8'],
            'departamento'  => ['nullable', 'string', 'max:8'],
            'barrio'        => ['required', 'string', 'max:25'],
            'ciudad'        => ['required', 'string', 'max:50'],
            'codigo_postal' => ['required', 'string', 'regex:/^([0-9]{4}|[A-Za-z][0-9]{4}[A-Za-z]{3})$/'],
            'provincia'     => ['required', Rule::in(self::PROVINCIAS)],
            'dni'           => [
                'required', 'string',
                function ($attribute, $value, $fail) use ($cliente) {
                    $digitos = preg_replace('/\D/', '', $value);
                    if (!preg_match('/^[0-9.\-\s]+$/', $value)) {
                        $fail('El DNI o CUIT solo puede tener números.');
                    } elseif (!in_array(strlen($digitos), [7, 8, 11], true)) {
                        $fail('El DNI tiene 7 u 8 números, o el CUIT tiene 11.');
                    } elseif (DB::table('cliente')->where('DNI_CUIT', $digitos)->where('Id_cliente', '!=', $cliente->Id_cliente)->exists()) {
                        $fail('Ese DNI o CUIT ya está registrado en otra cuenta.');
                    }
                },
            ],
            'condicion_iva' => ['required', Rule::in(array_keys(self::CONDICIONES_IVA))],
            'terminos'      => ['accepted'],
        ];

        $mensajes = [
            'required' => 'Completá el campo :attribute.',
            'max'      => 'El campo :attribute puede tener hasta :max caracteres.',
            'regex'    => 'El campo :attribute no tiene un formato válido.',
            'provincia.in'      => 'Elegí una provincia de la lista.',
            'condicion_iva.in'  => 'Elegí una condición frente al IVA de la lista.',
            'accepted' => 'Tenés que aceptar los términos y condiciones.',
            'string'   => 'El campo :attribute no es válido.',
        ];

        $atributos = [
            'nombre'        => 'nombre',
            'apellido'      => 'apellido',
            'telefono'      => 'teléfono',
            'calle'         => 'calle',
            'numero'        => 'número',
            'departamento'  => 'departamento',
            'barrio'        => 'barrio',
            'ciudad'        => 'ciudad',
            'codigo_postal' => 'código postal (4 números, o formato A1234BCD)',
            'provincia'     => 'provincia',
            'dni'           => 'DNI o CUIT',
            'condicion_iva' => 'condición frente al IVA',
            'terminos'      => 'términos y condiciones',
        ];

        $datos = $request->validate($reglas, $mensajes, $atributos);

        $datos['dni'] = preg_replace('/\D/', '', $datos['dni']);

        $cliente->DNI_CUIT      = $datos['dni'];
        $cliente->Condicion_IVA = $datos['condicion_iva'];
        if (trim((string) $cliente->Nombre) === '') {
            $cliente->Nombre = $datos['nombre'];
        }
        if (trim((string) $cliente->Apellido) === '') {
            $cliente->Apellido = $datos['apellido'];
        }
        if (in_array($cliente->Telefono, [null, '', '00000000'], true)) {
            $cliente->Telefono = $datos['telefono'];
        }
        $cliente->save();

        $departamento = !empty($datos['departamento']) ? $datos['departamento'] : null;

        $calleNumero = $datos['calle'] . ' ' . $datos['numero'];
        if ($departamento !== null) {
            $calleNumero .= ', Dpto ' . $departamento;
        }
        $calleNumero .= ', Barrio ' . $datos['barrio'];

        $codigoPostal = strtoupper($datos['codigo_postal']);

        $consulta = DB::table('direccion')
            ->where('Id_cliente', $cliente->Id_cliente)
            ->where('Tipo', 'Envio')
            ->where('Calle', $datos['calle'])
            ->where('Numero', $datos['numero'])
            ->where('Barrio', $datos['barrio'])
            ->where('Ciudad', $datos['ciudad'])
            ->where('Provincia', $datos['provincia'])
            ->where('Codigo_Postal', $codigoPostal);

        if ($departamento === null) {
            $consulta->whereNull('Departamento');
        } else {
            $consulta->where('Departamento', $departamento);
        }

        $idDireccion = $consulta->value('Id_direccion');

        if (!$idDireccion) {
            $idDireccion = DB::table('direccion')->insertGetId([
                'Id_cliente'    => $cliente->Id_cliente,
                'Calle_Numero'  => mb_substr($calleNumero, 0, 100),
                'Calle'         => $datos['calle'],
                'Numero'        => $datos['numero'],
                'Departamento'  => $departamento,
                'Barrio'        => $datos['barrio'],
                'Ciudad'        => $datos['ciudad'],
                'Provincia'     => $datos['provincia'],
                'Codigo_Postal' => $codigoPostal,
                'Tipo'          => 'Envio',
            ]);
        }

        session(['checkout' => [
            'id_direccion' => $idDireccion,
            'form'         => $datos,
            'destinatario' => [
                'nombre'   => $datos['nombre'],
                'apellido' => $datos['apellido'],
                'telefono' => $datos['telefono'],
            ],
        ]]);

        return redirect('/checkout/finalizar');
    }

    public function finalizarCompra()
    {
        if (!session('checkout.id_direccion')) {
            return redirect('/checkout/datos')
                ->withErrors(['datos' => 'Primero completá los datos del comprador.']);
        }

        $faltan = $this->datosFaltantes($this->clienteActual());
        if ($faltan) {
            return redirect('/checkout/datos')
                ->withErrors(['datos' => 'Para comprar y recibir tu factura completá: ' . implode(', ', $faltan) . '.']);
        }

        $items = $this->cargarItems();

        if ($items->isEmpty()) {
            return $this->salidaSinItems();
        }

        return view('checkout', [
            'items'        => $items,
            'subtotal'     => round($items->sum('subtotal'), 2),
            'metodosPago'  => DB::table('metodo_pago')->where('Activo', 1)->orderBy('Id_metodo_pago')->get(),
            'metodosEnvio' => DB::table('metodo_envio')->where('Activo', 1)->orderBy('Id_metodo_envio')->get(),
        ]);
    }

    public function procesarPago(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (!session('checkout.id_direccion')) {
            return redirect('/checkout/datos')
                ->withErrors(['datos' => 'Primero completá los datos del comprador.']);
        }

        $faltan = $this->datosFaltantes($this->clienteActual());
        if ($faltan) {
            return redirect('/checkout/datos')
                ->withErrors(['datos' => 'Para comprar y recibir tu factura completá: ' . implode(', ', $faltan) . '.']);
        }

        $items = $this->cargarItems();
        if ($items->isEmpty()) {
            return $this->salidaSinItems();
        }

        $mensajes = [
            'required' => 'Completá el campo :attribute.',
            'exists'   => 'Elegí una opción válida para :attribute.',
            'regex'    => 'El campo :attribute no tiene un formato válido.',
            'max'      => 'El campo :attribute es demasiado largo.',
        ];

        $atributos = [
            'metodo_pago'    => 'método de pago',
            'metodo_envio'   => 'método de envío',
            'numero_tarjeta' => 'número de tarjeta',
            'vencimiento'    => 'vencimiento (formato MM/AA)',
            'cvc'            => 'CVC (3 o 4 números)',
        ];

        $request->validate([
            'metodo_pago'        => ['required', Rule::exists('metodo_pago', 'Id_metodo_pago')->where('Activo', 1)],
            'metodo_envio'       => ['required', Rule::exists('metodo_envio', 'Id_metodo_envio')->where('Activo', 1)],
            'banco_seleccionado' => ['nullable', 'string', 'max:30'],
        ], $mensajes, $atributos);

        $metodoPago  = DB::table('metodo_pago')->where('Id_metodo_pago', $request->input('metodo_pago'))->first();
        $metodoEnvio = DB::table('metodo_envio')->where('Id_metodo_envio', $request->input('metodo_envio'))->first();

        if (str_contains(mb_strtolower($metodoPago->Nombre), 'tarjeta')) {
            $request->validate([
                'numero_tarjeta' => ['required', 'regex:/^[0-9 \-]{13,23}$/'],
                'vencimiento'    => ['required', 'regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/'],
                'cvc'            => ['required', 'regex:/^[0-9]{3,4}$/'],
            ], $mensajes, $atributos);
        }

        $cliente      = $this->clienteActual();
        $idDireccion  = session('checkout.id_direccion');
        $destinatario = session('checkout.destinatario', []);
        $banco        = $request->input('banco_seleccionado');
        $origen       = session('compra_origen');

        try {
            $idPedido = DB::transaction(function () use ($items, $metodoPago, $metodoEnvio, $cliente, $idDireccion, $destinatario, $banco, $origen) {

                $ids = $items->pluck('id_variante')->unique()->sort()->values()->all();

                $variantes = DB::table('variante_producto')
                    ->whereIn('Id_variante', $ids)
                    ->orderBy('Id_variante')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('Id_variante');

                $subtotal = 0;

                foreach ($items as $it) {
                    $v = $variantes->get($it->id_variante);

                    if (!$v || (int) $v->Stock_Actual < $it->cantidad) {
                        throw new \RuntimeException('Lo sentimos, ya no queda stock suficiente de "' . $it->producto . '". No se cobró nada.');
                    }

                    $subtotal += round((float) $v->Precio_Venta * $it->cantidad, 2);
                }

                $subtotal   = round($subtotal, 2);
                $costoEnvio = (float) $metodoEnvio->Costo_Base;
                $total      = round($subtotal + $costoEnvio, 2);

                $idPedido = DB::table('pedido')->insertGetId([
                    'Id_cliente'            => $cliente->Id_cliente,
                    'Id_direccion'          => $idDireccion,
                    'Id_metodo_envio'       => $metodoEnvio->Id_metodo_envio,
                    'Subtotal'              => $subtotal,
                    'Descuento_Aplicado'    => 0,
                    'Total_Final'           => $total,
                    'Estado_Pedido'         => 'Preparacion',
                    'Destinatario_Nombre'   => $destinatario['nombre'] ?? null,
                    'Destinatario_Apellido' => $destinatario['apellido'] ?? null,
                    'Destinatario_Telefono' => $destinatario['telefono'] ?? null,
                ]);

                foreach ($items as $it) {
                    $v = $variantes->get($it->id_variante);

                    DB::table('detalle_pedido')->insert([
                        'Id_pedido'                  => $idPedido,
                        'Id_variante'                => $v->Id_variante,
                        'Cantidad'                   => $it->cantidad,
                        'Precio_Unitario_Cobrado'    => (float) $v->Precio_Venta,
                        'Costo_Unitario_Fabricacion' => null,
                    ]);

                    DB::table('variante_producto')
                        ->where('Id_variante', $v->Id_variante)
                        ->decrement('Stock_Actual', $it->cantidad);
                }

                $referencia = 'SIM-' . now()->format('ymdHis') . '-' . strtoupper(Str::random(4));
                if (!empty($banco)) {
                    $referencia .= '-' . strtoupper($banco);
                }

                DB::table('transaccion_pago')->insert([
                    'Id_pedido'           => $idPedido,
                    'Id_metodo_pago'      => $metodoPago->Id_metodo_pago,
                    'Monto'               => $total,
                    'Estado_Pago'         => 'Aprobado',
                    'Referencia_Pasarela' => $referencia,
                ]);

                DB::table('envio')->insert([
                    'Id_pedido'         => $idPedido,
                    'Empresa_Logistica' => $metodoEnvio->Nombre,
                    'Numero_Tracking'   => null,
                    'Estado_Envio'      => 'Preparando',
                    'Fecha_Despacho'    => null,
                ]);

                $cuitReceptor = DB::table('cliente')
                    ->where('Id_cliente', $cliente->Id_cliente)
                    ->value('DNI_CUIT');

                $nroFactura = '0001-' . str_pad((string) (DB::table('factura')->max('Id_factura') + 1), 8, '0', STR_PAD_LEFT);

                DB::table('factura')->insert([
                    'Id_pedido'        => $idPedido,
                    'Tipo_Comprobante' => 'B',
                    'Nro_Comprobante'  => $nroFactura,
                    'CUIT_Receptor'    => $cuitReceptor ?: '00000000000',
                    'Fecha_Emision'    => now()->format('Y-m-d'),
                    'Leida'            => 0
                ]);

                if ($origen === 'carrito') {
                    $idCarrito = DB::table('carrito')
                        ->where('Id_cliente', $cliente->Id_cliente)
                        ->orderBy('Id_carrito')
                        ->value('Id_carrito');

                    if ($idCarrito) {
                        DB::table('item_carrito')
                            ->where('Id_carrito', $idCarrito)
                            ->whereIn('Id_variante', $ids)
                            ->delete();
                    }
                }

                return $idPedido;
            });
        } catch (\RuntimeException $e) {
            return redirect('/checkout/finalizar')->withErrors(['stock' => $e->getMessage()]);
        }

        $totalPedido = DB::table('pedido')->where('Id_pedido', $idPedido)->value('Total_Final');

        $lineasProductos = $items->map(function ($it) {
            return $it->cantidad . ' x ' . $it->producto . ' (talle ' . $it->talle . ', ' . $it->color . ')';
        })->all();

        AvisoAdmin::enviar(
            'Kiddy Shop: nuevo pedido #' . $idPedido,
            array_merge([
                'Entró un pedido nuevo: #' . $idPedido . '.',
                'Cliente: ' . trim($cliente->Nombre . ' ' . $cliente->Apellido) . '.',
                'Total: $ ' . number_format((float) $totalPedido, 0, ',', '.') . '.',
                'Envío: ' . $metodoEnvio->Nombre . '. Pago: ' . $metodoPago->Nombre . '.',
                'Productos:',
            ], $lineasProductos),
            url('/admin/pedidos')
        );

        session()->forget(['compra_items', 'compra_origen', 'checkout']);

        return redirect('/mi-cuenta/pedidos')->with('success', '¡Gracias por tu compra! Tu pedido #' . $idPedido . ' fue registrado.');
    }
}