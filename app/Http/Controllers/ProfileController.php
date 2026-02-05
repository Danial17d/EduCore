<?php

namespace App\Http\Controllers;

use App\Enums\PermissionType;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(Request $request): View
    {
        Gate::authorize(PermissionType::ProfileView);
        $user = $request->user();
        $user->load([
            'userProfile',
            'experiences' => function ($query) {
                $query->orderByDesc('is_current')->orderByDesc('start_date');
            },
            'certificates' => function ($query) {
                $query->orderByDesc('issue_date');
            },
        ]);

        return view('profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        Gate::authorize(PermissionType::ProfileUpdate);
        $user = $request->user();
        $user->load([
            'userProfile',
            'experiences' => function ($query) {
                $query->orderByDesc('is_current')->orderByDesc('start_date');
            },
            'certificates' => function ($query) {
                $query->orderByDesc('issue_date');
            },
        ]);
        return view('profile.edit', [
            'user' => $user,
        ]);

    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        Gate::authorize(PermissionType::ProfileUpdate);

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }



    public function destroy(Request $request): RedirectResponse
    {
        Gate::authorize(PermissionType::ProfileDelete);

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
