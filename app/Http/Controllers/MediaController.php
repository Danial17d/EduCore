<?php

namespace App\Http\Controllers;

use App\Enums\PermissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function update(Request $request)
    {
        Gate::authorize(PermissionType::ProfileUpdate);

        $validated = $request->validate([
            'avatar' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png'],
            'banner' => ['nullable', 'image', 'max:4096', 'mimes:jpg,jpeg,png'],
            'bio'    => ['nullable', 'string', 'max:255'],
        ]);


        $user = $request->user();

        $profile = $user->userProfile()->firstOrCreate([],[
            'bio' => $validated['bio'] ?? '',
        ]);
        $avatarUrl = null;
        $bannerUrl = null;

        if ($request->hasFile('avatar')) {

            if ($profile->avatar) {

                Storage::disk('public')->delete($profile->avatar);
            }

            $profile->avatar = $request->file('avatar')->store('avatars', 'public');
            $avatarUrl = asset('storage/' . $profile->avatar);
        }


        if ($request->hasFile('banner')) {
            if ($profile->cover) {
                Storage::disk('public')->delete($profile->cover);
            }

            $profile->cover = $request->file('banner')->store('banners', 'public');
            $bannerUrl = asset('storage/' . $profile->cover);
        }


        if (array_key_exists('bio', $validated)) {
            $profile->bio = $validated['bio'];
        }
        $profile->save();
        return Redirect::route('profile.edit')->with('status', 'profile-media-updated');
    }
}
