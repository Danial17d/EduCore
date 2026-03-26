<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Edit user</p>
                <h1 class="text-lg font-semibold text-slate-900">{{ $user->name }}</h1>
            </div>
            <a
                href="{{ route('users.show', $user) }}"
                class="text-sm font-semibold text-gray-600 hover:text-gray-900"
            >
                Back to user
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto w-full max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow sm:p-8">
                <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="role" value="Role" />
                        <select id="role" name="role" class="mt-1 block w-full rounded-md border-2 border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="" disabled {{ old('role', $user->roles->first()?->name) ? '' : 'selected' }}>Select a role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ old('role', $user->roles->first()?->name) === $role ? 'selected' : '' }}>
                                    {{ $role }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('role')" />
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>Update user</x-primary-button>
                        <a href="{{ route('users.show', $user) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
