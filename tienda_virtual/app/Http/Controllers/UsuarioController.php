<?php

namespace App\Http\Controllers;

use App\Models\UsuarioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller{
    
       public function index()
    {
        
        $usuarios = DB::table('Usuario')->get();

        
        return response()->json($usuarios);
    }

    
    public function showUsuario($id)
    {
        
        $usuario = UsuarioModel::findOrFail($id); 
        return response()->json($usuario);
    }

    
    public function updateUsuario(Request $actualizacion, $id)
    {
        $usuario = UsuarioModel::findOrFail($id);
        
        
        $usuario->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $usuario
        ]);
    }

    
    public function destroyUsuario($id)
    {
        $usuario = UsuarioModel::findOrFail($id);
        $usuario->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}