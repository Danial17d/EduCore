<?php

namespace App\Http\Controllers;


use App\Enums\PermissionType;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize(PermissionType::UserList);

        $request->validate([
            'search' => ['nullable','string']
        ]);
        $search = $request->input('search');

        $query = User::with('roles:id,name')
            ->when(filled($search) && $search !== 'role', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })->when($search === 'role', function ($query) use ($search) {
                $query->whereHas('roles', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            });
        $users = $query->paginate(10);
        return view('users.index',[
            'users' => $users,
        ]);
    }

    public function create()
    {
        Gate::authorize(PermissionType::UserCreate);

        return view('users.create', [
            'roles' => Role::query()->orderBy('name')->pluck('name'),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize(PermissionType::UserCreate);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::exists('roles', 'name')],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return Redirect::route('users.index')->with('status', 'user-created');
    }

    public function show(User $user)
    {
        Gate::authorize(PermissionType::UserView);

        $user->load('roles:id,name');

        return view('users.show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        Gate::authorize(PermissionType::UserUpdate);

        $user->load('roles:id,name');

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::query()->orderBy('name')->pluck('name'),
        ]);
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize(PermissionType::UserUpdate);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'string', Rule::exists('roles', 'name')],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);
        $user->syncRoles([$validated['role']]);

        return Redirect::route('users.show', $user)->with('status', 'user-updated');
    }

    public function destroy(User $user)
    {
        Gate::authorize(PermissionType::UserDelete);

        $user->userProfile()->delete();

        $user->delete();

        if (Auth::id() === $user->id) {
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return Redirect::route('register')->with('status', 'user-deleted');
        }

        return Redirect::route('users.index')->with('status', 'user-deleted');
    }
}
