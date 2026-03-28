<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use Carbon\Carbon;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Gera o token de validação único para uma inscrição.
     */
    protected function generateToken(Inscription $inscription): string
    {
        $data = $inscription->id . '-' . $inscription->user_id . '-' . $inscription->event_id;
        return hash('sha256', $data . config('app.key'));
    }

    /**
     * Retorna a TELA do Ingresso Digital (HTML Premium Dark)
     */
    public function showTicket(Inscription $inscription)
    {
        // Segurança: Apenas o dono pode ver seu próprio ingresso
        abort_unless($inscription->user_id == auth()->id(), 403);

        // Apenas inscrições confirmadas
        if ($inscription->status !== 1) {
            abort(403, 'Ingresso disponível apenas para inscrições confirmadas.');
        }

        // Geração do QR Code em Base64 para seguir a Regra de Ouro (sem GD)
        $token = $this->generateToken($inscription);
        $url = route('attendance.validate', [
            'inscription' => $inscription->id,
            'token' => $token
        ]);
        
        $qrCode = new QrCode($url);
        $writer = new SvgWriter();
        $result = $writer->write($qrCode);
        $qrCodeUri = $result->getDataUri();

        return view('attendance.qrcode', compact('inscription', 'qrCodeUri'));
    }

    /**
     * Retorna apenas a IMAGEM PNG do QR Code (chamada pela tag <img> na view)
     */
    public function showQrCodeImage(Inscription $inscription)
    {
        if (Auth::id() !== $inscription->user_id) {
            abort(403, 'Acesso não autorizado.');
        }
        $token = $this->generateToken($inscription);
        
        $url = route('attendance.validate', [
            'inscription' => $inscription->id,
            'token' => $token
        ]);
        
        $qrCode = new QrCode($url);
        $writer = new SvgWriter();
        $result = $writer->write($qrCode);
        
        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    /**
     * Página de leitura de QR Code para o organizador (câmera).
     */
    public function scannerPage()
    {
        return view('organization.attendance.scanner');
    }

    /**
     * Valida o QR Code e confirma a presença.
     */
    public function validate(Request $request, Inscription $inscription, string $token)
    {
        // Segurança: Apenas o organizador do evento (ou admin) pode validar a presença
        abort_unless($inscription->event->user_id == auth()->id() || auth()->user()->user_type_id == 4, 403);

        // Verifica o token
        $expectedToken = $this->generateToken($inscription);
        if (!hash_equals($expectedToken, $token)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'QR Code inválido.'], 400);
            }
            return back()->with('error', 'QR Code inválido.');
        }

        // Verifica se o evento já passou
        $event = $inscription->event;
        if (Carbon::parse($event->event_date)->endOfDay()->isPast()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'QR Code expirado. O evento já encerrou.'], 400);
            }
            return back()->with('error', 'QR Code expirado. O evento já encerrou.');
        }

        // Verifica se já foi usado
        if ($inscription->attended) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Presença já confirmada anteriormente.',
                    'participant' => $inscription->user->name
                ], 400);
            }
            return back()->with('error', 'Presença já confirmada anteriormente para ' . $inscription->user->name);
        }

        // Confirma a presença
        $inscription->attended = true;
        $inscription->save();

        // Notifica o participante que o certificado está liberado
        $inscription->user->notify(new \App\Notifications\AttendanceConfirmedNotification($inscription));

        $participantName = $inscription->user->name;
        $eventTitle = $event->title;

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Presença confirmada com sucesso!",
                'participant' => $participantName,
                'event' => $eventTitle
            ]);
        }

        return redirect()->route('attendance.scanner')->with('success', "Presença confirmada para: {$participantName}");
    }

    /**
     * RF_F10: Reseta a presença de um participante.
     */
    public function resetAttendance(Inscription $inscription)
    {
        // Segurança: Apenas o organizador do evento pode resetar
        if ($inscription->event->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $inscription->attended = false;
        $inscription->save();

        return back()->with('success', 'Presença resetada com sucesso. O QR Code pode ser usado novamente.');
    }
}
