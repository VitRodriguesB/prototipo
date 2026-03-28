<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Lista todos os usuários (RF_S1).
     */
    public function index()
    {
        $users = User::with('userType')->orderBy('name')->paginate(20);
        $userTypes = \App\Models\UserType::all();
        return view('admin.users.index', compact('users', 'userTypes'));
    }

    /**
     * Altera o papel (role) do usuário.
     */
    public function changeRole(User $user, Request $request)
    {
        $request->validate([
            'new_role' => 'required|integer|in:1,2,3,4' // 1: Participante, 2: Organizador, 3: Avaliador, 4: Admin
        ]);

        $user->user_type_id = $request->new_role;
        $user->save();

        return back()->with('success', "Perfil do usuário {$user->name} atualizado com sucesso.");
    }
}
