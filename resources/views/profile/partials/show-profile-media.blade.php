@php
    $profile = $user->userProfile;
    $avatarUrl = $profile?->avatar ? Storage::url($profile->avatar) : null;
    $bannerUrl = $profile?->cover ? Storage::url($profile->cover) : null;
    $initials = '';
    $name = $user->name ?? '';
    if ($name !== '') {
        preg_match_all('/\b(\p{L})/u', $name, $matches);
        $initials = strtoupper(implode('', $matches[1]));
    }
@endphp

<div class="mb-12">
    <div class="relative">
        <div class="h-48 w-full rounded-2xl bg-gray-50">
            @if($bannerUrl)
                <img
                    src="{{ $bannerUrl }}"
                    alt="Banner"
                    class="h-48 w-full rounded-2xl object-cover"
                />
            @else
                <div
                    class="flex h-48 w-full items-center justify-center rounded-2xl border border-dashed border-gray-300 text-sm text-gray-500">
                    No banner uploaded
                </div>
            @endif
        </div>

        <div class="absolute left-8 bottom-0 translate-y-1/2">
            <div
                class="h-24 w-24 rounded-full border-4 border-white bg-gray-900 shadow-xl
                       flex items-center justify-center overflow-hidden">
                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" class="h-full w-full object-cover" alt="Avatar" />
                @else
                    <span class="text-white text-xl font-semibold">{{ $initials }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-12">
    <h3 class="text-lg font-semibold text-gray-900">Bio</h3>
    <p class="mt-2 text-sm text-gray-700">
        {{ $profile?->bio ?: 'No bio added yet.' }}
    </p>
</div>
