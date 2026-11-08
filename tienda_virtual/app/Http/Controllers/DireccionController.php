<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DireccionController extends Controller
{
    private const PROVINCIAS = [
        'Buenos Aires', 'Ciudad Autónoma de Buenos Aires', 'Catamarca', 'Chaco', 'Chubut',
        'Córdoba', 'Corrientes', 'Entre Ríos', 'Formosa', 'Jujuy', 'La Pampa', 'La Rioja',
        'Mendoza', 'Misiones', 'Neuquén', 'Río Negro', 'Salta', 'San Juan', 'San Luis',
        'Santa Cruz', 'Santa Fe', 'Santiago del Estero', 'Tierra del Fuego', 'Tucumán',
    ];

    private function verificarPropio(Request $request, $idCliente)
    {
        $cliente = $request->user() ? $request->user()->cliente : null;
        abort_unless($cliente && (int) $cliente->Id_cliente === (int) $idCliente, 403);
    }

    private function reglas()
    {
        return [
            'Calle'         => ['required', 'string', 'max:40'],
            'Numero'        => ['required', 'string', 'max:8'],
            'Departamento'  => ['nullable', 'string', 'max:8'],
            'Barrio'        => ['required', 'string', 'max:25'],
            'Ciudad'        => ['required', 'string', 'max:50'],
            'Provincia'     => ['required', Rule::in(self::PROVINCIAS)],
            'Codigo_Postal' => ['required', 'string', 'regex:/^([0-9]{4}|[A-Za-z][0-9]{4}[A-Za-z]{3})$/'],
        ];
    }

    private function mensajes()
    {
        return [
            'required'              => 'Completá el campo :attribute.',
            'max'                   => 'El campo :attribute es demasiado largo.',
            'string'                => 'El campo :attribute no es válido.',
            'Provincia.in'          => 'Elegí una provincia de la lista.',
            'Codigo_Postal.regex'   => 'El código postal no es válido: son 4 números, o el formato A1234BCD.',
        ];
    }

    private function atributos()
    {
        return [
            'Calle'         => 'calle',
            'Numero'        => 'número',
            'Departamento'  => 'departamento',
            'Barrio'        => 'barrio',
            'Ciudad'        => 'ciudad',
            'Provincia'     => 'provincia',
            'Codigo_Postal' => 'código postal',
        ];
    }

    private function armar(array $datos)
    {
        $datos['Departamento'] = !empty($datos['Departamento']) ? $datos['Departamento'] : null;

        $linea = $datos['Calle'] . ' ' . $datos['Numero'];
        if ($datos['Departamento'] !== null) {
            $linea .= ', Dpto ' . $datos['Departamento'];
        }
        $linea .= ', Barrio ' . $datos['Barrio'];

        $datos['Calle_Numero'] = mb_substr($linea, 0, 100);
        $datos['Codigo_Postal'] = strtoupper($datos['Codigo_Postal']);

        return $datos;
    }

    private function bloquearSiTienePedido($id)
    {
        abort_if(
            DB::table('pedido')->where('Id_direccion', $id)->exists(),
            409,
            'Esta dirección pertenece a un pedido y no se puede modificar'
        );
    }

    public function porCliente(Request $request, $idCliente)
    {
        $this->verificarPropio($request, $idCliente);

        $direccion = DB::table('direccion')
            ->where('Id_cliente', $idCliente)
            ->where('Tipo', 'Envio')
            ->orderByDesc('Id_direccion')
            ->first();

        return response()->json($direccion);
    }

    public function guardarPorCliente(Request $request, $idCliente)
    {
        $this->verificarPropio($request, $idCliente);

        $datos = $this->armar($request->validate($this->reglas(), $this->mensajes(), $this->atributos()));

        $actual = DB::table('direccion')
            ->where('Id_cliente', $idCliente)
            ->where('Tipo', 'Envio')
            ->orderByDesc('Id_direccion')
            ->first();

        if ($actual && !DB::table('pedido')->where('Id_direccion', $actual->Id_direccion)->exists()) {
            DB::table('direccion')->where('Id_direccion', $actual->Id_direccion)->update($datos);
            $idDireccion = $actual->Id_direccion;
        } else {
            $idDireccion = DB::table('direccion')->insertGetId(
                array_merge($datos, ['Id_cliente' => $idCliente, 'Tipo' => 'Envio'])
            );
        }

        return response()->json([
            'message' => 'Dirección guardada con éxito',
            'data' => DB::table('direccion')->where('Id_direccion', $idDireccion)->first()
        ]);
    }

    public function index()
    {
        return response()->json(DB::table('direccion')->get());
    }

    public function showDireccion($id)
    {
        $direccion = DB::table('direccion')->where('Id_direccion', $id)->first();
        abort_unless($direccion, 404);

        return response()->json($direccion);
    }

    public function updateDireccion(Request $actualizacion, $id)
    {
        abort_unless(DB::table('direccion')->where('Id_direccion', $id)->exists(), 404);
        $this->bloquearSiTienePedido($id);

        $datos = $this->armar($actualizacion->validate($this->reglas(), $this->mensajes(), $this->atributos()));

        DB::table('direccion')->where('Id_direccion', $id)->update($datos);

        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => DB::table('direccion')->where('Id_direccion', $id)->first()
        ]);
    }

    public function destroyDireccion($id)
    {
        abort_unless(DB::table('direccion')->where('Id_direccion', $id)->exists(), 404);
        $this->bloquearSiTienePedido($id);

        DB::table('direccion')->where('Id_direccion', $id)->delete();

        return response()->json(['message' => 'Eliminado con éxito']);
    }
}