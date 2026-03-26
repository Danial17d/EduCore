<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">User Profile</p>
                <h1 class="text-lg font-semibold text-slate-900">{{ $user->name }}</h1>
            </div>
            <a href="{{ route('users.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Back to users
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="grid gap-6 p-6 md:grid-cols-2">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Name</p>
                        <p class="mt-1 text-base font-semibold text-slate-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Email</p>
                        <p class="mt-1 text-base font-semibold text-slate-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Roles</p>
                        <p class="mt-1 text-base font-semibold text-slate-900">{{ $user->roles->pluck('name')->join(', ') ?: 'No role assigned' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Verified</p>
                        <p class="mt-1 text-base font-semibold text-slate-900">{{ $user->email_verified_at?->diffForHumans() ?? 'Not verified' }}</p>
                    </div>
                </div>
                <div class="border-t border-slate-200 p-6">
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('users.edit', $user) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Edit user
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="submit">Delete user</x-danger-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
