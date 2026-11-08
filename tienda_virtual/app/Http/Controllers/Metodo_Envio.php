<?php

namespace App\Http\Controllers;

use App\Models\Metodo_EnvioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Metodo_EnvioController extends Controller{
    
    public function index()
    {
        
        $metodos_envios = DB::table('METODO_ENVIO')->get();

        
        return response()->json($metodos_envios);
    }

    
    public function showMetodo_Envio($id)
    {
        
        $metodo_envio = Metodo_EnvioModel::findOrFail($id); 
        return response()->json($metodo_envio);
    }

    
    public function updateMetodo_Envio(Request $actualizacion, $id)
    {
        $metodo_envio = Metodo_EnvioModel::findOrFail($id);
        
        
        $metodo_envio->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $metodo_envio
        ]);
    }

    
    public function destroyMetodo_Envio($id)
    {
        $metodo_envio = Metodo_EnvioModel::findOrFail($id);
        $metodo_envio->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}