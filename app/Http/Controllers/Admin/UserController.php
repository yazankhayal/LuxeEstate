<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        // Only admins can manage users
        $this->authorizeAdmin();

        return Inertia::render('Admin/Users/Index', [
            'users' => User::orderByDesc('created_at')->paginate(20),
        ]);
    }

    public function create()
    {
        $this->authorizeAdmin();

        return Inertia::render('Admin/Users/Form');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role'     => ['required', 'in:admin,editor,viewer'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function editUser(int $id)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($id);

        return Inertia::render('Admin/Users/Form', [
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request, int $id)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', "unique:users,email,{$id}"],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role'     => ['required', 'in:admin,editor,viewer'],
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->authorizeAdmin();

        if ($id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        User::findOrFail($id)->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted.');
    }

    public function updateRole(Request $request, int $id)
    {
        $this->authorizeAdmin();

        $request->validate(['role' => 'required|in:admin,editor,viewer']);

        User::findOrFail($id)->update(['role' => $request->role]);

        return back()->with('success', 'Role updated.');
    }

    // Only 'admin' role can manage users
    private function authorizeAdmin(): void
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Only administrators can manage users.');
        }
    }
}
