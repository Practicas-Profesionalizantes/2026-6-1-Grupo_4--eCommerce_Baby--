<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaccionPagoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('TRANSACCION_PAGO')->insert([
            ['Id_transaccion' => 1, 'Id_pedido' => 1, 'Id_metodo_pago' => 1, 'Monto' => 58700.00, 'Estado_Pago' => 'Aprobado', 'Referencia_Pasarela' => 'MP-12345678', 'Fecha_Pago' => now()->subDays(3)],
            ['Id_transaccion' => 2, 'Id_pedido' =>