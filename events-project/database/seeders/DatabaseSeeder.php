<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserTypeSeeder::class,
            PaymentTypeSeeder::class,
            WorkTypeSeeder::class,
            EventSeeder::class,
        ]);

        // Usuário Participante padrão para testes
        User::factory()->create([
            'name' => 'Matheus Participante',
            'email' => 'participante@patio.com',
            'user_type_id' => 1,
            'email_verified_at' => now(),
        ]);
    }
}