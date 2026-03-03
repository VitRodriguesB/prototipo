<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use Carbon\Carbon;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
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
     * Retorna a imagem do QR Code para o participante.
     */
    public function showQrCode(Inscription $inscription)
    {
        // Segurança: Apenas o dono pode ver
        if (Auth::id() !== $inscription->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // Apenas inscrições confirmadas
        if ($inscription->status !== 1) {
            abort(403, 'QR Code disponível apenas para inscrições confirmadas.');
        }

        $token = $this->generateToken($inscription);
        $url = route('attendance.validate', [
            'inscription' => $inscription->id,
            'token' => $token
        ]);

        // Gera o QR Code
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->build();

        return response($result->getString(), 200, [
            'Content-Type' => 'image/png',
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
}
