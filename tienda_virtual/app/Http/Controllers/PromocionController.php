<?php

namespace App\Http\Controllers;

use App\Models\PromocionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromocionController extends Controller{
    
    public function index()
    {
        
        $promociones = DB::table('Promocion')->get();

        
        return response()->json($promociones);
    }

    
    public function showPromocion($id)
    {
        
        $promocion = PromocionModel::findOrFail($id); 
        return response()->json($promocion);
    }

    
    public function updatePromocion(Request $actualizacion, $id)
    {
        $promocion = PromocionModel::findOrFail($id);
        
        
        $promocion->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $promocion
        ]);
    }

    
    public function destroyPromocion($id)
    {
        $promocion = PromocionModel::findOrFail($id);
        $promocion->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}