<?php

namespace App\Http\Controllers;

use App\Enums\PermissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function __invoke()
    {
        Gate::authorize(PermissionType::DashboardView);

        return view('dashboard');
    }
}
