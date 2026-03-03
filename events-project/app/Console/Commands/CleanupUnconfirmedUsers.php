<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupUnconfirmedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:cleanup-unconfirmed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove usuários que não confirmaram o e-mail dentro do prazo de 15 minutos (RF_B1)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $expirationMinutes = config('auth.verification.expire', 15);
        $cutoffTime = Carbon::now()->subMinutes($expirationMinutes);

        // Busca usuários não confirmados criados antes do tempo limite
        $expiredUsers = User::whereNull('email_verified_at')
            ->where('created_at', '<', $cutoffTime)
            ->get();

        $count = $expiredUsers->count();

        if ($count === 0) {
            $this->info('Nenhum usuário expirado encontrado.');
            return Command::SUCCESS;
        }

        // Deleta os usuários expirados
        foreach ($expiredUsers as $user) {
            $this->line("Removendo usuário não confirmado: {$user->email}");
            $user->delete();
        }

        $this->info("Total de {$count} usuário(s) não confirmado(s) removido(s).");

        return Command::SUCCESS;
    }
}
