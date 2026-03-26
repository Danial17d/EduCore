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
<form action="{{route('media.update')}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div class="mb-12">
        <x-img-feed-back />
        <div class="relative">

            <label for="banner" class="block cursor-pointer">
                <img
                    src="{{ $bannerUrl ?? '' }}"
                    alt="Banner"
                    class="h-48 w-full rounded-2xl object-cover {{ empty($bannerUrl) ? 'hidden' : '' }}"
                    id="bannerPreview"
                />
                <div
                    id="bannerPlaceholder"
                    class="flex h-48 w-full items-center justify-center
                           rounded-2xl border border-dashed border-gray-300
                           bg-gray-50 text-sm text-gray-500 {{ empty($bannerUrl) ? '' : 'hidden' }}">
                    No banner uploaded
                </div>
            </label>

            <input id="banner" name="banner" type="file" accept="image/*" class="sr-only"/>

            <div class="absolute left-8 bottom-0 translate-y-1/2">
                <label for="avatar" class="block cursor-pointer">
                    <div
                        class="h-24 w-24 rounded-full border-4 border-white
                               bg-gray-900 shadow-xl
                               flex items-center justify-center overflow-hidden">

                        <img
                            src="{{ $avatarUrl ?? '' }}"
                            id="avatarPreview"
                            class="h-full w-full object-cover {{ empty($avatarUrl) ? 'hidden' : '' }}"
                            alt=""
                        />
                        <span id="avatarInitials" class="text-white text-xl font-semibold {{ empty($avatarUrl) ? '' : 'hidden' }}">
                        {{ $initials }}
                    </span>
                        <input id="avatar" name="avatar" type="file" accept="image/*" class="sr-only"/>
                    </div>
                </label>
            </div>
        </div>
    </div>
    <div class="mt-10">

        @if(auth()->id() === $user->id )
            <x-input-label for="bio" value="Bio" />
            <textarea
                id="bio"
                name="bio"
                rows="4"
                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            >{{ old('bio', $profile?->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />

            <div class="mt-5">

                <x-primary-button>Save</x-primary-button>

            </div>

        @endif


    </div>

</form>
