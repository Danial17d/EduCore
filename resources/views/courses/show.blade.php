<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Course Code</p>
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">{{ $course->code }}</h2>
            </div>
            <a href="{{ route('courses.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                Back to courses
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-200 bg-white shadow-xl">
                <div class="border-b border-slate-200 px-6 py-5 sm:px-8">
                    <div class="flex flex-wrap justify-between items-center gap-3">
                        <div>
                            <span class="rounded-full bg-slate-900/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white">
                                {{ $course->status }}
                            </span>
                            <span class="text-sm text-slate-500 ml-2">{{ $course->credit }} credits</span>
                            @if($course->category)
                                <span class="text-sm text-slate-500">Category: {{ $course->category->name }}</span>
                            @endif
                        </div>
                      <div class="space-x-2">
                          <x-hyper-link href="{{route('lessons.index',$course->slug)}}">Lessons</x-hyper-link>
                          <x-hyper-link href="{{route('lessons.create',$course->slug)}}">Add Lesson</x-hyper-link>
                      </div>
                    </div>
                </div>

                <div class="px-6 py-8 sm:px-8">
                    <div class="overflow-hidden rounded-2xl border border-slate-200">
                        <div class="aspect-[16/9] w-full overflow-hidden bg-slate-100">
                            <img
                                src="{{ $course->image ? asset('storage/'.$course?->image) : asset('storage/courses/Online-learning.jpg') }}"
                                alt="{{ $course->name }} cover"
                                class="h-full w-full object-cover"
                            />
                        </div>
                        <div class="px-5 py-6 sm:px-8">
                            <h1 class="text-2xl font-semibold text-slate-900">{{ $course->name }}</h1>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $course->description }}</p>

                            <div class="mt-6 rounded-2xl border border-slate-200 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-600">Instructors</h3>
                                    <span class="text-xs text-slate-500">{{ $course->courseInstructors->count() }} assigned</span>
                                </div>

                                @if($course->courseInstructors->isEmpty())
                                    <p class="mt-3 text-sm text-slate-500">No instructors assigned yet.</p>
                                @else
                                    <ul class="mt-3 space-y-2">
                                        @foreach($course->courseInstructors as $assignment)
                                            <li class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 px-3 py-2">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800">{{ $assignment->instructor?->name ?? 'Unknown user' }}</p>
                                                    <p class="text-xs text-slate-500">{{ $assignment->instructor?->email }}</p>
                                                </div>
                                                @can(\App\Enums\PermissionType::CourseInstructorDelete->value)
                                                    <x-delete-modal
                                                        :action="route('instructors-assignment.destroy', $assignment)"
                                                        :message="'Unassign ' . ($assignment->instructor?->name ?? 'this instructor') . ' from this course?'"
                                                        label="Unassign" />
                                                @endcan
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                @can(\App\Enums\PermissionType::CourseInstructorCreate->value)
                                    <form method="POST" action="{{ route('instructors-assignment.store', $course->slug) }}" class="mt-4 flex flex-wrap items-end gap-3">
                                        @csrf
                                        <div class="min-w-[260px] flex-1">
                                            <x-input-label for="user_id" value="Assign instructor" />
                                            <select id="user_id" name="user_id" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500">
                                                <option value="">Select instructor</option>
                                                @foreach($availableInstructors as $instructor)
                                                    <option value="{{ $instructor->id }}" {{ (string) old('user_id') === (string) $instructor->id ? 'selected' : '' }}>
                                                        {{ $instructor->name }} ({{ $instructor->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                                        </div>
                                        <x-primary-button type="submit">Assign</x-primary-button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                        <div>
                            <ul class="mt-4 space-y-2 text-sm text-slate-600">
                                @foreach($course->lessons as $lesson )
                                    <li class="rounded-xl border border-slate-200 px-4 py-3">
                                        <details class="group">
                                            <summary class="flex cursor-pointer items-center justify-between gap-3 list-none">
                                                <div>
                                                    <p class="font-medium text-slate-700">{{ $lesson->title }}</p>
                                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">
                                                        {{ $lesson->attachments->count() }} attachment{{ $lesson->attachments->count() === 1 ? '' : 's' }}
                                                    </p>
                                                </div>
                                                <span class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 group-open:text-slate-700">
                                                    <x-hyper-link href="{{ route('lessons.show', $lesson->slug) }}">
                                                        View
                                                    </x-hyper-link>
                                                    @can(\App\Enums\PermissionType::LessonDelete->value)
                                                        <x-delete-modal
                                                            :action="route('lessons.destroy', $lesson->slug)"
                                                            :message="'Delete lesson \'' . $lesson->title . '\'? This cannot be undone.'" />
                                                    @endcan
                                                </span>
                                            </summary>
                                            <div class="mt-3 border-t border-slate-200 pt-3">
                                                @if($lesson->attachments->isEmpty())
                                                    <p class="text-sm text-slate-500">No attachments.</p>
                                                @else
                                                    <ul class="space-y-2">
                                                        @foreach($lesson->attachments as $attachment)
                                                            <li class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-3 py-2">
                                                                <div>
                                                                    <p class="text-sm font-medium text-slate-700">{{ $attachment->name }}</p>
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
                                        </details>
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200 px-6 py-6 sm:px-8">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="text-sm text-slate-500">
                            Updated {{ $course->updated_at->diffForHumans() }}
                        </div>
                        <div class="flex items-center gap-3">
                           @can(\App\Enums\PermissionType::EnrollmentCreate)
                                <form method="post" action="{{route('enrollments.store')}}">
                                    @csrf
                                    <input type="hidden" value="{{$course->slug}}" name="course_slug">
                                    <x-primary-button type="submit">Enroll</x-primary-button>
                                </form>
                           @endcan
                            @if(auth()->user()->hasRole('admin'))
                                   <x-hyper-link href="{{route('courses.edit',$course->slug)}}">Edit</x-hyper-link>
                                <x-delete-modal
                                    :action="route('courses.destroy', $course->slug)"
                                    :message="'Delete course \'' . $course->name . '\'? This cannot be undone.'" />
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
