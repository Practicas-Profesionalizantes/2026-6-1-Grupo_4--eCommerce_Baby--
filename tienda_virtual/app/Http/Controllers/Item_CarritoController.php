<?php

namespace App\Http\Controllers;

use App\Models\Item_CarritoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Item_CarritoController extends Controller{
    
    public function index()
    {
        
        $items = DB::table('ITEM_CARRITO')->get();

        
        return response()->json($items);
    }

    
    public function showItem_Carrito($id)
    {
        
        $item = Item_CarritoModel::findOrFail($id); 
        return response()->json($item);
    }

    
    public function updateItem_Carrito(Request $actualizacion, $id)
    {
        $item = Item_CarritoModel::findOrFail($id);
        
        
        $item->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $item
        ]);
    }

    
    public function destroyItem_Carrito($id)
    {
        $item = Item_CarritoModel::findOrFail($id);
        $item->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}