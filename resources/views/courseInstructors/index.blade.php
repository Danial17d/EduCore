<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Course Instructors</p>
                <h1 class="text-lg font-semibold text-slate-900">Assigned Courses</h1>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('instructors-assignment.index') }}" method="GET" class="mb-6">
                <div class="flex flex-wrap items-end gap-3">
                    <div class="w-full max-w-xl">
                        <x-input-label for="search" value="Search by course, code, instructor name, or email" />
                        <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" :value="$search" />
                    </div>
                    <x-primary-button type="submit">Search</x-primary-button>
                    <a href="{{ route('instructors-assignment.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Reset</a>
                </div>
            </form>


            <section class="space-y-6">
                @forelse($courseInstructors as $assignment)
                    @php($course = $assignment->course)
                    <article class="flex flex-col gap-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:gap-8">
                        <div class="relative h-32 w-full overflow-hidden rounded-2xl sm:h-24 sm:w-40">
                            <img
                                src="{{ $course?->image ? asset('storage/'.$course->image) : asset('storage/courses/Online-learning.jpg') }}"
                                alt="{{ $course?->name ?? 'Course cover' }}"
                                class="h-full w-full object-cover"
                            >
                        </div>

                        <div class="flex-1 space-y-3">
                            <div class="flex flex-col gap-1">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">
                                    {{ $course?->category?->name ?? 'General' }} - {{ $course?->code ?? 'N/A' }}
                                </p>
                                <h3 class="text-xl font-semibold text-slate-900">{{ $course?->name ?? 'Deleted course' }}</h3>
                            </div>
                            <div class="text-sm text-slate-600">
                                <p><span class="font-semibold text-slate-800">Instructor:</span> {{ $assignment->instructor?->name ?? 'Deleted user' }}</p>
                                <p><span class="font-semibold text-slate-800">Email:</span> {{ $assignment->instructor?->email ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:flex-col sm:items-end sm:justify-center sm:gap-3">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                {{ $course?->status ?? 'N/A' }}
                            </span>
                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                                <form action="{{route('instructors-assignment.destroy',$assignment)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button>Unassign</x-danger-button>
                                </form>
                            @endif

                            @if($course?->slug)
                                <x-hyper-link href="{{ route('courses.show', $course->slug) }}">View</x-hyper-link>
                            @endif
                            <span class="text-xs text-slate-500">Assigned {{ $assignment->created_at?->diffForHumans() ?? '-' }}</span>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-600 shadow-sm">
                        <p class="text-lg font-semibold text-slate-900">No instructor assignments found.</p>
                        <p class="mt-2 text-sm">Assign instructors to courses and they will appear here.</p>
                    </div>
                @endforelse
            </section>

            @if ($courseInstructors->hasPages())
                <div class="mt-10">
                    {{ $courseInstructors->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
