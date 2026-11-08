<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoModel extends Model
{
    // Define el nombre de tu tabla en la base de datos
    public $table = 'PEDIDO'; 
    public $fillable = ['Fecha_Pedido', 'Subtotal', 'Descuento_Aplicado', 'Total_Final' ,'Estado_Pedido','Id_cliente','Id_direccion','Id_metodo_envio'];
    protected $primaryKey = 'Id_pedido';
    public $timestamps = false;
}