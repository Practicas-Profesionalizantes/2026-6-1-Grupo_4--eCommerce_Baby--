<?php

namespace App\Http\Controllers;

use App\Models\Transaccion_PagoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Transaccion_PagoController extends Controller{
    
    public function index()
    {
        
        $transaccion = DB::table('TRANSACCION_PAGO')->get();

        
        return response()->json($transaccion);
    }

    
    public function showTransaccion_Pago($id)
    {
        
        $transaccion = Transaccion_PagoModel::findOrFail($id); 
        return response()->json($transaccion);
    }

    
    public function updateTransaccion_Pago(Request $actualizacion, $id)
    {
        $transaccion = Transaccion_PagoModel::findOrFail($id);
        
        
        $transaccion->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $transaccion
        ]);
    }

    
    public function destroyTransaccion_Pago($id)
    {
        $transaccion = Transaccion_PagoModel::findOrFail($id);
        $transaccion->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}