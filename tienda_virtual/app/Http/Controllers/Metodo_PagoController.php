<?php

namespace App\Http\Controllers;

use App\Models\Metodo_PagoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Metodo_PagoController extends Controller{
    
    public function index()
    {
        
        $metodos_pagos = DB::table('METODO_PAGO')->get();

        
        return response()->json($metodos_pagos);
    }

    
    public function showMetodo_Pago($id)
    {
        
        $pago = Metodo_PagoModel::findOrFail($id); 
        return response()->json($pago);
    }

    
    public function updateMetodo_Pago(Request $actualizacion, $id)
    {
        $pago = Metodo_PagoModel::findOrFail($id);
        
        
        $pago->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $pago
        ]);
    }

    
    public function destroyMetodo_Pago($id)
    {
        $pago = Metodo_PagoModel::findOrFail($id);
        $pago->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}