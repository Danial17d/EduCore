<x-app-layout>

</x-app-layout>
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
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Current Course</p>
                        <h3 class="text-lg font-semibold text-slate-900">{{ $course->name }}</h3>
                        <p class="text-sm text-slate-500">{{ $course->code }} - {{ $course->lessons->count() }} lessons</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600">
                        {{ $course->category?->name ?? 'General' }}
                    </span>
                </div>
            </section>

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
                                    <span>{{ number_format((float) $relatedCourse->price, 2) }} SAR</span>
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
