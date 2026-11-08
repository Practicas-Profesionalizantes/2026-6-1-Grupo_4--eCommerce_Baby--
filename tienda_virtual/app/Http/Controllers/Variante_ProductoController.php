<?php

namespace App\Http\Controllers;

use App\Models\Variante_ProductoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Variante_ProductoController extends Controller{
    
    public function index()
    {
        
        $variantes = DB::table('VARIANTE_PRODUCTO')->get();

        
        return response()->json($variantes);
    }


    
    public function showVariante_Produccion($id)
    {
        
        $variante = Variante_ProductoModel::findOrFail($id); 
        return response()->json($variante);
    }

    
    public function updateVariante_Produccion(Request $actualizacion, $id)
    {
        $variante = Variante_ProductoModel::findOrFail($id);
        
        
        $variante->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $variante
        ]);
    }

    
    public function destroyVariante_Produccion($id)
    {
        $variante = Variante_ProductoModel::findOrFail($id);
        $variante->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}