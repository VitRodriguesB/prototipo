<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function index()
    {
        $events = Event::where('registration_deadline', '>=', now())
                        ->orderBy('event_date', 'asc')
                        ->get();
        return view('welcome', compact('events'));
    }

    public function show(Event $event)
    {
        // Carrega os tipos de inscrição e atividades relacionadas para exibir na página do evento
        $event->load(['inscriptionTypes', 'activities' => function($query) {
            $query->orderBy('start_time', 'asc');
        }]);

        // A view correta está no subdiretório public conforme refatoração anterior
        return view('events.public.show', compact('event'));
    }
}
