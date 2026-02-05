<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Course') }}
            </h2>
            <a
                href="{{ route('courses.index') }}"
                class="text-sm font-semibold text-gray-600 hover:text-gray-900"
            >
                Back to courses
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow sm:p-8">
                <form method="post" action="{{ route('courses.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="name" value="Course Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="code" value="Course Code" />
                            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" :value="old('code')" />
                            <x-input-error class="mt-2" :messages="$errors->get('code')" />
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="category_name" value="Category" />
                            <select id="category_name" name="category_name"  class="px-3 py-2 border-2 border-gray-300 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled {{ old('category_name') ? '' : 'selected' }}>Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ (string) old('category_name') === (string) $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="credit" value="Credit" />
                            <x-text-input id="credit" name="credit" type="number" min="1" class="mt-1 block w-full" :value="old('credit')" />
                            <x-input-error class="mt-2" :messages="$errors->get('credit')" />
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" class="px-3 py-2 border-2 border-gray-300 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <div>
                            <x-input-label for="image" value="Cover Image" />
                            <input id="image" name="image" type="file" accept="image/*" class="border-2 border-gray-300 mt-1 block w-full rounded-md border-gray-300 bg-white px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="4" class="border-2 border-gray-300 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div>
                        <x-input-label for="slug" value="Slug (optional)" />
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug')" />
                        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                        <p class="mt-1 text-xs text-gray-500">Leave blank to generate from the course name.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>Create course</x-primary-button>
                        <a href="{{ route('courses.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
