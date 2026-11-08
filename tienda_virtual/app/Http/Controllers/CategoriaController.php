<?php

namespace App\Http\Controllers;

use App\Models\CategoriaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller{
    
    public function index()
    {
        
        $categorias = DB::table('Categoria')->get();

        
        return response()->json($categorias);
    }

    
    public function showCategoria($id)
    {
        
        $categoria = CategoriaModel::findOrFail($id); 
        return response()->json($categoria);
    }

    
    public function updateCategoria(Request $actualizacion, $id)
    {
        $categoria = CategoriaModel::findOrFail($id);
        
        
        $categoria->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $categoria
        ]);
    }

    
    public function destroyCategoria($id)
    {
        $categoria = CategoriaModel::findOrFail($id);
        $categoria->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}