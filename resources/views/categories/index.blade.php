<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Categories</p>
            </div>
            <a href="{{ route('categories.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                Create Category
            </a>
        </div>
    </x-slot>

    <div class="flex justify-center">
        <div class="w-full max-w-screen-xl p-10">

            @if (session('status') === 'category-created')
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                    Category created successfully.
                </div>
            @elseif (session('status') === 'category-updated')
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                    Category updated successfully.
                </div>
            @elseif (session('status') === 'category-deleted')
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                    Category deleted.
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr class="border-b border-slate-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em]">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em]">Slug</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em]">Description</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.2em]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-semibold">{{ $category->name }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $category->slug }}</td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $category->description ? \Illuminate\Support\Str::limit($category->description, 60) : '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <x-hyper-link href="{{ route('categories.edit', $category) }}">Edit</x-hyper-link>
                                        <x-delete-modal
                                            :action="route('categories.destroy', $category)"
                                            :message="'Delete category \'' . $category->name . '\'? This cannot be undone.'" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                    No categories yet.
                                    <a href="{{ route('categories.create') }}" class="ml-1 font-semibold text-indigo-600 hover:underline">Create the first one.</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($categories->hasPages())
                    <div class="border-t border-slate-200 px-6 py-4">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
