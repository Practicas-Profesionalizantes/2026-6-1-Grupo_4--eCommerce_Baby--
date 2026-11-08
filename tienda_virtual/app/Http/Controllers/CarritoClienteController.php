<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ClienteModel;

class CarritoClienteController extends Controller
{
    private function idCarrito()
    {
        $cliente = ClienteModel::firstOrCreate(
            ['Id_usuario' => Auth::user()->Id_usuario],
            [
                'Nombre'        => '',
                'Apellido'      => '',
                'DNI_CUIT'      => null,
                'Telefono'      => '00000000',
                'Condicion_IVA' => 'Consumidor Final',
            ]
        );

        $id = DB::table('carrito')
            ->where('Id_cliente', $cliente->Id_cliente)
            ->orderBy('Id_carrito')
            ->value('Id_carrito');

        if (!$id) {
            $id = DB::table('carrito')->insertGetId(['Id_cliente' => $cliente->Id_cliente]);
        }

        return (int) $id;
    }

    private function armar($idCarrito, $aviso = null)
    {
        $filas = DB::table('item_carrito as i')
            ->join('variante_producto as v', 'v.Id_variante', '=', 'i.Id_variante')
            ->join('producto as p', 'p.Id_producto', '=', 'v.Id_producto')
            ->where('i.Id_carrito', $idCarrito)
            ->orderBy('i.Id_item_carrito')
            ->select(
                'i.Id_item_carrito',
                'i.Cantidad',
                'v.Id_variante',
                'v.Id_producto',
                'v.Talle',
                'v.Color',
                'v.Precio_Venta',
                'v.Stock_Actual',
                'p.Nombre as Producto',
                'p.Activo as Producto_Activo'
            )
            ->get();

        $imagenes = DB::table('imagen_producto')
            ->whereIn('Id_producto', $filas->pluck('Id_producto')->unique()->all())
            ->where('Es_Portada', 1)
            ->pluck('URL_Imagen', 'Id_producto');

        $items    = [];
        $subtotal = 0;

        foreach ($filas as $f) {
            $stock      = (int) $f->Stock_Actual;
            $disponible = $stock >= 1 && (int) $f->Producto_Activo === 1;
            $cantidad   = (int) $f->Cantidad;
            if ($disponible) {
                $cantidad = min($cantidad, $stock);
            }
            $precio = (float) $f->Precio_Venta;
            $linea  = $disponible ? round($precio * $cantidad, 2) : 0;
            $subtotal += $linea;

            $items[] = [
                'id'          => (int) $f->Id_item_carrito,
                'id_variante' => (int) $f->Id_variante,
                'id_producto' => (int) $f->Id_producto,
                'producto'    => $f->Producto,
                'talle'       => $f->Talle,
                'color'       => $f->Color,
                'precio'      => $precio,
                'cantidad'    => $cantidad,
                'stock'       => $stock,
                'disponible'  => $disponible,
                'subtotal'    => $linea,
                'imagen'      => $imagenes[$f->Id_producto] ?? null,
            ];
        }

        return response()->json([
            'items'              => $items,
            'subtotal'           => round($subtotal, 2),
            'cantidad_productos' => count($items),
            'aviso'              => $aviso,
        ]);
    }

    public function index()
    {
        return $this->armar($this->idCarrito());
    }

    public function contador()
    {
        $cliente = ClienteModel::where('Id_usuario', Auth::user()->Id_usuario)->first();
        if (!$cliente) {
            return response()->json(['total' => 0]);
        }

        $idCarrito = DB::table('carrito')
            ->where('Id_cliente', $cliente->Id_cliente)
            ->orderBy('Id_carrito')
            ->value('Id_carrito');

        if (!$idCarrito) {
            return response()->json(['total' => 0]);
        }

        return response()->json([
            'total' => DB::table('item_carrito')->where('Id_carrito', $idCarrito)->count(),
        ]);
    }

    public function agregar(Request $request)
    {
        $datos = $request->validate([
            'id_variante' => ['required', 'integer'],
            'cantidad'    => ['required', 'integer', 'min:1'],
        ], [
            'required' => 'Falta elegir el talle y color.',
            'integer'  => 'El dato enviado no es válido.',
            'min'      => 'La cantidad tiene que ser al menos 1.',
        ]);

        $variante = DB::table('variante_producto as v')
            ->join('producto as p', 'p.Id_producto', '=', 'v.Id_producto')
            ->where('v.Id_variante', $datos['id_variante'])
            ->select('v.*', 'p.Activo as Producto_Activo')
            ->first();

        if (!$variante || (int) $variante->Producto_Activo !== 1) {
            return response()->json(['mensaje' => 'Este producto no está disponible por el momento.'], 422);
        }

        if ((int) $variante->Stock_Actual < 1) {
            return response()->json(['mensaje' => 'Este producto no tiene stock por el momento.'], 422);
        }

        $idCarrito = $this->idCarrito();
        $stock     = (int) $variante->Stock_Actual;

        $item = DB::table('item_carrito')
            ->where('Id_carrito', $idCarrito)
            ->where('Id_variante', $variante->Id_variante)
            ->first();

        $nueva    = ($item ? (int) $item->Cantidad : 0) + (int) $datos['cantidad'];
        $ajustada = $nueva > $stock;
        $nueva    = min($nueva, $stock);

        if ($item) {
            DB::table('item_carrito')
                ->where('Id_item_carrito', $item->Id_item_carrito)
                ->update(['Cantidad' => $nueva]);
        } else {
            DB::table('item_carrito')->insert([
                'Id_carrito'  => $idCarrito,
                'Id_variante' => $variante->Id_variante,
                'Cantidad'    => $nueva,
            ]);
        }

        return response()->json([
            'mensaje'            => $ajustada
                ? 'Agregado. Dejamos la cantidad máxima disponible (' . $stock . ').'
                : 'Agregado al carrito.',
            'cantidad_productos' => DB::table('item_carrito')->where('Id_carrito', $idCarrito)->count(),
        ]);
    }

    public function actualizar(Request $request, $id)
    {
        $datos = $request->validate([
            'cantidad' => ['required', 'integer', 'min:1'],
        ], [
            'required' => 'Falta la cantidad.',
            'integer'  => 'La cantidad tiene que ser un número entero.',
            'min'      => 'La cantidad tiene que ser al menos 1.',
        ]);

        $idCarrito = $this->idCarrito();

        $item = DB::table('item_carrito')
            ->where('Id_item_carrito', (int) $id)
            ->where('Id_carrito', $idCarrito)
            ->first();

        if (!$item) {
            return response()->json(['mensaje' => 'Ese producto ya no está en tu carrito.'], 404);
        }

        $stock = (int) DB::table('variante_producto')
            ->where('Id_variante', $item->Id_variante)
            ->value('Stock_Actual');

        $cantidad = (int) $datos['cantidad'];
        $aviso    = null;

        if ($stock >= 1 && $cantidad > $stock) {
            $cantidad = $stock;
            $aviso    = 'Solo hay ' . $stock . ' disponibles, dejamos esa cantidad.';
        }

        DB::table('item_carrito')
            ->where('Id_item_carrito', $item->Id_item_carrito)
            ->update(['Cantidad' => $cantidad]);

        return $this->armar($idCarrito, $aviso);
    }

    public function quitar($id)
    {
        $idCarrito = $this->idCarrito();

        DB::table('item_carrito')
            ->where('Id_item_carrito', (int) $id)
            ->where('Id_carrito', $idCarrito)
            ->delete();

        return $this->armar($idCarrito);
    }
}