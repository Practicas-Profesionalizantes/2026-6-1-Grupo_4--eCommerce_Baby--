<?php

namespace App\Http\Controllers;

use App\Models\CarritoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarritoController extends Controller{
    
    public function index()
    {
        
        $carritos = DB::table('CARRITO')->get();

        
        return response()->json($carritos);
    }


    
    public function showCarrito($id)
    {
        
        $crrito = CarritoModel::findOrFail($id); 
        return response()->json($carrito);
    }

    
    public function updateCarrito(Request $actualizacion, $id)
    {
        $carrito = CarritoModel::findOrFail($id);
        
        
        $carrito->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $carrito
        ]);
    }

    
    public function destroyCarrito($id)
    {
        $carrito = CarritoModel::findOrFail($id);
        $carrito->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}