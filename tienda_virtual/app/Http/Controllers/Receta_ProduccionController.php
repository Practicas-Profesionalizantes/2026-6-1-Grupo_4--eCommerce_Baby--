<?php

namespace App\Http\Controllers;

use App\Models\Receta_ProduccionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Receta_ProduccionController extends Controller{
    
    public function index()
    {
        
        $receta = DB::table('RECETA_PRODUCCION')->get();

        
        return response()->json($receta);
    }

    
    public function showReceta_Produccion($id)
    {
        
        $receta = Receta_ProduccionModel::findOrFail($id); 
        return response()->json($receta);
    }

    
    public function updateReceta_Produccion(Request $actualizacion, $id)
    {
        $receta = Receta_ProduccionModel::findOrFail($id);
        
        
        $receta->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $receta
        ]);
    }

    
    public function destroyReceta_Produccion($id)
    {
        $receta = Receta_ProduccionModel::findOrFail($id);
        $receta->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}