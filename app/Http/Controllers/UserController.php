<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateRole(Request $request, User $user)
    {
        $auth = auth()->user();
        if (! $auth || ! $auth->isAdmin()) {
            return redirect()->back()->with('error', 'Je hebt geen toestemming om rollen te wijzigen.');
        }

        $data = $request->validate([
            'role' => ['required', 'in:employee,manager,admin'],
        ]);

        $user->role = $data['role'];
        $user->save();

        return back()->with('status', 'Rol bijgewerkt.');
    }
}
