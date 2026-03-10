<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Exporta a lista de inscritos de um evento para CSV (RF_S4).
     */
    public function exportInscriptions(Event $event): StreamedResponse
    {
        // 1. Segurança: Apenas o organizador do evento pode exportar
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $inscriptions = Inscription::where('event_id', $event->id)
            ->with(['user', 'inscriptionType', 'payment'])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="inscritos_' . str_replace(' ', '_', $event->title) . '.csv"',
        ];

        $callback = function () use ($inscriptions) {
            $file = fopen('php://output', 'w');
            
            // Adiciona BOM para o Excel abrir com acentuação correta no Windows
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Cabeçalho do CSV
            fputcsv($file, [
                'ID Inscrição',
                'Nome do Participante',
                'E-mail',
                'Modalidade',
                'Valor (R$)',
                'Status Inscrição',
                'Status Pagamento',
                'Presença (Check-in)',
                'Data da Inscrição'
            ], ';');

            foreach ($inscriptions as $ins) {
                // Tradução dos status para o humano ler no Excel
                $statusInsc = $ins->status == 1 ? 'Confirmada' : 'Pendente';
                
                $statusPag = 'N/A';
                if ($ins->payment) {
                    if ($ins->payment->status == 1) $statusPag = 'Em Análise';
                    elseif ($ins->payment->status == 2) $statusPag = 'Aprovado';
                    elseif ($ins->payment->status == 3) $statusPag = 'Recusado';
                }

                fputcsv($file, [
                    $ins->registration_code,
                    $ins->user->name,
                    $ins->user->email,
                    $ins->inscriptionType->type,
                    number_format($ins->inscriptionType->price, 2, ',', ''),
                    $statusInsc,
                    $statusPag,
                    $ins->attended ? 'Sim' : 'Não',
                    $ins->created_at->format('d/m/Y H:i')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}