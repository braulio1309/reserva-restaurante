<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin users
        $this->call([
            AdminUserSeeder::class,
        ]);

        // Create sample clients
        \App\Models\Cliente::create([
            'nombre' => 'Juan Pérez',
            'email' => 'juan.perez@gmail.com',
            'telefono' => '+52 555 123 4567',
            'tipo' => 'vip',
            'notas' => 'Prefiere mesa ventana',
            'visitas' => 24,
            'ultima_visita' => now()->subDays(4),
        ]);

        \App\Models\Cliente::create([
            'nombre' => 'Ana López',
            'email' => 'ana.lopez@outlook.com',
            'telefono' => '+52 555 987 6543',
            'tipo' => 'nuevo',
            'visitas' => 1,
            'ultima_visita' => now()->subDay(),
        ]);

        \App\Models\Cliente::create([
            'nombre' => 'Carlos Ruiz',
            'email' => 'cruiz_88@gmail.com',
            'telefono' => '+52 555 111 2222',
            'tipo' => 'regular',
            'notas' => 'Alergia al maní',
            'visitas' => 12,
            'ultima_visita' => now()->subMonths(2),
        ]);

        // Create sample tables
        \App\Models\Mesa::create([
            'nombre' => 'Mesa 01',
            'zona' => 'Salón Principal',
            'capacidad' => 4,
            'estado' => 'ocupada',
        ]);

        \App\Models\Mesa::create([
            'nombre' => 'Mesa 02',
            'zona' => 'Salón Principal',
            'capacidad' => 2,
            'estado' => 'disponible',
        ]);

        \App\Models\Mesa::create([
            'nombre' => 'Terraza 01',
            'zona' => 'Terraza',
            'capacidad' => 6,
            'estado' => 'reservada',
        ]);

        \App\Models\Mesa::create([
            'nombre' => 'Mesa Bar 01',
            'zona' => 'Bar',
            'capacidad' => 2,
            'estado' => 'disponible',
        ]);

        // Create sample reservations
        \App\Models\Reserva::create([
            'cliente_id' => 1,
            'mesa_id' => 1,
            'nombre_cliente' => 'Juan Pérez',
            'email_cliente' => 'juan.perez@gmail.com',
            'telefono_cliente' => '+52 555 123 4567',
            'fecha' => now(),
            'hora' => '20:00',
            'num_personas' => 4,
            'estado' => 'pendiente',
            'notas' => 'Mesa Interior',
        ]);

        \App\Models\Reserva::create([
            'cliente_id' => 2,
            'mesa_id' => 3,
            'nombre_cliente' => 'María González',
            'email_cliente' => 'maria.g@email.com',
            'telefono_cliente' => '+52 555 999 8888',
            'fecha' => now(),
            'hora' => '19:30',
            'num_personas' => 2,
            'estado' => 'confirmada',
        ]);

        \App\Models\Reserva::create([
            'cliente_id' => 3,
            'nombre_cliente' => 'Carlos Rodriguez',
            'email_cliente' => 'carlos.rod@email.com',
            'telefono_cliente' => '+52 555 777 6666',
            'fecha' => now(),
            'hora' => '21:00',
            'num_personas' => 6,
            'estado' => 'confirmada',
        ]);

        \App\Models\Reserva::create([
            'nombre_cliente' => 'Lucía Fernández',
            'email_cliente' => 'lucia.fer@email.com',
            'telefono_cliente' => '+52 555 444 3333',
            'fecha' => now()->addDay(),
            'hora' => '14:00',
            'num_personas' => 3,
            'estado' => 'cancelada',
        ]);
    }
}
