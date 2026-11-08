<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolSeeder::class,
            MetodoPagoSeeder::class,
            MetodoEnvioSeeder::class,
            CategoriaSeeder::class,
            PromocionSeeder::class,
            ProveedorSeeder::class,
            UsuarioSeeder::class,
            ClienteSeeder::class,
            DireccionSeeder::class,
            ProductoSeeder::class,
            VarianteSeeder::class,
            ImagenProductoSeeder::class,
            InsumoSeeder::class,
            RecetaProduccionSeeder::class,
            CarritoSeeder::class,
            ItemCarritoSeeder::class,
            PedidoSeeder::class,
            DetallePedidoSeeder::class,
            TransaccionPagoSeeder::class,
            FacturaSeeder::class,
            EnvioSeeder::class,
            OrdenProduccionSeeder::class,
            MovimientoStockSeeder::class,
            BebeSeeder::class,
            FallaProduccionSeeder::class,
        ]);
    }
}