<?php

namespace App\Http\Controllers;

use App\Models\EnvioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnvioController extends Controller{
    
    public function index()
    {
        
        $envios = DB::table('ENVIO')->get();

        
        return response()->json($envios);
    }

    
    public function showEnvio($id)
    {
        
        $envio = EnvioModel::findOrFail($id); 
        return response()->json($envio);
    }

    
    public function updateEnvio(Request $actualizacion, $id)
    {
        $envio = EnvioModel::findOrFail($id);
        
        
        $envio->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $envio
        ]);
    }

    
    public function destroyEnvio($id)
    {
        $envio = EnvioModel::findOrFail($id);
        $envio->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
}