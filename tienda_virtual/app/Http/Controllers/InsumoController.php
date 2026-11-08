<?php

namespace App\Http\Controllers;

use App\Models\InsumoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsumoController extends Controller{
    
    public function index()
    {
        
        $insumos = DB::table('INSUMO')->get();

        
        return response()->json($insumos);
    }

    
    public function showInsumo($id)
    {
        
        $insumo = InsumoModel::findOrFail($id); 
        return response()->json($insumo);
    }

    
    public function updateInsumo(Request $actualizacion, $id)
    {
        $insumo = InsumoModel::findOrFail($id);
        
        
        $insumo->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $insumo
        ]);
    }

    
    public function destroyInsumo($id)
    {
        $insumo = InsumoModel::findOrFail($id);
        $insumo->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}