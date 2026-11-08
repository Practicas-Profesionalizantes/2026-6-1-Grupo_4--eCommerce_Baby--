<?php
namespace App\Http\Controllers;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('Cliente', function (Blueprint $table) {
        $table->id('Id_cliente');
        $table->unsignedBigInteger('Id_usuario');
        $table->string('Nombre', 50);
        $table->string('Apellido', 50);
        $table->string('DNI_CUIT', 20)->unique();
        $table->string('Telefono', 20)->nullable();
        $table->enum('Condicion_IVA', ['Consumidor Final', 'Resp. Inscripto']);
        
        $table->foreign('Id_usuario')->references('Id_usuario')->on('Usuario');
    });

    Schema::create('Direccion', function (Blueprint $table) {
        $table->id('Id_direccion');
        $table->unsignedBigInteger('Id_cliente');
        $table->string('Calle_Numero', 100);
        $table->string('Ciudad', 50);
        $table->string('Provincia', 50);
        $table->string('Codigo_Postal', 20)->nullable();
        $table->enum('Tipo', ['Envio', 'Facturacion']);
        
        $table->foreign('Id_cliente')->references('Id_cliente')->on('Cliente');
    });
    }

    public function buscarCliente()
    {
        $cliente = Cliente::all();
        return response()->json($cliente);
    }

    
    public function showCliente($id)
    {
        
        $cliente = Cliente::findOrFail($id); 
        return response()->json($cliente);
    }

    
    public function updateCliente(Request $actualizacion, $id)
    {
        $cliente = Cliente::findOrFail($id);
        
        
        $cliente->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $cliente
        ]);
    }

    
    public function destroyCliente($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }


    public function buscarDireccion()
    {
        $direccion = Direccion::all();
        return response()->json($direccion);
    }

    
    public function showDireccion($id)
    {
        
        $direccion = Direccion::findOrFail($id); 
        return response()->json($direccion);
    }

    
    public function updateDireccion(Request $actualizacion, $id)
    {
        $direccion = Direcciion::findOrFail($id);
        
        
        $direccion->update($actualizacion->all()); 
        
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $direccion
        ]);
    }

    
    public function destroyDireccion($id)
    {
        $direccion = Direccion::findOrFail($id);
        $direccion->delete();
        
        return response()->json(['message' => 'Eliminado con éxito']);
    }    
};
