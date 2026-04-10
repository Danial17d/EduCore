@props([
    'action',
    'message' => 'Are you sure you want to delete this? This action cannot be undone.',
    'label'   => 'Delete',
])

<div x-data="{ open: false }">
    <x-danger-button type="button" @click="open = true">{{ $label }}</x-danger-button>

    <div x-show="open"
         style="display:none"
         class="fixed inset-0 z-50"
         role="dialog"
         aria-modal="true"
         @keydown.escape.window="open = false">
        <div class="absolute inset-0 bg-slate-900/55" @click="open = false"></div>
        <div class="relative mx-auto mt-24 w-[92%] max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
            <h3 class="text-lg font-semibold text-slate-900">Confirm deletion</h3>
            <p class="mt-2 text-sm text-slate-600">{{ $message }}</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" @click="open = false"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    Cancel
                </button>
                <form method="POST" action="{{ $action }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                        Yes, delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>