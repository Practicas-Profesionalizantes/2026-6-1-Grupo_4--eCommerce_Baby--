<?php

namespace App\Http\Controllers;

use App\Models\Movimiento_StockModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Movimiento_StockController extends Controller{
    
   public function index()
    {
        
        $movimiento_stock = DB::table('MOVIMIENTO_STOCK')->get();

        
        return response()->json($movimiento_stock);
    }

    
    public function showMovimiento_Stock($id)
    {
        
        $movimiento = Movimiento_StockModel::findOrFail($id); 
        return response()->json($movimiento);
    }

    
    public function updateMovimiento_Stock(Request $actualizacion, $id)
    {
        $movimiento = Movimiento_StockModel::findOrFail($id);
        
        
        $movimiento->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $movimiento
        ]);
    }

    
    public function destroyMovimiento_Stock($id)
    {
        $movimiento = Movimiento_StockModel::findOrFail($id);
        $movimiento->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}