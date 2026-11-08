<?php

namespace App\Http\Controllers;

use App\Models\Orden_ProduccionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Orden_ProduccionController extends Controller{
    
   public function index()
    {
        
        $orden = DB::table('ORDEN_PRODUCCION')->get();

        
        return response()->json($orden);
    }


    
    public function showOrden_Produccion($id)
    {
        
        $orden = Orden_ProduccionModel::findOrFail($id); 
        return response()->json($orden);
    }

    
    public function updateOrden_Produccion(Request $actualizacion, $id)
    {
        $orden = Orden_ProduccionModel::findOrFail($id);
        
        
        $orden->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $orden
        ]);
    }

    
    public function destroyOrden_Produccion($id)
    {
        $orden = Orden_ProduccionModel::findOrFail($id);
        $orden->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}