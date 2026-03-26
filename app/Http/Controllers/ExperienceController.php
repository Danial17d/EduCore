<?php

namespace App\Http\Controllers;

use App\Enums\PermissionType;
use App\Http\Requests\StoreExperienceRequest;
use App\Http\Requests\UpdateExperienceRequest;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;

class ExperienceController extends Controller
{

    public function store(StoreExperienceRequest $request){

        Gate::authorize(PermissionType::ExperienceCreate);

        $validated = $request->validated();

        if (!empty($validated['is_current'])) {
            $validated['end_date'] = null;
        }

        $request->user()->experiences()->create($validated);

        return Redirect::route('profile.edit')->with('status', 'experience-added');
    }
    public function update(UpdateExperienceRequest $request, Experience $experience){

        Gate::authorize(PermissionType::ExperienceUpdate);

        $validated = $request->validated();

        $validated['is_current'] = $request->boolean('is_current');
        if ($validated['is_current']) {
            $validated['end_date'] = null;
        }

        if ($experience->user_id !== $request->user()->id) {
            abort(403);
        }

        $experience->update($validated);

        return Redirect::route('profile.edit')->with('status', 'experience-updated');
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
