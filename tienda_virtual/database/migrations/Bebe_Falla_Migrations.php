<?php

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
    
    Schema::create('Bebe', function (Blueprint $table) {
        $table->id('Id_bebe');
        $table->unsignedBigInteger('Id_cliente');
        $table->string('Nombre', 50);
        $table->date('Fecha_Nacimiento');
        $table->string('Genero', 20)->nullable();
        
        //$table->foreign('Id_cliente')->references('Id_cliente')->on('Cliente');
    });

    
    Schema::create('FALLA_PRODUCCION', function (Blueprint $table) {
        $table->id('Id_falla');
        $table->unsignedBigInteger('Id_orden');
        $table->integer('Cantidad_Fallada');
        $table->string('Motivo_Falla', 255)->nullable();
        $table->decimal('Costo_Perdido', 10, 2)->nullable();
        
        $table->foreign('Id_orden')->references('Id_orden')->on('ORDEN_PRODUCCION');
    });

    }

    public function upBebe(): void
    {
        Schema::create('Bebe', function (Blueprint $table) {
            $table->id(); // Crea la clave primaria autoincremental (id)
            
            // Tus columnas personalizadas
            $table->string('Nombre', 50);       // Campo de texto para el nombre
            $table->date('Fecha_Nacimiento');    // Campo de texto largo para la descripción
            $table->decimal('Genero', 20)->nullable(); // Campo numérico para precios (ej: 999999.99)
            
            $table->timestamps(); // Crea automáticamente 'created_at' y 'updated_at'
        });
    }

    /**
     * Revierte las migraciones (Elimina la tabla).
     */
    public function downBebe(): void
    {
        Schema::dropIfExists('Bebe');
    }

        public function upFalla(): void
    {
        Schema::create('FALLA_PRODUCCION', function (Blueprint $table) {
            $table->id(); // Crea la clave primaria autoincremental (id)
            
            // Tus columnas personalizadas
            $table->integer('Cantidad_Fallada');    // Campo de texto para el nombre
            $table->string('Motivo_Falla', 255)->nullable();  // Campo de texto largo para la descripción
            $table->decimal('Costo_Perdido', 10, 2)->nullable(); // Campo numérico para precios (ej: 999999.99)
            
            $table->timestamps(); // Crea automáticamente 'created_at' y 'updated_at'
        });
    }

    /**
     * Revierte las migraciones (Elimina la tabla).
     */
    public function downFalla(): void
    {
        Schema::dropIfExists('FALLA_PRODUCCION');
    }

};