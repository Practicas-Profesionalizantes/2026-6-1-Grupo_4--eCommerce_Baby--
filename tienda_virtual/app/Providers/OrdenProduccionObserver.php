<?php

namespace App\Observers;

use App\Models\Orden_ProduccionModel;
use App\Models\Movimiento_StockModel;
use App\Models\InsumoModel;
use App\Models\Variante_ProductoModel;
use Illuminate\Support\Facades\DB;
use Exception;

class OrdenProduccionObserver
{
    
    public function updated(Orden_ProduccionModel $orden): void
    {
        
        if ($orden->isDirty('Estado_Orden') && $orden->Estado_Orden === 'Finalizado') {
            
            
            DB::transaction(function () use ($orden) {
                
                
                $variante = Variante_ProductoModel::findOrFail($orden->Id_variante);
                $variante->increment('Stock_Actual', $orden->Cantidad_Producida_Real);

                
                Movimiento_StockModel::create([
                    'Id_variante'     => $orden->Id_variante,
                    'Id_insumo'       => null,
                    'Tipo_Movimiento' => 'Entrada',
                    'Cantidad'        => $orden->Cantidad_Producida_Real,
                    'Motivo'          => "Finalización automática de Orden de Producción #{$orden->Id_orden}",
                ]);

                
                $recetas = $variante->recetas; 

                foreach ($recetas as $receta) {
                   
                    $cantidadConsumida = $receta->Cantidad_Necesaria * $orden->Cantidad_Producida_Real;

                    
                    $insumo = InsumoModel::findOrFail($receta->Id_insumo);
                    
                    
                    if ($insumo->Stock_Disponible < $cantidadConsumida) {
                        throw new Exception("Stock insuficiente del insumo: {$insumo->Nombre}");
                    }

                    $insumo->decrement('Stock_Disponible', $cantidadConsumida);

                    
                    Movimiento_StockModel::create([
                        'Id_variante'     => null,
                        'Id_insumo'       => $receta->Id_insumo,
                        'Tipo_Movimiento' => 'Salida',
                        'Cantidad'        => $cantidadConsumida,
                        'Motivo'          => "Materiales consumidos por Orden de Producción #{$orden->Id_orden}",
                    ]);
                }
            });
        }
    }
}