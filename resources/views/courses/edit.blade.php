<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Edit course</p>
            </div>
            <a
                href="{{ route('courses.show', $course->slug) }}"
                class="text-sm font-semibold text-gray-600 hover:text-gray-900"
            >
                Back to course
            </a>
        </div>
    </x-slot>


    <div class="py-10">
        <div class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,320px)]">
                <div class="rounded-2xl bg-white p-6 shadow sm:p-8">
                    <form method="post" action="{{ route('courses.update', $course->slug) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <x-input-label for="name" value="Course Name" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $course->name)" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="code" value="Course Code" />
                                <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" :value="old('code', $course->code)" />
                                <x-input-error class="mt-2" :messages="$errors->get('code')" />
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <x-input-label for="category_name" value="Category" />
                                @if(!empty($categories))
                                    <select id="category_name" name="category_name" class="px-3 py-2 border-2 border-gray-300 mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="" disabled {{ old('category_name', $course->category->name) ? '' : 'selected' }}>Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->name }}" {{ (string) old('category_name', $course->category->name) === (string) $category->name ? 'selected' : '' }}>
                                                {{ $category->name  }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <x-text-input id="category_name" name="category_name" type="text" class="mt-1 block w-full" :value="old('category_name', $course->category->name)" />
                                @endif
                                <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <x-input-label for="price" value="Price (SAR)" />
                                <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price', $course->price)" placeholder="Leave blank for free" />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                                <p class="mt-1 text-xs text-gray-500">Leave blank if the course is free.</p>
                            </div>

                            <div>
                                <x-input-label for="credit" value="Credits" />
                                <x-text-input id="credit" name="credit" type="number" min="1" class="mt-1 block w-full" :value="old('credit', $course->credit)" placeholder="e.g. 3" />
                                <x-input-error class="mt-2" :messages="$errors->get('credit')" />
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <x-input-label for="status" value="Status" />
                                <select id="status" name="status" class="px-3 py-2 border-2 border-gray-300 mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="1" {{ $course->status === 'active' || $course->status === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $course->status === 'inactive' || $course->status === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div>
                                <x-input-label for="image" value="Cover Image" />
                                <input id="image" name="image" type="file" accept="image/*" class="border-2 border-gray-300 mt-1 block w-full rounded-md bg-white px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                <p class="mt-1 text-xs text-gray-500">Leave blank to keep the current cover.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('image')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" rows="4" class="p-3 border-2 border-gray-300 mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $course->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="slug" value="Slug (optional)" />
                            <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $course->slug)" />
                            <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                            <p class="mt-1 text-xs text-gray-500">Leave blank to keep the current slug.</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>Save changes</x-primary-button>
                            <a href="{{ route('courses.show', $course->slug) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Preview</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">{{ $course->name }}</h3>

                    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
                        <img
                            src="{{  $course->image ? asset('storage/'.$course?->image) : asset('storage/courses/Online-learning.jpg') }}"
                            alt="{{ $course->name }} cover"
                            class="h-40 w-full object-cover"
                        />
                    </div>

                    <div class="mt-4 space-y-2 text-sm text-slate-600">
                        <p>
                            <span class="font-semibold text-slate-800">Category:</span>
                            {{ $course->category->name ?? 'General' }}
                        </p>
                        <p>
                            <span class="font-semibold text-slate-800">Status:</span>
                            {{ $course->status }}
                        </p>
                        <p class="text-xs text-slate-500">
                            Updated {{ $course->updated_at?->diffForHumans() ?? 'just now' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
