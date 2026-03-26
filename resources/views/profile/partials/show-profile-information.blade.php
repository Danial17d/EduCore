<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Basic account details.
        </p>
    </header>

    <form id="profile-send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <div class="mt-6 space-y-4">
        <div>
            <div class="text-xs font-semibold text-gray-500">Name</div>
            <div class="text-sm text-gray-900">{{ $user->name }}</div>
        </div>
        <div>
            <div class="text-xs font-semibold text-gray-500">Email</div>
            <div class="text-sm text-gray-900">{{ $user->email }}</div>
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 rounded-lg border border-amber-200 bg-amber-50 p-3">
                    <p class="text-xs text-amber-800">Email address is not verified.</p>
                    <button
                        form="profile-send-verification"
                        class="mt-2 text-xs font-semibold text-amber-900 underline underline-offset-2 hover:text-amber-700"
                    >
                        Send verification email
                    </button>
                </div>
            @else
                <p class="mt-2 text-xs text-emerald-700">Email verified.</p>
            @endif

            @if (session('status') === 'verification-link-sent')
                <p class="mt-2 text-xs font-medium text-emerald-700">
                    A new verification link has been sent to your email address.
                </p>
            @endif
        </div>
    </div>
</section>
