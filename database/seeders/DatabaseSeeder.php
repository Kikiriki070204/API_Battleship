<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Estado;
use App\Models\Estado_Resultado;
use Illuminate\Support\Facades\Hash;

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

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'kiarabarrientossalazar@gmail.com',
            'is_active' => true,
            'password' => Hash::make('1234567890')
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Nuevaaminuro',
            'email' => 'nuevaaminuro@gmail.com',
            'is_active' => true,
            'password' => Hash::make('1234567890')
        ]);

        $estados = [
            ['nombre' => 'Disponible'],
            ['nombre' => 'Ocupada'],
            ['nombre' => 'Finalizada'],
        ];

        foreach ($estados as $estado) {
            Estado::create($estado);
        }
    }
}
