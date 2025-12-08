<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function store(Request $request)
    {
        $auth = auth()->user();
        if (! $auth || ! $auth->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:employee,manager,admin'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'department_id' => $data['department_id'] ?? null,
        ]);

        return back()->with('status', 'Gebruiker aangemaakt.');
    }

    public function updateRole(Request $request, User $user)
    {
        $auth = auth()->user();
        if (! $auth || ! $auth->isAdmin()) {
            return redirect()->back()->with('error', 'Je hebt geen toestemming om rollen te wijzigen.');
        }

        $data = $request->validate([
            'role' => ['required', 'in:employee,manager,admin'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        $user->role = $data['role'];
        $user->department_id = $data['department_id'] ?? null;
        $user->save();

        return back()->with('status', 'Rol en afdeling bijgewerkt.');
    }

    public function destroy(User $user)
    {
        $auth = auth()->user();
        if (! $auth || ! $auth->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $user->delete();

        return back()->with('status', 'Gebruiker verwijderd.');
    }
}
