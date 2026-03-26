<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Lesson</p>
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">{{ $lesson->title }}</h2>
            </div>
            <div class="flex items-center gap-4">
                @if($lesson->course)
                    <a href="{{ route('courses.show', $lesson->course->slug) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                        Back to course
                    </a>
                    <x-hyper-link href="{{ route('lessons.index', $lesson->course->slug) }}">Related courses</x-hyper-link>
                @endif
                <x-hyper-link href="{{ route('lessons.edit', $lesson->slug) }}">Edit</x-hyper-link>
                @if(auth()->user()->hasRole('admin'))
                    <form method="post" action="{{ route('lessons.destroy', $lesson->slug) }}" onsubmit="return confirm('Delete this lesson? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button type="submit" class="px-4 py-3" >
                            Delete
                        </x-danger-button>
                    </form>
                @endif
                @if($nextLesson)
                    <x-hyper-link href="{{ route('lessons.show', $nextLesson->slug) }}">Next lesson</x-hyper-link>
                @endif
            </div>
        </div>
    </x-slot>

    <x-auth-session-status :status="session('status')"/>

    <div class="py-10">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-200 bg-white shadow-xl">
                <div class="border-b border-slate-200 px-6 py-5 sm:px-8">
                    <div class="flex flex-wrap items-center justify-between gap-3 text-sm text-slate-500">
                        @if($lesson->course)
                            <span>Course: {{ $lesson->course->name }}</span>
                        @endif
                        @if($lesson->video)
                            <span>Duration: {{ $lesson->duration }} min</span>
                        @endif

                    </div>
                </div>

                <div class="space-y-6 px-6 py-8 sm:px-8">
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Summary</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $lesson->description }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Objectives</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $lesson->objectives }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Resources</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $lesson->resources }}</p>
                    </div>

                    @if($lesson->video)
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Video</h3>
                            <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                <video class="w-full" controls id="video">
                                    <source src="{{ asset('storage/'.$lesson->video) }}" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Attachments</h3>
                        @if($lesson->attachments->isEmpty())
                            <p class="mt-3 text-sm text-slate-500">No attachments.</p>
                        @else
                            <ul class="mt-4 space-y-2 text-sm text-slate-600">
                                @foreach($lesson->attachments as $attachment)
                                    <li class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3">
                                        <div>
                                            <p class="font-medium text-slate-700">{{ $attachment->name }}</p>
                                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ $attachment->type }}</p>
                                        </div>
                                        <a class="text-sm font-semibold text-slate-700 hover:text-slate-900" href="{{ asset('storage/'.$attachment->path) }}">
                                            Download
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div>
                        <form id="lesson-progress-form" action="{{ route('lesson-progress.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="lesson_id" value="{{$lesson->id}}">
                            <input type="hidden" name="completed" value="{{true}}">
                            <x-primary-button id="lesson-progress-button" type="submit">Complete</x-primary-button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const form = document.getElementById('lesson-progress-form');
            if (!form) return;

            const video = document.getElementById('video');
            const button = document.getElementById('lesson-progress-button');

            const sendProgress = async () => {
                const formData = new FormData(form);
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': formData.get('_token'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    if (response.ok) {
                        const payload = await response.json();
                        if (payload?.status && typeof window.showStatus === 'function') {
                            window.showStatus(payload.status);
                        }
                    }
                } catch (error) {
                    // ignore
                }
            };

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                sendProgress();
            });

            if (video) {
                video.addEventListener('ended', sendProgress);
            }
        })();
    </script>
</x-app-layout>
