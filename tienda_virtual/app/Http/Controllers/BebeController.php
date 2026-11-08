<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;



use App\Http\Controllers\Controller; 


use App\Models\BebeModel; 

class BebeController extends Controller{
    
    public function index()
    {
        
        $bebes = DB::table('Bebe')->get();

        
        return response()->json($bebes);
    }

    
    public function showBebe($id)
    {
        
        $bebe = BebeModel::findOrFail($id); 
        return response()->json($bebe);
    }

    
    public function updateBebe(Request $actualizacion, $id)
    {
        $bebe = BebeModel::findOrFail($id);
        
        
        $bebe->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $bebe
        ]);
    }

    
    public function destroyBebe($id)
    {
        $bebe = BebeModel::findOrFail($id);
        $bebe->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}