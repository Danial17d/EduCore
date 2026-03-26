<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Edit Lesson</p>
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                    {{ $lesson->title }}
                </h2>
            </div>
            @if($lesson->course)
                <a href="{{ route('courses.show', $lesson->course->slug) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                    Back to course
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-200 bg-white shadow-xl">
                <div class="border-b border-slate-200 px-6 py-6 sm:px-8">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <span class="rounded-full bg-slate-900/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white">
                                Lesson Builder
                            </span>
                            <p class="mt-3 text-sm text-slate-500">
                                Update the lesson details and manage any additional assets.
                            </p>
                        </div>
                        @if($lesson->course)
                            <div class="text-right text-sm text-slate-500">
                                Course code: <span class="font-semibold text-slate-700">{{ $lesson->course->code }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="px-6 py-8 sm:px-8">
                    <form method="post" action="{{ route('lessons.update', $lesson->slug) }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="title" value="Lesson title"/>
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                          value="{{ old('title', $lesson->title) }}"
                                          placeholder="Intro to data modeling"/>
                            <x-input-error class="mt-2" :messages="$errors->get('title')"/>
                        </div>

                        <div>
                            <x-input-label for="duration" value="Duration (minutes)"/>
                            <x-text-input id="duration" name="duration" type="number" min="5" class="mt-1 block w-full"
                                          value="{{ old('duration', $lesson->duration) }}"
                                          placeholder="45"/>
                            <x-input-error class="mt-2" :messages="$errors->get('duration')"/>
                        </div>

                        <div>
                            <x-input-label for="description" value="Lesson summary" />
                            <textarea id="description" name="description" rows="4" class="border-2 border-gray-300 mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describe what students will learn and practice.">{{ old('description', $lesson->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <x-input-label for="objectives" value="Learning objectives" />
                                <textarea id="objectives" name="objectives" rows="5" class="border-2 border-gray-300 mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="- Understand key terms&#10;- Apply concepts in a short exercise">{{ old('objectives', $lesson->objectives) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('objectives')" />
                            </div>
                            <div>
                                <x-input-label for="resources" value="Resources & links" />
                                <textarea id="resources" name="resources" rows="5" class="border-2 border-gray-300 mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Add URLs, readings, or media references.">{{ old('resources', $lesson->resources) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('resources')" />
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label for="video" class="cursor-pointer">
                                    <div class="flex justify-center items-center text-gray-300 border-3 border-gray-300 border-dashed  h-40 w-full">
                                        Replace your video
                                    </div>
                                </label>
                                <input id="video" name="video" type="file" accept="video/*" class="sr-only" />
                                <ul id="video-files" class="mt-2 text-sm text-slate-600"></ul>
                                @if($lesson->video)
                                    <p class="mt-1 text-xs text-slate-500">Current: {{ basename($lesson->video) }}</p>
                                @endif
                                <x-input-error class="mt-2" :messages="$errors->get('video')" />
                                <p class="mt-1 text-xs text-slate-500">MP4, up to 100MB.</p>
                            </div>
                            <div>
                                <label for="attachments" class="cursor-pointer">
                                    <div class="flex justify-center items-center text-gray-300 border-3 border-gray-300 border-dashed  h-40 w-full">
                                        Add more attachments
                                    </div>
                                </label>
                                <input id="attachments" name="attachments[]" type="file"  multiple class="sr-only" />
                                <ul id="attachment-files" class="mt-2 text-sm text-slate-600"></ul>
                                @if($lesson->attachments->isNotEmpty())
                                    <ul class="mt-3 space-y-1 text-xs text-slate-500">
                                        @foreach($lesson->attachments as $attachment)
                                            <li>{{ $attachment->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                <x-input-error class="mt-2" :messages="$errors->get('attachments')" />
                                <x-input-error class="mt-2" :messages="$errors->get('attachments.*')" />
                                <p class="mt-1 text-xs text-slate-500">Upload worksheets, slides, or reference files.</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <x-primary-button type="submit">Update lesson</x-primary-button>
                            <a href="{{ route('lessons.show', $lesson->slug) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const videoInput = document.getElementById('video');
        const videoFiles = document.getElementById('video-files');
        const attachmentsInput = document.getElementById('attachments');
        const attachmentFiles = document.getElementById('attachment-files');

        const renderFiles = (listEl, files) => {
            listEl.innerHTML = '';
            if (!files || files.length === 0) {
                return;
            }

            Array.from(files).forEach((file) => {
                const item = document.createElement('li');
                item.textContent = file.name;
                listEl.appendChild(item);
            });
        };

        videoInput.addEventListener('change', () => renderFiles(videoFiles, videoInput.files));
        attachmentsInput.addEventListener('change', () => renderFiles(attachmentFiles, attachmentsInput.files));
    </script>
</x-app-layout>
