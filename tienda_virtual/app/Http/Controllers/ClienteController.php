<?php

namespace App\Http\Controllers;

use App\Models\ClienteModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    private const CONDICIONES_IVA = ['Consumidor Final', 'Resp. Inscripto'];

    private function verificarPropio(Request $request, $id)
    {
        $cliente = $request->user() ? $request->user()->cliente : null;
        abort_unless($cliente && (int) $cliente->Id_cliente === (int) $id, 403);
    }

    public function index()
    {
        $clientes = DB::table('Cliente')->get();

        return response()->json($clientes);
    }

    public function showCliente(Request $request, $id)
    {
        $this->verificarPropio($request, $id);

        $cliente = ClienteModel::findOrFail($id);

        return response()->json($cliente);
    }

    public function updateCliente(Request $request, $id)
    {
        $this->verificarPropio($request, $id);

        $reglas = [
            'Nombre'        => ['required', 'string', 'max:50', 'regex:/^[\pL\s\'.\-]+$/u'],
            'Apellido'      => ['required', 'string', 'max:50', 'regex:/^[\pL\s\'.\-]+$/u'],
            'Telefono'      => [
                'required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/',
                function ($attribute, $value, $fail) {
                    $digitos = preg_replace('/\D/', '', $value);
                    if (strlen($digitos) < 8 || strlen($digitos) > 15) {
                        $fail('El teléfono tiene que tener entre 8 y 15 números.');
                    }
                },
            ],
            'DNI_CUIT'      => [
                'required', 'string',
                function ($attribute, $value, $fail) use ($id) {
                    $digitos = preg_replace('/\D/', '', $value);
                    if (!preg_match('/^[0-9.\-\s]+$/', $value)) {
                        $fail('El DNI o CUIT solo puede tener números.');
                    } elseif (!in_array(strlen($digitos), [7, 8, 11], true)) {
                        $fail('El DNI tiene 7 u 8 números, o el CUIT tiene 11.');
                    } elseif (DB::table('cliente')->where('DNI_CUIT', $digitos)->where('Id_cliente', '!=', $id)->exists()) {
                        $fail('Ese DNI o CUIT ya está registrado en otra cuenta.');
                    }
                },
            ],
            'Condicion_IVA' => ['required', Rule::in(self::CONDICIONES_IVA)],
        ];

        $mensajes = [
            'required'          => 'Completá el campo :attribute.',
            'max'               => 'El campo :attribute puede tener hasta :max caracteres.',
            'regex'             => 'El campo :attribute no tiene un formato válido.',
            'string'            => 'El campo :attribute no es válido.',
            'Condicion_IVA.in'  => 'Elegí una condición frente al IVA de la lista.',
        ];

        $atributos = [
            'Nombre'        => 'nombre',
            'Apellido'      => 'apellido',
            'Telefono'      => 'teléfono',
            'DNI_CUIT'      => 'DNI o CUIT',
            'Condicion_IVA' => 'condición frente al IVA',
        ];

        $datos = $request->validate($reglas, $mensajes, $atributos);

        $datos['DNI_CUIT'] = preg_replace('/\D/', '', $datos['DNI_CUIT']);

        $cliente = ClienteModel::findOrFail($id);
        $cliente->update($datos);

        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $cliente
        ]);
    }

    public function destroyCliente($id)
    {
        $cliente = ClienteModel::findOrFail($id);
        $cliente->delete();

        return response()->json(['message' => 'Eliminado con éxito']);
    }
}