<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show users list with departments for admin management.
     */
    public function index()
    {

        $auth = auth()->user();
        if (! $auth || ! $auth->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $users = User::with('department')->get();
        $departments = Department::orderBy('name')->get();

        return view('users.index', compact('users', 'departments'));
    }

    public function updateRole(Request $request, User $user)
    {
        $auth = auth()->user();
        if (! $auth || ! $auth->isAdmin()) {
            return redirect()->back()->with('error', 'Je hebt geen toestemming om rollen te wijzigen.');
        }

        $data = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
        ]);

        $user->department_id = $data['department_id'];
        $user->role = User::roleForDepartment((int) $data['department_id']) ?? $user->role;
        $user->save();

        return back()->with('status', 'Rol en afdeling bijgewerkt.');
    }
}
