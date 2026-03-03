<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Inscription;
use App\Notifications\EventReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notifications:event-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Envia lembretes de eventos para participantes (7 dias e 1 dia antes) - RF_S6';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = Carbon::today();

        // Eventos em 7 dias
        $eventsIn7Days = Event::whereDate('event_date', $today->copy()->addDays(7))->get();
        $this->sendReminders($eventsIn7Days, 7);

        // Eventos em 1 dia (amanhã)
        $eventsIn1Day = Event::whereDate('event_date', $today->copy()->addDay())->get();
        $this->sendReminders($eventsIn1Day, 1);

        $this->info('Lembretes de eventos enviados com sucesso.');
        return Command::SUCCESS;
    }

    /**
     * Envia lembretes para todos os participantes confirmados de um conjunto de eventos.
     */
    protected function sendReminders($events, int $daysUntil): void
    {
        foreach ($events as $event) {
            // Busca apenas inscrições CONFIRMADAS (status = 1)
            $confirmedInscriptions = Inscription::where('event_id', $event->id)
                ->where('status', 1)
                ->with('user')
                ->get();

            foreach ($confirmedInscriptions as $inscription) {
                $inscription->user->notify(new EventReminderNotification($event, $daysUntil));
                $this->line("Lembrete ({$daysUntil} dias) enviado para: {$inscription->user->email}");
            }
        }
    }
}
