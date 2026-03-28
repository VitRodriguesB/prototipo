<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Simpósio Internacional de Inteligência Artificial',
            'Congresso Nacional de Engenharia de Software',
            'Hackathon Web3 & Blockchain 2026',
            'Semana Acadêmica de Ciência da Computação',
            'Fórum Global de Segurança Cibernética',
            'Workshop Prático de Laravel & Modern PHP',
            'Encontro de Pesquisa em Visão Computacional',
            'Cúpula de Tecnologia Sustentável',
            'Maratona de Programação Interfatec',
            'Simpósio de Data Science e Big Data',
            'Conferência sobre Ética na IA',
            'Jornada de Inovação e Empreendedorismo Tech',
            'Expo Tecnologia e Robótica Aplicada',
            'Seminário de Infraestrutura Cloud & DevOps',
            'Fórum de Desenvolvimento Mobile Multiplataforma'
        ];

        $locations = [
            'Auditório Principal - Campus Central',
            'Centro de Convenções Tecnológicas',
            'Laboratório de Inovação (Hub Digital)',
            'Online via Microsoft Teams',
            'Online via Zoom Meetings',
            'Espaço Maker - Bloco B',
            'Teatro Municipal de Tecnologia',
            'Sala de Conferências 404',
            'Metaverso PÁTIO (Ambiente Virtual)'
        ];

        $images = [
            'https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=1000',
            'https://images.unsplash.com/photo-1541701494587-cb58502866ab?q=80&w=1000',
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1000',
            'https://images.unsplash.com/photo-1514525253344-90206e4799c7?q=80&w=1000',
            'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=1000',
            'https://images.unsplash.com/photo-1519750783826-e2420f4d687f?q=80&w=1000',
            'https://images.unsplash.com/photo-1557683316-973673baf926?q=80&w=1000',
            'https://images.unsplash.com/photo-1605810230434-7631ac76ec81?q=80&w=1000',
            'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?q=80&w=1000',
            'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=1000',
            'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=1000',
            'https://images.unsplash.com/photo-1475721027187-402ad2989a3b?q=80&w=1000',
            'https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=1000',
            'https://images.unsplash.com/photo-1531050171654-776e72796b7c?q=80&w=1000',
            'https://images.unsplash.com/photo-1515187029135-18ee286d815b?q=80&w=1000'
        ];

        return [
            'user_id' => User::factory(), // Fallback caso não venha do seeder
            'title' => fake()->unique()->randomElement($titles),
            'description' => 'Bem-vindo ao evento! Este encontro visa reunir os maiores especialistas da área para discutir tendências, ferramentas e o futuro do setor. ' .
                             'A programação inclui palestras magnas, mesas redondas e sessões técnicas de apresentação de trabalhos. ' .
                             'Não perca a oportunidade de fazer networking e expandir seus conhecimentos acadêmicos e profissionais.',
            'location' => fake()->randomElement($locations),
            'event_date' => fake()->dateTimeBetween('+1 month', '+6 months'),
            'registration_deadline' => fake()->dateTimeBetween('now', '+1 month'),
            'registration_fee' => fake()->randomFloat(2, 10, 250),
            'max_participants' => fake()->optional()->numberBetween(50, 500),
            'pix_key' => fake()->email(),
            'cover_image_path' => fake()->randomElement($images),
        ];
    }

    /**
     * Estado para evento no passado (Expirado).
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_date' => fake()->dateTimeBetween('-3 months', '-1 month'),
            'registration_deadline' => fake()->dateTimeBetween('-4 months', '-3 months'),
        ]);
    }
}
