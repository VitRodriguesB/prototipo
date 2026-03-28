<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Mostra o formulário de avaliação para o Avaliador.
     */
    public function edit(Review $review)
    {
        // 1. Segurança: Este trabalho é mesmo deste avaliador?
        if (Auth::id() !== $review->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // 2. Segurança: Este trabalho já foi avaliado?
        if ($review->status != 0) { // 0 = Pendente
             return redirect()->route('dashboard')->with('info', 'Este trabalho já foi avaliado.');
        }

        // 3. Carrega o trabalho (para mostrar o título, resumo, etc.)
        $review->load('work.user');

        return view('reviews.edit', [
            'review' => $review
        ]);
    }

    /**
     * Armazena a avaliação (Aprovado/Reprovado) no banco.
     */
    public function update(Request $request, Review $review)
    {
        // 1. Segurança: Este trabalho é mesmo deste avaliador?
        if (Auth::id() !== $review->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // 2. Validação dos dados do formulário
        $validatedData = $request->validate([
            'status' => 'required|in:1,2', // 1=Aprovado, 2=Reprovado
            'comments' => 'required|string|min:15',
        ]);

        // 3. Atualiza a avaliação
        $review->update($validatedData);

        // 4. Notificar o autor do trabalho (RF_S6)
        $author = $review->work->user;
        $author->notify(new \App\Notifications\WorkReviewedNotification($review));

        // 5. Redireciona de volta ao dashboard
        return redirect()->route('dashboard')->with('success', 'Avaliação enviada com sucesso!');
    }
}