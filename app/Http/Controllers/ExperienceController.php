<?php

namespace App\Http\Controllers;

use App\Enums\PermissionType;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;

class ExperienceController extends Controller
{

    public function store(Request $request){

        Gate::authorize(PermissionType::ExperienceCreate);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'company' => ['required', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'],
            'employment_type' => ['nullable', 'string', 'max:60'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        if (!empty($validated['is_current'])) {
            $validated['end_date'] = null;
        }

        $request->user()->experiences()->create($validated);

        return Redirect::route('profile.edit')->with('status', 'experience-added');
    }
    public function update(Request $request, Experience $experience){

        Gate::authorize(PermissionType::ExperienceUpdate)
    }

    public function destroy(Request $request, Experience $experience){
        Gate::authorize(PermissionType::ExperienceDelete);

        if ($experience->user_id !== $request->user()->id) {
            abort(403);
        }

        $experience->delete();

        return Redirect::route('profile.edit')->with('status', 'experience-deleted');



    }
}
