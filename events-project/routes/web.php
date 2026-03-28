<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\InscriptionTypeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CertificateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROTAS PÚBLICAS ---
Route::get('/', [PublicEventController::class, 'index'])->name('home');
Route::get('/eventos/{event}', [PublicEventController::class, 'show'])->name('events.public.show');

// --- ROTAS PROTEGIDAS (AUTH) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Unificado
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- GRUPO DO ORGANIZADOR ---
    Route::middleware(['organizer'])->group(function () {
        Route::resource('events', EventController::class);
        Route::get('/events/{event}/export', [ReportController::class, 'exportInscriptions'])->name('events.export');
        
        // Atividades
        Route::get('/events/{event}/activities/create', [ActivityController::class, 'create'])->name('activities.create');
        Route::post('/events/{event}/activities', [ActivityController::class, 'store'])->name('activities.store');
        Route::resource('activities', ActivityController::class)->only(['edit', 'update', 'destroy']);

        // Tipos de Inscrição
        Route::get('/events/{event}/inscription-types/create', [InscriptionTypeController::class, 'create'])->name('inscription_types.create');
        Route::post('/events/{event}/inscription-types', [InscriptionTypeController::class, 'store'])->name('inscription_types.store');
        Route::get('/inscription-types/{inscriptionType}/edit', [InscriptionTypeController::class, 'edit'])->name('inscription_types.edit');
        Route::put('/inscription-types/{inscriptionType}', [InscriptionTypeController::class, 'update'])->name('inscription_types.update');
        Route::delete('/inscription-types/{inscriptionType}', [InscriptionTypeController::class, 'destroy'])->name('inscription_types.destroy');

        // Gestão Financeira e Científica
        Route::get('/organizacao/pagamentos', [PaymentController::class, 'index'])->name('organization.payments.index');
        Route::post('/organizacao/pagamentos/{inscription}/aprovar', [PaymentController::class, 'approve'])->name('organization.payments.approve');
        Route::post('/organizacao/pagamentos/{inscription}/recusar', [PaymentController::class, 'reject'])->name('organization.payments.reject');
        Route::get('/organizacao/pagamentos/{payment}/download', [PaymentController::class, 'download'])->name('organization.payments.download');
        
        Route::get('/organizacao/trabalhos', [SubmissionController::class, 'index'])->name('submissions.index');
        Route::post('/organizacao/trabalhos/{work}/assign', [SubmissionController::class, 'assign'])->name('submissions.assign');
        Route::post('/organizacao/trabalhos/{work}/schedule', [SubmissionController::class, 'schedule'])->name('submissions.schedule');
        Route::post('/organizacao/trabalhos/{work}/confirm-presentation', [SubmissionController::class, 'confirmPresentation'])->name('submissions.confirm');

        // Presença (Scanner)
        Route::get('/presenca/scanner', [AttendanceController::class, 'scannerPage'])->name('attendance.scanner');
        Route::any('/presenca/{inscription}/{token}', [AttendanceController::class, 'validate'])->name('attendance.confirm');
        Route::post('/admin/inscritos/{inscription}/reset', [AttendanceController::class, 'resetAttendance'])->name('admin.inscriptions.reset');
    });

    // --- GRUPO DO PARTICIPANTE ---
    Route::get('/eventos/{event}/inscrever', [InscriptionController::class, 'create'])->name('inscriptions.create');
    Route::post('/eventos/{event}/inscrever', [InscriptionController::class, 'store'])->name('inscriptions.store');
    Route::delete('/inscricoes/{inscription}/cancelar', [InscriptionController::class, 'destroy'])->name('inscriptions.destroy');

    Route::get('/inscricoes/{inscription}/pagar', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/inscricoes/{inscription}/pagar', [PaymentController::class, 'store'])->name('payment.store');

    Route::get('/events/{event}/works/create', [WorkController::class, 'create'])->name('works.create');
    Route::post('/events/{event}/works', [WorkController::class, 'store'])->name('works.store');
    Route::get('/works/{work}/download', [WorkController::class, 'download'])->name('works.download');

    // Saída (Ingressos e Certificados)
    Route::get('/presenca/qrcode-image/{inscription}', [AttendanceController::class, 'showQrCodeImage'])->name('attendance.qrcode.image');
    Route::get('/inscricoes/{inscription}/comprovante', [CertificateController::class, 'registrationProof'])->name('inscriptions.proof');
    Route::get('/certificados/{inscription}/participacao', [CertificateController::class, 'participation'])->name('certificates.participation');
    Route::get('/certificados/trabalho/{work}', [CertificateController::class, 'presentation'])->name('certificates.presentation');

    // Rota Forçada de Ingresso (Consertada e Integrada)
    Route::get('/meu-ingresso/{id}', function ($id) {
        $inscription = \App\Models\Inscription::findOrFail($id);
        
        // Segurança: Apenas o dono pode ver seu próprio ingresso
        if (Auth::id() !== $inscription->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // Apenas inscrições confirmadas
        if ($inscription->status !== 1) {
            abort(403, 'Ingresso disponível apenas para inscrições confirmadas.');
        }

        // Lógica de Token (Sincronizada com AttendanceController)
        $data = $inscription->id . '-' . $inscription->user_id . '-' . $inscription->event_id;
        $token = hash('sha256', $data . config('app.key'));
        
        // QR Code aponta para a rota de validação (confirm)
        $url = route('attendance.confirm', [
            'inscription' => $inscription->id,
            'token' => $token
        ]);
        
        // Regra de Ouro: SvgWriter (Sem dependência de GD)
        $qrCode = new \Endroid\QrCode\QrCode($url);
        $writer = new \Endroid\QrCode\Writer\SvgWriter();
        $result = $writer->write($qrCode);
        $qrCodeUri = $result->getDataUri();
        
        return view('attendance.qrcode', compact('inscription', 'qrCodeUri'));
    })->name('ingresso.force.show');

    // --- GRUPO DO AVALIADOR ---
    Route::get('/avaliacoes/{review}/avaliar', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/avaliacoes/{review}', [ReviewController::class, 'update'])->name('reviews.update');

    // --- GRUPO DE ADMINISTRAÇÃO (RF_S1) ---
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::any('/usuarios', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/usuarios/{user}/role', [App\Http\Controllers\Admin\UserController::class, 'changeRole'])->name('users.update');
    });

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/debug-work/{id}', function($id) {
        $work = \App\Models\Work::find($id);
        
        if(!$work) {
            return "Trabalho não encontrado no banco.";
        }

        dd([
            '1. CAMINHO NO BANCO DE DADOS' => $work->file_path,
            '2. EXISTE NO DISCO LOCAL?' => \Illuminate\Support\Facades\Storage::disk('local')->exists((string) $work->file_path),
            '3. EXISTE NO DISCO PUBLIC?' => \Illuminate\Support\Facades\Storage::disk('public')->exists((string) $work->file_path),
            '4. ONDE O LARAVEL ESTA PROCURANDO (Local)' => storage_path('app/' . $work->file_path),
        ]);
    });

    
});

require __DIR__.'/auth.php';