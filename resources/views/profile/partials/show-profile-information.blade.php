<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Basic account details.
        </p>
    </header>

    <div class="mt-6 space-y-4">
        <div>
            <div class="text-xs font-semibold text-gray-500">Name</div>
            <div class="text-sm text-gray-900">{{ $user->name }}</div>
        </div>
        <div>
            <div class="text-xs font-semibold text-gray-500">Email</div>
            <div class="text-sm text-gray-900">{{ $user->email }}</div>
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="mt-2 text-xs text-gray-600">Email address is not verified.</p>
            @endif
        </div>
    </div>
</section>
