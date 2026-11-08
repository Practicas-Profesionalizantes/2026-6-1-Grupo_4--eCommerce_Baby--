<?php

namespace App\Http\Controllers;

use App\Models\RolModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolController extends Controller{
    
    public function index()
    {
        
        $rol = DB::table('Rol')->get();

        
        return response()->json($rol);
    }

    
    public function showRol($id)
    {
        
        $rol = RolModel::findOrFail($id); 
        return response()->json($rol);
    }

    
    public function updateRol(Request $actualizacion, $id)
    {
        $rol = RolModel::findOrFail($id);
        
        
        $rol->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $rol
        ]);
    }

    
    public function destroyRol($id)
    {
        $rol = RolModel::findOrFail($id);
        $rol->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}