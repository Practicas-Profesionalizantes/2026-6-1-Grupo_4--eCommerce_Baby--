<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminProductoController extends Controller
{
    public function categorias()
    {
        return response()->json(
            DB::table('categoria')->orderBy('Nombre')->get(['Id_categoria', 'Nombre'])
        );
    }

    public function listar(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $consulta = DB::table('producto')->orderByDesc('Id_producto');
        if ($q !== '') {
            $consulta->where('Nombre', 'like', '%' . $q . '%');
        }
        $productos = $consulta->get();
        $ids = $productos->pluck('Id_producto');

        $categorias = DB::table('categoria')->pluck('Nombre', 'Id_categoria');
        $variantes = DB::table('variante_producto')->whereIn('Id_producto', $ids)->get()->groupBy('Id_producto');
        $imagenes = DB::table('imagen_producto')->whereIn('Id_producto', $ids)->orderByDesc('Es_Portada')->get()->groupBy('Id_producto');

        $resultado = $productos->map(function ($p) use ($categorias, $variantes, $imagenes) {
            $vars = $variantes->get($p->Id_producto, collect());
            $imgs = $imagenes->get($p->Id_producto, collect());

            return [
                'Id_producto'  => $p->Id_producto,
                'Nombre'       => $p->Nombre,
                'Categoria'    => $categorias->get($p->Id_categoria),
                'Activo'       => (int) $p->Activo,
                'Destacado'    => (int) $p->Destacado,
                'Precio_Desde' => $vars->map(fn ($v) => (float) $v->Precio_Venta)->min(),
                'Stock_Total'  => (int) $vars->sum('Stock_Actual'),
                'Imagen'       => optional($imgs->first())->URL_Imagen,
            ];
        })->values();

        return response()->json($resultado);
    }

    public function mostrar($id)
    {
        $producto = DB::table('producto')->where('Id_producto', $id)->first();

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $variantes = DB::table('variante_producto')->where('Id_producto', $id)->orderBy('Id_variante')->get();
        $imagen = DB::table('imagen_producto')->where('Id_producto', $id)->orderByDesc('Es_Portada')->first();

        return response()->json([
            'Id_producto'  => $producto->Id_producto,
            'Nombre'       => $producto->Nombre,
            'Descripcion'  => $producto->Descripcion,
            'Id_categoria' => $producto->Id_categoria,
            'Destacado'    => (int) $producto->Destacado,
            'Activo'       => (int) $producto->Activo,
            'Imagen'       => optional($imagen)->URL_Imagen,
            'Variantes'    => $variantes->map(fn ($v) => [
                'Id_variante'  => $v->Id_variante,
                'SKU'          => $v->SKU,
                'Talle'        => $v->Talle,
                'Color'        => $v->Color,
                'Precio_Venta' => (float) $v->Precio_Venta,
                'Stock_Actual' => (int) $v->Stock_Actual,
            ])->values(),
        ]);
    }

    public function crear(Request $request)
    {
        $datos = $request->validate($this->reglas(true), $this->mensajes());
        $variantes = $this->leerVariantes($request);

        $ruta = $this->guardarImagen($request->file('imagen'), $datos['nombre']);

        $id = DB::transaction(function () use ($datos, $variantes, $request, $ruta) {
            $idProducto = DB::table('producto')->insertGetId([
                'Id_categoria'        => $datos['id_categoria'],
                'Nombre'              => $datos['nombre'],
                'Descripcion'         => $datos['descripcion'] ?? '',
                'Porcentaje_Ganancia' => 0,
                'Activo'              => 1,
                'Destacado'           => $request->boolean('destacado') ? 1 : 0,
            ], 'Id_producto');

            foreach ($variantes as $v) {
                $this->insertarVariante($idProducto, $v);
            }

            DB::table('imagen_producto')->insert([
                'Id_producto' => $idProducto,
                'URL_Imagen'  => $ruta,
                'Es_Portada'  => 1,
            ]);

            return $idProducto;
        });

        return response()->json(['message' => 'Producto publicado', 'Id_producto' => $id], 201);
    }

    public function actualizar(Request $request, $id)
    {
        $producto = DB::table('producto')->where('Id_producto', $id)->first();

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $datos = $request->validate($this->reglas(false), $this->mensajes());
        $variantes = $this->leerVariantes($request);

        $ruta = $request->hasFile('imagen')
            ? $this->guardarImagen($request->file('imagen'), $datos['nombre'])
            : null;

        DB::transaction(function () use ($id, $datos, $variantes, $request, $ruta) {
            DB::table('producto')->where('Id_producto', $id)->update([
                'Id_categoria' => $datos['id_categoria'],
                'Nombre'       => $datos['nombre'],
                'Descripcion'  => $datos['descripcion'] ?? '',
                'Destacado'    => $request->boolean('destacado') ? 1 : 0,
            ]);

            foreach ($variantes as $v) {
                $idVariante = $v['Id_variante'] ?? null;
                $existe = $idVariante
                    ? DB::table('variante_producto')->where('Id_variante', $idVariante)->where('Id_producto', $id)->exists()
                    : false;

                if ($existe) {
                    DB::table('variante_producto')->where('Id_variante', $idVariante)->update([
                        'Talle'        => $v['Talle'],
                        'Color'        => $v['Color'],
                        'Precio_Venta' => $v['Precio_Venta'],
                        'Stock_Actual' => $v['Stock_Actual'],
                    ]);
                } else {
                    $this->insertarVariante($id, $v);
                }
            }

            if ($ruta) {
                $portada = DB::table('imagen_producto')
                    ->where('Id_producto', $id)
                    ->orderByDesc('Es_Portada')
                    ->first();

                if ($portada) {
                    DB::table('imagen_producto')->where('Id_imagen', $portada->Id_imagen)->update([
                        'URL_Imagen' => $ruta,
                        'Es_Portada' => 1,
                    ]);
                } else {
                    DB::table('imagen_producto')->insert([
                        'Id_producto' => $id,
                        'URL_Imagen'  => $ruta,
                        'Es_Portada'  => 1,
                    ]);
                }
            }
        });

        return response()->json(['message' => 'Cambios guardados']);
    }

    public function visibilidad($id)
    {
        $producto = DB::table('producto')->where('Id_producto', $id)->first();

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $nuevo = $producto->Activo ? 0 : 1;
        DB::table('producto')->where('Id_producto', $id)->update(['Activo' => $nuevo]);

        return response()->json(['Activo' => $nuevo]);
    }

    public function cambiarStock(Request $request, $id)
    {
        $datos = $request->validate([
            'stock' => ['required', 'integer', 'min:0', 'max:1000000'],
        ], [
            'stock.required' => 'Escribí la cantidad de stock.',
            'stock.integer'  => 'El stock tiene que ser un número entero.',
            'stock.min'      => 'El stock no puede ser negativo.',
            'stock.max'      => 'El stock es demasiado grande.',
        ]);

        $variante = DB::table('variante_producto')->where('Id_variante', $id)->first();

        if (!$variante) {
            return response()->json(['message' => 'Variante no encontrada'], 404);
        }

        DB::table('variante_producto')->where('Id_variante', $id)->update([
            'Stock_Actual' => $datos['stock'],
        ]);

        return response()->json([
            'Id_variante'  => (int) $id,
            'Stock_Actual' => (int) $datos['stock'],
        ]);
    }

    private function reglas(bool $imagenObligatoria): array
    {
        return [
            'nombre'       => ['required', 'string', 'max:100'],
            'descripcion'  => ['nullable', 'string', 'max:2000'],
            'id_categoria' => ['required', 'integer', 'exists:categoria,Id_categoria'],
            'imagen'       => [$imagenObligatoria ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    private function mensajes(): array
    {
        return [
            'nombre.required'       => 'Escribí el nombre del producto.',
            'nombre.max'            => 'El nombre puede tener hasta 100 caracteres.',
            'descripcion.max'       => 'La descripción puede tener hasta 2000 caracteres.',
            'id_categoria.required' => 'Elegí una categoría.',
            'id_categoria.exists'   => 'La categoría elegida no existe.',
            'imagen.required'       => 'Elegí una imagen para el producto.',
            'imagen.image'          => 'El archivo tiene que ser una imagen.',
            'imagen.mimes'          => 'La imagen tiene que ser JPG, PNG o WEBP.',
            'imagen.max'            => 'La imagen puede pesar hasta 4 MB.',
        ];
    }

    private function leerVariantes(Request $request): array
    {
        $lista = json_decode((string) $request->input('variantes'), true);

        if (!is_array($lista) || count($lista) === 0) {
            throw ValidationException::withMessages(['variantes' => 'Agregá al menos una variante.']);
        }

        Validator::make(['v' => $lista], [
            'v.*.Talle'        => ['required', 'string', 'max:30'],
            'v.*.Color'        => ['required', 'string', 'max:30'],
            'v.*.Precio_Venta' => ['required', 'numeric', 'min:1', 'max:99999999'],
            'v.*.Stock_Actual' => ['required', 'integer', 'min:0', 'max:1000000'],
        ], [
            'v.*.Talle.required'        => 'Completá el talle de cada variante.',
            'v.*.Talle.max'             => 'El talle puede tener hasta 30 caracteres.',
            'v.*.Color.required'        => 'Completá el color de cada variante.',
            'v.*.Color.max'             => 'El color puede tener hasta 30 caracteres.',
            'v.*.Precio_Venta.required' => 'Completá el precio de cada variante.',
            'v.*.Precio_Venta.numeric'  => 'El precio tiene que ser un número.',
            'v.*.Precio_Venta.min'      => 'El precio tiene que ser mayor a cero.',
            'v.*.Stock_Actual.required' => 'Completá el stock de cada variante.',
            'v.*.Stock_Actual.integer'  => 'El stock tiene que ser un número entero.',
            'v.*.Stock_Actual.min'      => 'El stock no puede ser negativo.',
        ])->validate();

        return array_values($lista);
    }

    private function insertarVariante($idProducto, array $v): void
    {
        DB::table('variante_producto')->insert([
            'Id_producto'  => $idProducto,
            'SKU'          => $this->crearSku($idProducto, $v['Talle'], $v['Color']),
            'Talle'        => $v['Talle'],
            'Color'        => $v['Color'],
            'Precio_Venta' => $v['Precio_Venta'],
            'Stock_Actual' => $v['Stock_Actual'],
        ]);
    }

    private function crearSku($idProducto, string $talle, string $color): string
    {
        $base = 'KS-' . $idProducto . '-' . Str::upper(Str::slug($talle)) . '-' . Str::upper(Str::slug($color));
        $sku = $base;
        $n = 1;

        while (DB::table('variante_producto')->where('SKU', $sku)->exists()) {
            $n++;
            $sku = $base . '-' . $n;
        }

        return $sku;
    }

    private function guardarImagen($archivo, string $nombre): string
    {
        $carpeta = public_path('img');

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $extension = $archivo->guessExtension() ?: 'jpg';
        $archivoNombre = Str::slug($nombre) . '-' . time() . '.' . $extension;
        $archivo->move($carpeta, $archivoNombre);

        return 'img/' . $archivoNombre;
    }
}