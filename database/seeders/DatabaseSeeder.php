<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\Rol::create([
            'id' => 1,
            'name' => 'Administrador',
        ]);

        \App\Models\Rol::create([
            'id' => 2,
            'name' => 'Operador',
        ]);

        \App\Models\User::create([
            'id' => 1,
            'name' => 'Administrador',
            'email' => 'administrador@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'), // password
            'rol_id' => 1,
            'remember_token' => Str::random(10),
        ]);

        \App\Models\User::create([
            'id' => 2,
            'name' => 'Operador',
            'email' => 'operador@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'), // password
            'rol_id' => 2,
            'remember_token' => Str::random(10),
        ]);

    }
}
