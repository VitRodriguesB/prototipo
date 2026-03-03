<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Notifications\PendingPaymentReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendPendingPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notifications:pending-payments';

    /**
     * The console command description.
     */
    protected $description = 'Envia lembretes para organizadores sobre comprovantes pendentes há mais de 24h - RF_S6';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cutoffTime = Carbon::now()->subHours(24);

        // Pagamentos pendentes (status = 1 = Em Análise) por mais de 24h
        $pendingPayments = Payment::where('status', 1)
            ->where('updated_at', '<', $cutoffTime)
            ->with('inscription.event.user', 'inscription.user')
            ->get();

        if ($pendingPayments->isEmpty()) {
            $this->info('Nenhum comprovante pendente há mais de 24 horas.');
            return Command::SUCCESS;
        }

        foreach ($pendingPayments as $payment) {
            $inscription = $payment->inscription;
            $organizer = $inscription->event->user;

            $organizer->notify(new PendingPaymentReminderNotification($inscription));
            $this->line("Lembrete enviado para organizador: {$organizer->email} (Evento: {$inscription->event->title})");
        }

        $this->info("Total de {$pendingPayments->count()} lembrete(s) enviado(s).");
        return Command::SUCCESS;
    }
}
