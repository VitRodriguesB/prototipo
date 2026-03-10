<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Inscription;
use App\Models\InscriptionType;
use App\Notifications\InscriptionConfirmedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InscriptionController extends Controller
{
    /**
     * Mostra o formulário de confirmação de inscrição.
     */
    public function create(Event $event)
    {
        if (!Auth::check()) {
            session(['url.intended' => route('inscriptions.create', $event)]);
            return redirect()->route('login')->with('info', 'Você precisa estar logado para se inscrever.');
        }

        $user = Auth::user();
        $isAlreadyInscribed = Inscription::where('user_id', $user->id)
                                         ->where('event_id', $event->id)
                                         ->exists();

        if ($isAlreadyInscribed) {
            return redirect()->route('dashboard')->with('info', 'Você já está inscrito neste evento.');
        }

        if ($event->registration_deadline < now()) {
            return redirect()->route('events.public.show', $event)->with('error', 'O prazo de inscrição para este evento já encerrou.');
        }

        $event->load('inscriptionTypes', 'activities');

        return view('inscriptions.create', [
            'event' => $event,
            'inscriptionTypes' => $event->inscriptionTypes
        ]);
    }

    /**
     * Armazena a nova inscrição e vincula às atividades (RF_F9).
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'inscription_type_id' => 'required|integer|exists:inscription_types,id',
            'activities' => 'nullable|array',
            'activities.*' => 'integer|exists:activities,id',
        ]);

        $inscriptionType = InscriptionType::find($request->inscription_type_id);
        if ($inscriptionType->event_id !== $event->id) {
            return back()->with('error', 'Tipo de inscrição inválido.');
        }

        if ($event->registration_deadline < now()) {
            return back()->with('error', 'O prazo de inscrição encerrou.');
        }

        $user = Auth::user();
        $isAlreadyInscribed = Inscription::where('user_id', $user->id)
                                         ->where('event_id', $event->id)
                                         ->exists();
        if ($isAlreadyInscribed) {
            return redirect()->route('dashboard')->with('info', 'Você já está inscrito neste evento.');
        }

        try {
            DB::beginTransaction();

            // 1. Cria a Inscrição Base
            $inscription = Inscription::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'inscription_type_id' => $request->inscription_type_id,
                'status' => 0,
                'registration_code' => strtoupper(Str::random(8)),
            ]);

            // 2. Processa Atividades (RF_F9)
            
            // 2a. Inscrição Automática (atividades sem limite de vagas)
            $automaticActivities = $event->activities()->whereNull('max_participants')->pluck('id');
            if ($automaticActivities->count() > 0) {
                $user->activities()->syncWithoutDetaching($automaticActivities);
            }

            // 2b. Inscrição Opcional (atividades selecionadas com vagas limitadas)
            if ($request->has('activities')) {
                foreach ($request->activities as $activityId) {
                    $activity = \App\Models\Activity::find($activityId);
                    
                    if ($activity && $activity->event_id === $event->id) {
                        $count = $activity->participants()->count();
                        if ($activity->max_participants && $count < $activity->max_participants) {
                            $user->activities()->attach($activityId);
                        }
                    }
                }
            }

            DB::commit();

            $user->notify(new InscriptionConfirmedNotification($inscription));

            if ($inscriptionType->price <= 0) {
                $inscription->status = 1;
                $inscription->save();
                return redirect()->route('dashboard')->with('success', 'Inscrição confirmada com sucesso!');
            }

            return redirect()->route('payment.create', $inscription);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao processar inscrição: ' . $e->getMessage());
        }
    }

    /**
     * RF_F4: Cancela uma inscrição existente.
     */
    public function destroy(Inscription $inscription)
    {
        if ($inscription->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        if ($inscription->status == 1) {
            return back()->with('error', 'Inscrições confirmadas não podem ser canceladas pelo painel.');
        }

        if ($inscription->payment && $inscription->payment->proof_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($inscription->payment->proof_path);
        }
        
        if ($inscription->work && $inscription->work->file_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($inscription->work->file_path);
        }

        $inscription->delete();

        return redirect()->route('dashboard')->with('success', 'Inscrição cancelada com sucesso.');
    }
}