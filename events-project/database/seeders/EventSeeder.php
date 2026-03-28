<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Busca o primeiro Organizador (user_type_id == 2)
        $organizer = User::where('user_type_id', 2)->first();

        // 2. Se não existir, cria o Organizador Master
        if (!$organizer) {
            $organizer = User::create([
                'name' => 'Organizador Master',
                'email' => 'organizador@patio.com',
                'password' => Hash::make('password'),
                'user_type_id' => 2, // ID fixo conforme regra de negócio
                'email_verified_at' => now(),
            ]);
        }

        // 3. Gera 13 eventos para o futuro
        Event::factory()
            ->count(13)
            ->create([
                'user_id' => $organizer->id
            ]);

        // 4. Gera 2 eventos no passado (Expirados)
        Event::factory()
            ->count(2)
            ->expired()
            ->create([
                'user_id' => $organizer->id
            ]);
            
        $this->command->info('15 Eventos diversificados (13 futuros, 2 expirados) foram gerados com sucesso!');
    }
}
