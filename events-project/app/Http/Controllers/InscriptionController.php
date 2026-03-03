<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Inscription;
use App\Models\InscriptionType;
use App\Notifications\InscriptionConfirmedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InscriptionController extends Controller
{
    /**
     * Mostra o formulário de confirmação de inscrição. (RF-F1)
     */
    public function create(Event $event)
    {
        // 1. O usuário é visitante? (CORREÇÃO 1)
        if (!Auth::check()) {
            // Se não estiver logado, salva a URL que ele queria
            session(['url.intended' => route('inscriptions.create', $event)]);
            // E o manda para o login
            return redirect()->route('login')->with('info', 'Você precisa estar logado para se inscrever.');
        }

        // 2. O usuário já está inscrito?
        $user = Auth::user();
        $isAlreadyInscribed = Inscription::where('user_id', $user->id)
                                         ->where('event_id', $event->id)
                                         ->exists();

        if ($isAlreadyInscribed) {
            return redirect()->route('dashboard')->with('info', 'Você já está inscrito neste evento.');
        }

        // 3. A data de inscrição já passou?
        if ($event->registration_deadline < now()) {
            return redirect()->route('events.public.show', $event)->with('error', 'O prazo de inscrição para este evento já encerrou.');
        }

        // 4. Se passou por tudo, busque os tipos de inscrição e mostre o formulário
        $event->load('inscriptionTypes');

        return view('inscriptions.create', [
            'event' => $event,
            'inscriptionTypes' => $event->inscriptionTypes // Passa os tipos para a view
        ]);
    }

    /**
     * Armazena a nova inscrição no banco. (RF-F1)
     */
    public function store(Request $request, Event $event)
    {
        // 1. Validação
        $request->validate([
            'inscription_type_id' => 'required|integer|exists:inscription_types,id',
        ]);

        // 2. Verifica se o tipo de inscrição escolhido pertence mesmo a este evento
        // (Isso veio do seu controller, é uma boa verificação!)
        $inscriptionType = InscriptionType::find($request->inscription_type_id);
        if ($inscriptionType->event_id !== $event->id) {
            return back()->with('error', 'Tipo de inscrição inválido.');
        }

        // 3. Checagens de segurança (redundantes, mas boas)
        if ($event->registration_deadline < now()) {
            return back()->with('error', 'O prazo de inscrição encerrou enquanto você preenchia o formulário.');
        }

        $user = Auth::user();
        $isAlreadyInscribed = Inscription::where('user_id', $user->id)
                                         ->where('event_id', $event->id)
                                         ->exists();
        if ($isAlreadyInscribed) {
            return redirect()->route('dashboard')->with('info', 'Você já está inscrito neste evento.');
        }

        // 4. Cria a Inscrição
        $inscription = Inscription::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'inscription_type_id' => $request->inscription_type_id,
            'status' => 0, // 0 = Pendente
            'registration_code' => strtoupper(Str::random(8)), // Ex: A8B2K9L0
        ]);

        // RF_S6: Notificar participante sobre a inscrição
        $user->notify(new InscriptionConfirmedNotification($inscription));

        // 5. Redireciona para o próximo passo
        
        // Se o preço for R$ 0,00, confirma automaticamente
        if ($inscriptionType->price <= 0) {
            $inscription->status = 1; // 1 = Confirmada
            $inscription->save();
            return redirect()->route('dashboard')->with('success', 'Inscrição confirmada com sucesso!');
        }

        // Se for pago, manda para a tela de pagamento
        return redirect()->route('payment.create', $inscription);
    }
}