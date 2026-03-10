<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Inscription;
use App\Models\Review;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    /**
     * Mostra o painel de gerenciamento de trabalhos para o Organizador.
     */
    public function index()
    {
        $eventIds = Auth::user()->events()->pluck('id');

        $workIds = Inscription::whereIn('event_id', $eventIds)
                              ->whereNotNull('work_id')
                              ->pluck('work_id');
        
        $works = Work::whereIn('id', $workIds)
                     ->with('user', 'workType', 'reviews.user')
                     ->get();

        $reviewers = User::where('user_type_id', 3)->get();

        return view('submissions.index', [
            'works' => $works,
            'reviewers' => $reviewers,
        ]);
    }

    /**
     * Atribui um trabalho a um avaliador.
     */
    public function assign(Request $request, Work $work)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $reviewerId = $request->user_id;
        $reviewer = User::find($reviewerId);
        
        if ($reviewer->user_type_id != 3) {
            return back()->with('error', 'Este usuário não é um Avaliador.');
        }

        $existingReview = Review::where('work_id', $work->id)
                                ->where('user_id', $reviewerId)
                                ->exists();

        if ($existingReview) {
            return back()->with('info', 'Este trabalho já foi atribuído a esse avaliador.');
        }

        Review::create([
            'work_id' => $work->id,
            'user_id' => $reviewerId,
        ]);

        return back()->with('success', 'Trabalho atribuído com sucesso!');
    }

    /**
     * RF_F11: Define a agenda de apresentação do trabalho.
     */
    public function schedule(Request $request, Work $work)
    {
        // Segurança: O organizador é dono do evento deste trabalho?
        $event = $work->inscription->event;
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'presentation_date' => 'required|date',
            'presentation_room' => 'required|string|max:255',
            'presentation_order' => 'required|integer|min:1',
        ]);

        $work->update($validated);

        // Notifica o autor do trabalho
        $work->user->notify(new \App\Notifications\WorkScheduledNotification($work));

        return back()->with('success', 'Agenda de apresentação atualizada e autor notificado!');
    }

    /**
     * RF_F12: Confirma que o autor realizou a apresentação do trabalho.
     */
    public function confirmPresentation(Work $work)
    {
        $inscription = $work->inscription;
        
        // Segurança: Apenas o organizador do evento pode confirmar
        if ($inscription->event->user_id !== Auth::id()) {
            abort(403);
        }

        $inscription->update(['presented_work' => true]);

        return back()->with('success', 'Apresentação confirmada com sucesso! O certificado foi liberado para o autor.');
    }
}