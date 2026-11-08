<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClienteModel;

class CuentaController extends Controller
{
    // Guarda los datos del formulario de Mi Cuenta
    public function actualizar(Request $request)
    {
        // Revisa que lo escrito sea válido (si no, avisa en rojo)
        $datos = $request->validate([
            'nombre'   => 'required|string|max:50',
            'apellido' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:20',
        ]);

        // Busca tu perfil; si por algún motivo no existe, lo crea
        $cliente = ClienteModel::firstOrNew(['Id_usuario' => Auth::user()->Id_usuario]);

        if (!$cliente->exists) {
            $cliente->DNI_CUIT = '00000000';
            $cliente->Condicion_IVA = 'Consumidor Final';
        }

        $cliente->Nombre   = $datos['nombre'];
        $cliente->Apellido = $datos['apellido'] ?? '';
        $cliente->Telefono = $datos['telefono'] ?? '00000000';
        $cliente->save();

        return redirect()->route('cuenta.index')->with('success', '¡Datos actualizados con éxito!');
    }
}