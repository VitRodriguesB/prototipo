<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Agendamento de Tarefas (RF_B1)
|--------------------------------------------------------------------------
|
| Remove usuários que não confirmaram o e-mail dentro de 15 minutos.
| Para funcionar, é necessário configurar o cron do servidor:
| * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
|
*/
Schedule::command('users:cleanup-unconfirmed')->everyMinute();

/*
|--------------------------------------------------------------------------
| Agendamento de Notificações (RF_S6)
|--------------------------------------------------------------------------
|
| Envia lembretes automáticos para participantes e organizadores.
|
*/

// Lembrete de eventos (7 dias e 1 dia antes) - Executa diariamente às 08:00
Schedule::command('notifications:event-reminders')->dailyAt('08:00');

// Lembrete de comprovantes pendentes (24h+) - Executa diariamente às 09:00
Schedule::command('notifications:pending-payments')->dailyAt('09:00');
