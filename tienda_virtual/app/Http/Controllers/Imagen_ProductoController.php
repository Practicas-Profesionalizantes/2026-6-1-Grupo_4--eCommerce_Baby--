<?php

namespace App\Http\Controllers;

use App\Models\Imagen_ProductoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Imagen_ProductoController extends Controller{
    
    public function index()
    {
        
        $imagenes = DB::table('IMAGEN_PRODUCTO')->get();

        
        return response()->json($imagenes);
    }

    
    public function showImagen_Producto($id)
    {
        
        $imagen = Imagen_ProductoModel::findOrFail($id); 
        return response()->json($imagen);
    }

    
    public function updateImagen_Producto(Request $actualizacion, $id)
    {
        $imagen = Imagen_ProductoModel::findOrFail($id);
        
        
        $imagen->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $imagen
        ]);
    }

    
    public function destroyImagen_Producto($id)
    {
        $imagen = Imagen_ProductoModel::findOrFail($id);
        $imagen->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}