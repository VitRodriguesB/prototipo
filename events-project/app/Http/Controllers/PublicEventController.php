<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    /**
     * Exibe a página de detalhes pública de um evento. (RF-S2)
     */
    public function show(Event $event)
    {
        // Carregamos os tipos de inscrição e as atividades vinculadas a este evento
        $event->load(['inscriptionTypes', 'activities']);

        return view('events.public.show', [
            'event' => $event
        ]);
    }
}