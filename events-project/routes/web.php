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
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $events = Event::where('registration_deadline', '>=', now())
                    ->orderBy('event_date', 'asc')
                    ->get();
    return view('welcome', ['events' => $events]);
});

Route::get('/eventos/{event}', [PublicEventController::class, 'show'])->name('events.public.show');

// RF_F10: Validação de QR Code
Route::get('/presenca/{inscription}/{token}', [AttendanceController::class, 'validate'])->name('attendance.validate');

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas (Dashboard)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->user_type_id == 1) { // 1 = Participante
        $inscriptions = $user->inscriptions()
                            ->with(['event', 'inscriptionType', 'payment', 'work.reviews', 'user.activities'])
                            ->orderBy('created_at', 'desc')
                            ->get();
        return view('dashboard', ['userInscriptions' => $inscriptions]);
    
    } elseif ($user->user_type_id == 2) { // 2 = Organizador
        // RF_S4: Estatísticas Reais do Organizador
        $myEventsIds = $user->events()->pluck('id');
        $stats = [
            'total_inscriptions' => \App\Models\Inscription::whereIn('event_id', $myEventsIds)->count(),
            'confirmed_payments' => \App\Models\Inscription::whereIn('event_id', $myEventsIds)->where('status', 1)->count(),
            'pending_payments' => \App\Models\Payment::whereIn('inscription_id', function($query) use ($myEventsIds) {
                $query->select('id')->from('inscriptions')->whereIn('event_id', $myEventsIds);
            })->where('status', 1)->count(),
            'total_revenue' => \App\Models\Payment::whereIn('inscription_id', function($query) use ($myEventsIds) {
                $query->select('id')->from('inscriptions')->whereIn('event_id', $myEventsIds);
            })->where('status', 2)->sum('amount'),
        ];
        return view('dashboard', ['stats' => $stats]); 

    } elseif ($user->user_type_id == 3) { // 3 = Avaliador
        $pendingReviews = $user->reviews()
                              ->where('status', 0) // Pendente
                              ->with('work.user')
                              ->get();
        return view('dashboard', ['pendingReviews' => $pendingReviews]);
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Rotas de GESTÃO e AÇÕES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Organizador (ID 2)
    Route::middleware(['organizer'])->group(function () {
        Route::resource('events', EventController::class);
        Route::get('/events/{event}/activities/create', [ActivityController::class, 'create'])->name('activities.create');
        Route::post('/events/{event}/activities', [ActivityController::class, 'store'])->name('activities.store');
        Route::get('/events/{event}/inscription-types/create', [InscriptionTypeController::class, 'create'])->name('inscription_types.create');
        Route::post('/events/{event}/inscription-types', [InscriptionTypeController::class, 'store'])->name('inscription_types.store');
        Route::get('/inscription-types/{inscriptionType}/edit', [InscriptionTypeController::class, 'edit'])->name('inscription_types.edit');
        Route::put('/inscription-types/{inscriptionType}', [InscriptionTypeController::class, 'update'])->name('inscription_types.update');
        Route::delete('/inscription-types/{inscriptionType}', [InscriptionTypeController::class, 'destroy'])->name('inscription_types.destroy');
        Route::get('/organizacao/pagamentos', [PaymentController::class, 'index'])->name('organization.payments.index');
        Route::post('/organizacao/pagamentos/{inscription}/aprovar', [PaymentController::class, 'approve'])->name('organization.payments.approve');
        Route::post('/organizacao/pagamentos/{inscription}/recusar', [PaymentController::class, 'reject'])->name('organization.payments.reject');
        Route::get('/organizacao/pagamentos/{payment}/download', [PaymentController::class, 'download'])->name('organization.payments.download');
        Route::get('/organizacao/trabalhos', [SubmissionController::class, 'index'])->name('submissions.index');
        Route::post('/organizacao/trabalhos/{work}/assign', [SubmissionController::class, 'assign'])->name('submissions.assign');
        Route::post('/organizacao/trabalhos/{work}/schedule', [SubmissionController::class, 'schedule'])->name('submissions.schedule');
        Route::post('/organizacao/trabalhos/{work}/confirm-presentation', [SubmissionController::class, 'confirmPresentation'])->name('submissions.confirm'); // 👈 ADICIONADO
        Route::get('/events/{event}/export', [\App\Http\Controllers\ReportController::class, 'exportInscriptions'])->name('events.export');
    });

    // Participante (ID 1)
    Route::get('/eventos/{event}/inscrever', [InscriptionController::class, 'create'])->name('inscriptions.create');
    Route::post('/eventos/{event}/inscrever', [InscriptionController::class, 'store'])->name('inscriptions.store');
    Route::delete('/inscricoes/{inscription}', [InscriptionController::class, 'destroy'])->name('inscriptions.destroy');
    Route::get('/inscricoes/{inscription}/pagar', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/inscricoes/{inscription}/pagar', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/events/{event}/works/create', [WorkController::class, 'create'])->name('works.create');
    Route::post('/events/{event}/works', [WorkController::class, 'store'])->name('works.store');
    Route::get('/works/{work}/download', [WorkController::class, 'download'])->name('works.download');
    
    // Avaliador (ID 3)
    Route::get('/avaliacoes/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::patch('/avaliacoes/{review}', [ReviewController::class, 'update'])->name('reviews.update');

    // Saída (Certificados e QR Code)
    Route::get('/inscricoes/{inscription}/comprovante', [CertificateController::class, 'registrationProof'])->name('inscriptions.proof'); // 👈 ADICIONADO
    Route::get('/certificados/{inscription}/participacao', [CertificateController::class, 'participation'])->name('certificates.participation');
    Route::get('/certificados/trabalho/{work}', [CertificateController::class, 'presentation'])->name('certificates.presentation');
    Route::get('/presenca/scanner', [AttendanceController::class, 'scannerPage'])->name('attendance.scanner');
    Route::get('/presenca/qrcode/{inscription}', [AttendanceController::class, 'showQrCode'])->name('attendance.qrcode');
});

// ROTA PARA GERAR EVENTOS FULL TESTE
Route::get('/gerar-eventos-full', function() {
    $u = \App\Models\User::first();
    $images = [
        'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=1000',
        'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=1000',
        'https://images.unsplash.com/photo-1475721027187-402ad2989a3b?q=80&w=1000',
        'https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=1000',
        'https://images.unsplash.com/photo-1531050171654-776e72796b7c?q=80&w=1000',
        'https://images.unsplash.com/photo-1515187029135-18ee286d815b?q=80&w=1000',
        'https://images.unsplash.com/photo-1591115765373-520b7a21769b?q=80&w=1000',
        'https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=1000',
        'https://images.unsplash.com/photo-1561489396-888724a1543d?q=80&w=1000',
        'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=1000'
    ];

    for($i=0; $i<10; $i++) {
        \App\Models\Event::create([
            'user_id' => $u->id,
            'title' => 'Conferência Global Tech #' . ($i+1),
            'description' => 'Uma imersão completa no mundo da tecnologia, abordando inteligência artificial, desenvolvimento de software e segurança cibernética.',
            'location' => 'Centro de Convenções ' . ($i+1),
            'event_date' => now()->addDays($i + 10),
            'registration_deadline' => now()->addDays($i + 5),
            'registration_fee' => 49.90 * ($i+1),
            'pix_key' => 'pix-evento-full-' . $i . '@fatec.com',
            // Usamos a URL da imagem externa como se fosse o path (para facilitar o teste)
            'cover_image_path' => $images[$i] 
        ]);
    }
    return "10 Eventos com capas gerados! Vá para a Home.";
});

// ROTA PARA ATUALIZAR CAPAS COM CORES VIBRANTES
Route::get('/atualizar-capas-vibrantes', function() {
    $events = \App\Models\Event::orderBy('id', 'asc')->take(10)->get();
    $vividImages = [
        'https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=1000', // Neon Retro
        'https://images.unsplash.com/photo-1541701494587-cb58502866ab?q=80&w=1000', // Abstract Vivid
        'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1000', // Event Crowd Red
        'https://images.unsplash.com/photo-1514525253344-90206e4799c7?q=80&w=1000', // Vibrant Stage
        'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=1000', // Artistic Colorful
        'https://images.unsplash.com/photo-1519750783826-e2420f4d687f?q=80&w=1000', // Neon Pink/Blue
        'https://images.unsplash.com/photo-1557683316-973673baf926?q=80&w=1000', // Vivid Gradient
        'https://images.unsplash.com/photo-1605810230434-7631ac76ec81?q=80&w=1000', // Tech Colorful
        'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?q=80&w=1000', // Geometric Vivid
        'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=1000'  // Vibrant Festival
    ];

    foreach ($events as $index => $event) {
        if (isset($vividImages[$index])) {
            $event->update(['cover_image_path' => $vividImages[$index]]);
        }
    }
    return "10 Capas vibrantes aplicadas com sucesso! Verifique a Home.";
});

require __DIR__.'/auth.php';