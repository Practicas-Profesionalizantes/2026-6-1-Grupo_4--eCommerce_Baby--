<?php

namespace App\Http\Controllers;

use App\Models\ProveedorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller{
    
    public function index()
    {
        
        $proveedores = DB::table('PROVEEDOR')->get();

        
        return response()->json($proveedores);
    }
    
    public function showProveedor($id)
    {
        
        $proveedor = ProveedorModel::findOrFail($id); 
        return response()->json($proveedor);
    }

    
    public function updateProveedor(Request $actualizacion, $id)
    {
        $proveedor = ProveedorModel::findOrFail($id);
        
        
        $proveedor->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $proveedor
        ]);
    }

    
    public function destroyProveedor($id)
    {
        $proveedor = ProveedorModel::findOrFail($id);
        $proveedor->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}