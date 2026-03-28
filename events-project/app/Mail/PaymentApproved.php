<?php

namespace App\Mail;

use App\Models\Payment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentApproved extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;
    public string $qrCodeImage;

    /**
     * Create a new message instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->qrCodeImage = $this->generateQrCode();
    }

    /**
     * Gera o QR Code idêntico ao AttendanceController
     */
    protected function generateQrCode(): string
    {
        $inscription = $this->payment->inscription;
        
        // Lógica de token SHA256 idêntica ao sistema de presença
        $data = $inscription->id . '-' . $inscription->user_id . '-' . $inscription->event_id;
        $token = hash('sha256', $data . config('app.key'));
        
        $url = route('attendance.confirm', [
            'inscription' => $inscription->id,
            'token' => $token
        ]);

        $qrCode = new QrCode($url);
        $writer = new SvgWriter();
        $result = $writer->write($qrCode);

        return $result->getDataUri();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ingresso Confirmado: ' . $this->payment->inscription->event->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payment-approved',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
