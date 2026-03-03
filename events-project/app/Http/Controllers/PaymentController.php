<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Notifications\NewPaymentProofNotification;
use App\Notifications\PaymentApprovedNotification;
use App\Notifications\PaymentRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Mostra o painel de validação de pagamentos para o Organizador. (RF-F3)
     */
    public function index()
    {
        // 1. Busca todos os pagamentos com status 'Em Análise' (payments.status = 1)
        // O with() garante que carregamos o usuário, evento e tipo de inscrição em uma só query.
        $pendingPayments = Payment::where('status', 1) 
            ->with('inscription.user', 'inscription.event', 'inscription.inscriptionType') 
            ->orderBy('created_at', 'asc')
            ->get();

        return view('organization.payments.index', [
            'pendingPayments' => $pendingPayments
        ]);
    }

    /**
     * Mostra o formulário de pagamento (Pix) e upload de comprovativo. (RF-F2 - Participante)
     */
    public function create(Inscription $inscription)
    {
        if (Auth::id() !== $inscription->user_id) {
            abort(403, 'Acesso não autorizado à inscrição.');
        }

        // Verifica se o pagamento já foi aprovado, recusado ou está em análise
        if ($inscription->payment && $inscription->payment->status == 1) {
             return redirect()->route('dashboard')->with('error', 'O pagamento já foi enviado e está em análise.');
        }
        if ($inscription->status == 1) { // 1 = Confirmada
            return redirect()->route('dashboard')->with('error', 'Inscrição já confirmada.');
        }

        return view('participant.payment', [
            'inscription' => $inscription
        ]);
    }

    /**
     * Processa o upload do comprovativo e registra o pagamento. (RF-F2 - Participante)
     */
    public function store(Request $request, Inscription $inscription)
    {
        if (Auth::id() !== $inscription->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate([
            'proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // 2MB máximo
        ]);

        // Se já existe um pagamento (que foi recusado), atualiza o registro existente
        if ($inscription->payment) {
             $payment = $inscription->payment;
             // Remove o arquivo antigo (para não acumular lixo)
             Storage::disk('public')->delete($payment->proof_path);
        } else {
             $payment = new Payment();
             $pixType = PaymentType::where('type', 'PIX')->first();
             $payment->payment_type_id = $pixType->id ?? 1;
             $payment->inscription_id = $inscription->id;
             
             // 👇 ESTA É A LINHA CORRIGIDA 👇
             // Ela pega o preço do TIPO DE INSCRIÇÃO, e não do EVENTO.
             $payment->amount = $inscription->inscriptionType->price;
        }

        // 3. Salvar o novo arquivo
        $proofPath = $request->file('proof')->store(
            'proofs/' . $inscription->event_id, 
            'public'
        );

        // 4. Atualizar o registro de pagamento (Em Análise)
        $payment->status = 1; 
        $payment->proof_path = $proofPath;
        $payment->rejection_reason = null; // Limpa a razão de recusa se houver
        $payment->save();

        // RF_S6: Notificar organizador sobre novo comprovante
        $organizer = $inscription->event->user;
        $organizer->notify(new NewPaymentProofNotification($inscription));

        // 5. Redirecionar
        return redirect()->route('dashboard')->with('success', 'Comprovante enviado com sucesso! Seu pagamento está agora EM ANÁLISE.');
    }

    /**
     * Aprova o pagamento. (RF-F3)
     */
    public function approve(Inscription $inscription)
    {
        // 1. Segurança: Garante que o organizador é o dono do evento
        // CORREÇÃO DE LÓGICA: Deve checar o user_id no EVENTO, não na inscrição
        if (Auth::id() !== $inscription->event->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // 2. Atualiza payment (Aprovado) e inscription (Confirmada)
        $inscription->payment->status = 2; // 2 = Aprovado (payments.status)
        $inscription->payment->save();

        $inscription->status = 1; // 1 = Confirmada (inscriptions.status)
        $inscription->save();
        
        // RF_S6: Notificar participante sobre aprovação
        $inscription->user->notify(new PaymentApprovedNotification($inscription->payment));
        
        return redirect()->route('organization.payments.index')->with('success', 'Pagamento aprovado. Participante notificado.');
    }

    /**
     * Recusa o pagamento. (RF-F3)
     */
    public function reject(Request $request, Inscription $inscription)
    {
        // 1. Segurança
        // CORREÇÃO DE LÓGICA: Deve checar o user_id no EVENTO, não na inscrição
        if (Auth::id() !== $inscription->event->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // Valida o motivo da recusa APENAS se estiver vindo do formulário de recusa
        if ($request->has('rejection_reason')) {
             $request->validate([
                 'rejection_reason' => 'required|string|min:10',
            ]);

             $rejectionReason = $request->rejection_reason;
        } else {
             // Se for um POST sem motivo (deve vir com motivo, mas como fallback)
             $rejectionReason = 'Motivo de recusa não informado.';
        }


        // 3. Atualiza payment e inscription
        $inscription->payment->status = 3; // 3 = Recusado (payments.status)
        $inscription->payment->rejection_reason = $rejectionReason;
        $inscription->payment->save();

        $inscription->status = 2; // 2 = Pagamento Recusado (inscriptions.status)
        $inscription->save();

        // RF_S6: Notificar participante sobre recusa
        $inscription->user->notify(new PaymentRejectedNotification($inscription->payment, $rejectionReason));

        return redirect()->route('organization.payments.index')->with('success', 'Pagamento recusado. Participante notificado com justificativa.');
    }

    /**
     * RF_F2: Faz download do comprovante de pagamento.
     */
    public function download(Payment $payment)
    {
        // Segurança: Apenas o organizador do evento pode baixar
        if (Auth::id() !== $payment->inscription->event->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // Verifica se o arquivo existe
        if (!Storage::disk('public')->exists($payment->proof_path)) {
            return back()->with('error', 'Arquivo não encontrado.');
        }

        // Gera um nome legível para o arquivo
        $participantName = str_replace(' ', '_', $payment->inscription->user->name);
        $extension = pathinfo($payment->proof_path, PATHINFO_EXTENSION);
        $filename = "comprovante_{$participantName}.{$extension}";

        return Storage::disk('public')->download($payment->proof_path, $filename);
    }
}