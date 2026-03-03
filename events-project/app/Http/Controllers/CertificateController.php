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
     * RF_S7: Gera certificado de participação em PDF.
     */
    public function participation(Inscription $inscription)
    {
        // Segurança: Apenas o dono da inscrição pode baixar
        if (Auth::id() !== $inscription->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // Verifica se a presença foi confirmada
        if (!$inscription->attended) {
            return back()->with('error', 'Certificado não disponível. Sua presença ainda não foi confirmada.');
        }

        // Gera código de autenticação único
        $authCode = $this->generateAuthCode($inscription, 'participation');

        // Dados para o certificado
        $data = [
            'type' => 'participation',
            'user' => $inscription->user,
            'event' => $inscription->event,
            'work' => null,
            'authCode' => $authCode,
        ];

        // Gera o PDF
        $pdf = Pdf::loadView('certificates.template', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'certificado_participacao_' . Str::slug($inscription->event->title) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * RF_S7: Gera certificado de apresentação de trabalho em PDF.
     */
    public function presentation(Work $work)
    {
        // Segurança: Apenas o autor pode baixar
        if (Auth::id() !== $work->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // Busca a inscrição relacionada ao trabalho
        $inscription = Inscription::where('work_id', $work->id)->first();

        if (!$inscription) {
            return back()->with('error', 'Inscrição não encontrada para este trabalho.');
        }

        // Verifica se a apresentação foi confirmada
        if (!$inscription->presented_work) {
            return back()->with('error', 'Certificado não disponível. A apresentação ainda não foi confirmada pelo organizador.');
        }

        // Gera código de autenticação único
        $authCode = $this->generateAuthCode($inscription, 'presentation', $work->id);

        // Dados para o certificado
        $data = [
            'type' => 'presentation',
            'user' => $work->user,
            'event' => $inscription->event,
            'work' => $work,
            'authCode' => $authCode,
        ];

        // Gera o PDF
        $pdf = Pdf::loadView('certificates.template', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'certificado_apresentacao_' . Str::slug($work->title) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Gera um código de autenticação único para o certificado.
     */
    protected function generateAuthCode(Inscription $inscription, string $type, ?int $workId = null): string
    {
        $baseString = $inscription->id . '-' . $type . '-' . $inscription->user_id . '-' . ($workId ?? 0);
        $hash = hash('sha256', $baseString . config('app.key'));
        
        // Retorna os primeiros 16 caracteres em maiúsculo
        return strtoupper(substr($hash, 0, 16));
    }
}
