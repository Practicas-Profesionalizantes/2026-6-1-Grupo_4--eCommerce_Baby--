<?php
namespace App\Http\Controllers;

use App\Models\Falla_ProduccionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Falla_ProduccionController extends Controller{

    public function index()
    {
        
        $fallas = DB::table('FALLA_PRODUCCION')->get();

        
        return response()->json($fallas);
    }


    
    public function showFalla($id)
    {
        
        $falla = Falla_ProduccionModel::findOrFail($id); 
        return response()->json($falla);
    }

    
    public function updateFalla(Request $actualizacion, $id)
    {
        $falla = Falla_ProduccionModel::findOrFail($id);
        
        
        $falla->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $falla
        ]);
    }

    
    public function destroyFalla($id)
    {
        $falla = Falla_ProduccionModel::findOrFail($id);
        $falla->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}