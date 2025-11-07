<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Ejecutar seeders en el orden correcto (por dependencias)
        $this->call([
            // Datos básicos (sin dependencias)
            MonedasSeeder::class,
            TipoComprobantesSeeder::class,
            UbigeosSeeder::class,
            RolesSeeder::class,
            ParametrosSeeder::class,
            
            // Datos de catálogos
            CategoriasSeeder::class,
            MarcasSeeder::class,
            
            // Datos de negocio (con dependencias)
            ProveedoresSeeder::class,
            ClientesSeeder::class,
            UsuariosSeeder::class,
            
            // Productos (depende de categorías, marcas y proveedores)
            ProductosSeeder::class,
            
            // Transacciones (depende de clientes, usuarios y productos)
            VentasSeeder::class,
            // ComprasSeeder::class,
        ]);
    }
}
