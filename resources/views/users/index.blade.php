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
                                        <div class="space-x-2" onclick="event.stopPropagation()">
                                            <x-hyper-link href="{{route('users.edit',$user)}}">Edit</x-hyper-link>
                                            <form action="{{route('users.destroy',$user)}}" method="POST" class="inline js-delete-user-form" data-user-name="{{$user->name}}">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button type="button" class="js-delete-trigger">delete</x-danger-button>
                                            </form>
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

<div id="delete-user-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-slate-900/55"></div>
    <div class="relative mx-auto mt-5 w-[92%] max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
        <h3 class="text-lg font-semibold text-slate-900">Delete user</h3>
        <p id="delete-user-message" class="mt-2 text-sm text-slate-600">
            Are you sure you want to delete this user?
        </p>
        <div class="mt-6 flex items-center justify-end gap-3">
            <button id="delete-user-cancel" type="button" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                Cancel
            </button>
            <button id="delete-user-confirm" type="button" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                Yes, delete
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('delete-user-modal');
        const message = document.getElementById('delete-user-message');
        const cancelButton = document.getElementById('delete-user-cancel');
        const confirmButton = document.getElementById('delete-user-confirm');
        var selectedForm = null;

        function closeModal() {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
            selectedForm = null;
        }

        document.querySelectorAll('.js-delete-user-form').forEach(function (form) {
            var trigger = form.querySelector('.js-delete-trigger');
            trigger.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                selectedForm = form;
                var userName = form.dataset.userName || 'this user';
                message.textContent = 'Are you sure you want to delete ' + userName + '? This action cannot be undone.';
                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');
            });
        });

        cancelButton.addEventListener('click', closeModal);

        confirmButton.addEventListener('click', function () {
            if (selectedForm) {
                selectedForm.submit();
            }
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal || event.target === modal.firstElementChild) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>
