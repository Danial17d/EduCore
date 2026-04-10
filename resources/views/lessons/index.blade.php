<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Related Courses</p>
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">{{ $course->name }}</h2>
            </div>
            <a href="{{ route('courses.show', $course->slug) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                Back to course
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8 space-y-8">
            @if($lessons)
                @foreach($lessons as $lesson)
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Current Course</p>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $course->name }}</h3>
                                <p class="text-sm text-slate-500">{{ $lesson->slug }}</p>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600">
                            Lesson
                        </span>
                        </div>
                        @if($lesson->attachments->count())
                            <details class="mt-4 group">
                                <summary class="flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 list-none">
                                    <svg class="h-4 w-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    Attachments ({{ $lesson->attachments->count() }})
                                </summary>

                                <ul class="mt-3 space-y-2 pl-6">
                                    @foreach($lesson->attachments as $attachment)
                                        <li>
                                            <a href="{{ asset('storage/'.$attachment->path) }}"
                                               target="_blank"
                                               class="flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                </svg>
                                                {{ $attachment->name ?? basename($attachment->path) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </details>
                        @endif

                    </section>
                @endforeach
            @else
                <div class="text-center p-4 border-gray border-b-1 ">
                    <h1 class="text-gray-500 text-xl">There is no lesson for this course</h1>
                </div>
            @endif


            <section class="space-y-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Courses Related To {{ $course->name }}</h3>
                    <span class="text-sm text-slate-500">{{ $relatedCourses->total() }} found</span>
                </div>

                @if($relatedCourses->isEmpty())
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-600 shadow-sm">
                        <p class="text-lg font-semibold text-slate-900">No related courses found.</p>
                        <p class="mt-2 text-sm">Try adding more courses in the same category.</p>
                    </div>
                @else
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($relatedCourses as $relatedCourse)
                            <article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">{{ $relatedCourse->code }}</p>
                                <h4 class="mt-2 text-lg font-semibold text-slate-900">{{ $relatedCourse->name }}</h4>
                                <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($relatedCourse->description, 100) }}</p>
                                <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
                                    <span>{{ $relatedCourse->lessons_count }} lessons</span>
                                </div>
                                <div class="mt-4">
                                    <x-hyper-link href="{{ route('courses.show', $relatedCourse->slug) }}">View Course</x-hyper-link>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if($relatedCourses->hasPages())
                        <div class="pt-4">
                            {{ $relatedCourses->links() }}
                        </div>
                    @endif
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
