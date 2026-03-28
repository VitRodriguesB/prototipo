<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Work;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    /**
     * RF_S3: Gera o comprovante de inscrição em PDF.
     */
    public function registrationProof(Inscription $inscription)
    {
        // Segurança: Apenas o dono da inscrição pode baixar
        if ($inscription->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $inscription->load(['user', 'event', 'inscriptionType']);

        // Gera o PDF (em modo retrato/portrait)
        $pdf = Pdf::loadView('inscriptions.proof_pdf', [
            'inscription' => $inscription,
            'event' => $inscription->event,
            'user' => $inscription->user
        ])->setPaper('a4', 'portrait');

        $filename = 'comprovante_inscricao_' . $inscription->registration_code . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * RF_S7: Exibe o certificado de participação para impressão no navegador.
     */
    public function participation(Inscription $inscription)
    {
        if (Auth::id() !== $inscription->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        if (!$inscription->attended) {
            return back()->with('error', 'Certificado não disponível. Sua presença ainda não foi confirmada.');
        }

        $authCode = $this->generateAuthCode($inscription, 'participation');

        return view('certificates.participation', [
            'type' => 'participation',
            'user' => $inscription->user,
            'event' => $inscription->event,
            'inscription' => $inscription,
            'authCode' => $authCode,
        ]);
    }

    /**
     * RF_S7: Exibe o certificado de apresentação de trabalho para impressão no navegador.
     */
    public function presentation(Work $work)
    {
        if (Auth::id() !== $work->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $inscription = Inscription::where('work_id', $work->id)->first();

        if (!$inscription) {
            return back()->with('error', 'Inscrição não encontrada para este trabalho.');
        }

        if (!$inscription->presented_work) {
            return back()->with('error', 'Certificado não disponível. A apresentação ainda não foi confirmada pelo organizador.');
        }

        $authCode = $this->generateAuthCode($inscription, 'presentation', $work->id);

        return view('certificates.participation', [
            'type' => 'presentation',
            'user' => $work->user,
            'event' => $inscription->event,
            'inscription' => $inscription,
            'work' => $work,
            'authCode' => $authCode,
        ]);
    }

    /**
     * Gera um código de autenticação único para o certificado.
     */
    protected function generateAuthCode(Inscription $inscription, string $type, ?int $workId = null): string
    {
        $baseString = $inscription->id . '-' . $type . '-' . $inscription->user_id . '-' . ($workId ?? 0);
        $hash = hash('sha256', $baseString . config('app.key'));
        return strtoupper(substr($hash, 0, 16));
    }
}