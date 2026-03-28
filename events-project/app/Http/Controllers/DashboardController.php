<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Payment;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard for authenticated users.
     */
    public function index()
    {
        if (auth()->user()->user_type_id == 4) {
            return redirect()->route('admin.users.index');
        }

        $user = Auth::user();
        $stats = [];
        $userInscriptions = [];
        $pendingReviews = [];

        if ($user->user_type_id == 1) { // 1 = Participante
            $userInscriptions = Inscription::with([
                'event', 
                'inscriptionType', 
                'work.reviews'
            ])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

            $stats = [
                'total_inscriptions' => $userInscriptions->count(),
                'confirmed_inscriptions' => $userInscriptions->where('status', 1)->count(),
                'submitted_works' => $userInscriptions->whereNotNull('work_id')->count(),
                'confirmed_presences' => $userInscriptions->where('attended', true)->count(),
            ];
        
        } elseif ($user->user_type_id == 2) { // 2 = Organizador
            $myEventsIds = $user->events()->pluck('id');
            
            $stats = [
                'total_inscriptions' => Inscription::whereIn('event_id', $myEventsIds)->count(),
                'confirmed_payments' => Inscription::whereIn('event_id', $myEventsIds)->where('status', 1)->count(),
                'pending_payments' => Payment::whereIn('inscription_id', function($query) use ($myEventsIds) {
                    $query->select('id')->from('inscriptions')->whereIn('event_id', $myEventsIds);
                })->where('status', 1)->count(),
                'total_revenue' => Payment::whereIn('inscription_id', function($query) use ($myEventsIds) {
                    $query->select('id')->from('inscriptions')->whereIn('event_id', $myEventsIds);
                })->where('status', 2)->sum('amount'),
            ];

        } elseif ($user->user_type_id == 3) { // 3 = Avaliador
            $pendingReviews = $user->reviews()
                                  ->where('status', 0) // 0 = Pendente
                                  ->with('work.user')
                                  ->get();
        }

        return view('dashboard', compact('userInscriptions', 'stats', 'pendingReviews'));
    }
}
