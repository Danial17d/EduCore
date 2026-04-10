<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Users</p>
            </div>
            <a href="{{ route('users.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                Create User
            </a>
        </div>
    </x-slot>
    <div class="flex justify-center">

        <div class="w-full max-w-screen-2xl  p-10">
            <form action="{{route('users.index')}}" method="get">
                <div class="w-full mb-10 bg-slate-100">
                    <x-input-label value="search"/>
                    <div class="flex space-x-3">
                        <x-text-input class="w-xl" name="search"/>
                        <x-primary-button type="submit">Search</x-primary-button>
                    </div>
                </div>
            </form>
                <div class="w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <table class="w-full text-center text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                        <tr class="border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.2em]">ID</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.2em]">Name</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.2em]">Email</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.2em]">Role</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.2em]">Verified</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.2em]">Actions</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($users as $user)
                                <tr class="hover:bg-slate-50 cursor-pointer" onclick="window.location='{{ route('users.show', $user) }}'">
                                    <td class="p-5 font-bold">{{$user->id}}</td>
                                    <td class="p-5 font-bold">{{$user->name}}</td>
                                    <td class="p-5 font-bold">{{$user->email}}</td>
                                    <td class="p-5 font-bold">{{ $user->roles->pluck('name')->join(', ') }}</td>
                                    <td class="p-5 font-bold">{{ $user->email_verified_at?->diffForHumans() ?? 'Not verified' }}</td>
                                    <td>
                                        <div class="flex items-center justify-center gap-2" onclick="event.stopPropagation()">
                                            <x-hyper-link href="{{route('users.edit',$user)}}">Edit</x-hyper-link>
                                            <x-delete-modal
                                                :action="route('users.destroy', $user)"
                                                :message="'Delete user \'' . $user->name . '\'? This cannot be undone.'"
                                                label="Delete" />
                                        </div>
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>
                    </table>
                    <div class="border-t border-slate-200 px-6 py-4">
                        {{ $users->links() }}
                    </div>
                </div>


        </div>
    </div>




</x-app-layout>
