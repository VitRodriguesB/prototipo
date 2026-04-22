<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inicia a pesquisa mantendo a sua regra: apenas inscrições abertas
        $query = Event::where('registration_deadline', '>=', now());

        // 2. Se o utilizador digitou algo na barra de pesquisa...
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            
            // Agrupamos a pesquisa (título OU localização) para não quebrar a regra da data
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('location', 'like', '%' . $searchTerm . '%');
            });
        }

        // 3. Ordena pela data do evento (como já fazia) e vai buscar os resultados
        $events = $query->orderBy('event_date', 'asc')->get();

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