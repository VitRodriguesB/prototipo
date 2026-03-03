<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\InscriptionTypeController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AttendanceController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (para todos)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $events = Event::where('registration_deadline', '>=', now())
                    ->orderBy('event_date', 'asc')
                    ->get();
    return view('welcome', ['events' => $events]);
});

Route::get('/eventos/{event}', [PublicEventController::class, 'show'])->name('events.public.show');

// RF_F10: Rota pública para validação de QR Code (não requer auth do participante)
Route::get('/presenca/{inscription}/{token}', [AttendanceController::class, 'validate'])->name('attendance.validate');

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas (Login, Dashboard, Profile)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    
    $user = Auth::user();

    if ($user->user_type_id == 1) { // 1 = Participante
        $inscriptions = $user->inscriptions()
                            ->with('event', 'inscriptionType', 'payment', 'work') // Carrega o trabalho
                            ->orderBy('created_at', 'desc')
                            ->get();
        return view('dashboard', ['userInscriptions' => $inscriptions]);
    
    } elseif ($user->user_type_id == 2) { // 2 = Organizador
        return view('dashboard'); 

    } elseif ($user->user_type_id == 3) { // 3 = Avaliador
        // Carrega as avaliações PENDENTES (status 0)
        $pendingReviews = $user->reviews()
                              ->where('status', 0) // 0 = Pendente
                              ->with('work.user') // Carrega o trabalho e o autor
                              ->get();
        return view('dashboard', ['pendingReviews' => $pendingReviews]);
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // ESTA ERA A LINHA COM O ERRO DE DIGITAÇÃO (AGORA CORRIGIDA)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Rotas de GESTÃO (Organizador) e AÇÕES (Participante/Avaliador)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // ----- Rotas do Organizador (ID 2) -----
    Route::middleware(['organizer'])->group(function () {
        
        Route::resource('events', EventController::class);
        
        // Atividades (RF-F8)
        Route::get('/events/{event}/activities/create', [ActivityController::class, 'create'])->name('activities.create');
        Route::post('/events/{event}/activities', [ActivityController::class, 'store'])->name('activities.store');
        
        // Tipos de Inscrição (RF-B3)
        Route::get('/events/{event}/inscription-types/create', [InscriptionTypeController::class, 'create'])->name('inscription_types.create');
        Route::post('/events/{event}/inscription-types', [InscriptionTypeController::class, 'store'])->name('inscription_types.store');
        Route::get('/inscription-types/{inscriptionType}/edit', [InscriptionTypeController::class, 'edit'])->name('inscription_types.edit');
        Route::put('/inscription-types/{inscriptionType}', [InscriptionTypeController::class, 'update'])->name('inscription_types.update');
        Route::delete('/inscription-types/{inscriptionType}', [InscriptionTypeController::class, 'destroy'])->name('inscription_types.destroy');
        
        // Validação de Pagamento (RF-F3)
        Route::get('/organizacao/pagamentos', [PaymentController::class, 'index'])->name('organization.payments.index');
        Route::post('/organizacao/pagamentos/{inscription}/aprovar', [PaymentController::class, 'approve'])->name('organization.payments.approve');
        Route::post('/organizacao/pagamentos/{inscription}/recusar', [PaymentController::class, 'reject'])->name('organization.payments.reject');
        Route::get('/organizacao/pagamentos/{payment}/download', [PaymentController::class, 'download'])->name('organization.payments.download');

        // ROTAS DE GERENCIAR TRABALHOS (RF-F6)
        Route::get('/organizacao/trabalhos', [SubmissionController::class, 'index'])->name('submissions.index');
        Route::post('/organizacao/trabalhos/{work}/assign', [SubmissionController::class, 'assign'])->name('submissions.assign');
    });

    // ----- Rotas do Participante (ID 1) -----
    
    Route::get('/eventos/{event}/inscrever', [InscriptionController::class, 'create'])->name('inscriptions.create');
    Route::post('/eventos/{event}/inscrever', [InscriptionController::class, 'store'])->name('inscriptions.store');

    Route::get('/inscricoes/{inscription}/pagar', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/inscricoes/{inscription}/pagar', [PaymentController::class, 'store'])->name('payment.store');

    Route::get('/events/{event}/works/create', [WorkController::class, 'create'])->name('works.create');
    Route::post('/events/{event}/works', [WorkController::class, 'store'])->name('works.store');
    Route::get('/works/{work}/download', [WorkController::class, 'download'])->name('works.download');
    
    // ----- Rotas do Avaliador (ID 3) -----
    
    // ROTAS DE AVALIAÇÃO (RF-F12)
    Route::get('/avaliacoes/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::patch('/avaliacoes/{review}', [ReviewController::class, 'update'])->name('reviews.update');

    // RF_S7: Certificados em PDF
    Route::get('/certificados/{inscription}/participacao', [CertificateController::class, 'participation'])->name('certificates.participation');
    Route::get('/certificados/trabalho/{work}', [CertificateController::class, 'presentation'])->name('certificates.presentation');

    // RF_F10: Controle de Presença via QR Code
    Route::get('/presenca/scanner', [AttendanceController::class, 'scannerPage'])->name('attendance.scanner');
    Route::get('/presenca/qrcode/{inscription}', [AttendanceController::class, 'showQrCode'])->name('attendance.qrcode');
});

require __DIR__.'/auth.php';