<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Create new category</p>
            </div>
            <div class="space-x-4">
                <a href="{{ route('courses.create') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                    Back to Course creation
                </a>
                <a href="{{ route('categories.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                    Back to categories
                </a>
            </div>


        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto w-full max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow sm:p-8">
                <form method="POST" action="{{ route('categories.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Description (optional)" />
                        <textarea id="description" name="description" rows="3"
                            class="border-2 border-gray-300 mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2">{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>Create category</x-primary-button>
                        <a href="{{ route('categories.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
