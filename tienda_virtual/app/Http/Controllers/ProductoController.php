<?php

namespace App\Http\Controllers;

use App\Models\ProductoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller{

    public function catalogo()
    {
        return view('catalog');
    }

    public function catalogoApi()
    {
        $productos = DB::table('producto')
            ->where('Activo', 1)
            ->orderByDesc('Destacado')
            ->get();

        $ids = $productos->pluck('Id_producto');

        $categorias = DB::table('categoria')->pluck('Nombre', 'Id_categoria');

        $variantes = DB::table('variante_producto')
            ->whereIn('Id_producto', $ids)
            ->get()
            ->groupBy('Id_producto');

        $imagenes = DB::table('imagen_producto')
            ->whereIn('Id_producto', $ids)
            ->orderByDesc('Es_Portada')
            ->get()
            ->groupBy('Id_producto');

        $resultado = $productos->map(function ($p) use ($variantes, $imagenes, $categorias) {
            $vars = $variantes->get($p->Id_producto, collect());
            $imgs = $imagenes->get($p->Id_producto, collect());

            return [
                'Id_producto'  => $p->Id_producto,
                'Id_categoria' => $p->Id_categoria,
                'Categoria'    => $categorias->get($p->Id_categoria),
                'Nombre'       => $p->Nombre,
                'Descripcion'  => $p->Descripcion,
                'Destacado'    => $p->Destacado,
                'Precio_Desde' => $vars->map(fn ($v) => (float) $v->Precio_Venta)->min(),
                'Stock_Total'  => (int) $vars->sum('Stock_Actual'),
                'Talles'       => $vars->pluck('Talle')->filter()->unique()->values(),
                'Variantes'    => $vars->map(fn ($v) => [
                    'Id_variante'  => (int) $v->Id_variante,
                    'Talle'        => $v->Talle,
                    'Color'        => $v->Color,
                    'Precio_Venta' => (float) $v->Precio_Venta,
                    'Stock_Actual' => (int) $v->Stock_Actual,
                ])->values(),
                'Imagen'       => optional($imgs->first())->URL_Imagen,
            ];
        })->values();

        return response()->json($resultado);
    }

    public function detalleApi($id)
    {
        $producto = DB::table('producto')
            ->where('Id_producto', $id)
            ->where('Activo', 1)
            ->first();

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $categoria = DB::table('categoria')
            ->where('Id_categoria', $producto->Id_categoria)
            ->value('Nombre');

        $variantes = DB::table('variante_producto')
            ->where('Id_producto', $id)
            ->orderBy('Precio_Venta')
            ->get();

        $imagenes = DB::table('imagen_producto')
            ->where('Id_producto', $id)
            ->orderByDesc('Es_Portada')
            ->get();

        return response()->json([
            'Id_producto' => $producto->Id_producto,
            'Nombre'      => $producto->Nombre,
            'Descripcion' => $producto->Descripcion,
            'Categoria'   => $categoria,
            'Variantes'   => $variantes->map(fn ($v) => [
                'Id_variante'  => $v->Id_variante,
                'SKU'          => $v->SKU,
                'Talle'        => $v->Talle,
                'Color'        => $v->Color,
                'Precio_Venta' => (float) $v->Precio_Venta,
                'Stock_Actual' => (int) $v->Stock_Actual,
            ])->values(),
            'Imagenes'    => $imagenes->pluck('URL_Imagen')->values(),
        ]);
    }

    public function index()
    {
        $productos = DB::table('producto')->get();

        return response()->json($productos);
    }

    public function showProducto($id)
    {
        $producto = ProductoModel::findOrFail($id);
        return response()->json($producto);
    }

    public function updateProducto(Request $actualizacion, $id)
    {
        $producto = ProductoModel::findOrFail($id);

        $producto->update($actualizacion->all());

        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $producto
        ]);
    }

    public function destroyProducto($id)
    {
        $producto = ProductoModel::findOrFail($id);
        $producto->delete();

        return response()->json(['message' => 'Eliminado con éxito']);
    }
}