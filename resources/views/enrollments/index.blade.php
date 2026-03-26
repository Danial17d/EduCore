<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Enrollemts</p>
        </div>
    </x-slot>

    <x-auth-session-status :status="session('status')"/>

    <div class="mx-auto w-full max-w-6xl px-4 pb-12 pt-10 sm:px-6 lg:px-8">
        <section class="space-y-6">
            @forelse ($enrollments as $enrollment)
                @php
                    $course = $enrollment->course;
                    $completedLessons = (int) ($enrollment->completed_lessons_count ?? 0);
                    $totalLessons = (int) ($course?->lessons_count ?? 0);
                    $progress = $totalLessons > 0 ? (int) round(($completedLessons / $totalLessons) * 100) : 0;
                @endphp
                <article class="flex flex-col gap-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:gap-8">
                    <div class="relative h-32 w-full overflow-hidden rounded-2xl sm:h-24 sm:w-40">
                        <img src="{{ $course?->image ? asset('storage/'.$course->image) : asset('storage/courses/Online-learning.jpg') }}" alt="{{ $course?->name ?? 'Course cover' }}" class="h-full w-full object-cover">
                    </div>
                    <div class="flex-1 space-y-3">
                        <div class="flex flex-col gap-1">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">
                                {{ optional($course?->category)->name ?? 'General' }} - {{ $course?->code ?? 'N/A' }}
                            </p>
                            <h3 class="text-xl font-semibold text-slate-900">{{ $course?->name ?? 'Course unavailable' }}</h3>
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-sm font-semibold text-slate-700">
                                <span>Progress</span>
                                <span>{{ $progress }}%</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ $completedLessons }} of {{ $totalLessons }} lessons</p>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-slate-900" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between sm:flex-col sm:items-end sm:justify-center sm:gap-3">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            {{ $progress === 100 ? 'Completed' : 'In progress' }}
                        </span>
                        @if ($course?->slug)
                            <x-hyper-link href="{{ route('courses.show', $course->slug) }}">View</x-hyper-link>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-600 shadow-sm">
                    <p class="text-lg font-semibold text-slate-900">No enrollments yet.</p>
                    <p class="mt-2 text-sm">Browse courses and enroll to see them listed here.</p>
                </div>
            @endforelse
        </section>

        @if ($enrollments->hasPages())
            <div class="mt-10">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
