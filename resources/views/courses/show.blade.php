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

    @php
        $imageUrl = $course->image;

        if (!$imageUrl) {
            $imageUrl = $placeholderUrl;
        }
        elseif (!\Illuminate\Support\Str::startsWith($imageUrl, ['http://', 'https://'])) {
            $imageUrl = \Illuminate\Support\Str::startsWith($imageUrl, 'storage/')
                ? asset($imageUrl)
                : Storage::url($imageUrl);
        }

    @endphp

    <div class="py-10">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-200 bg-white shadow-xl">
                <div class="border-b border-slate-200 px-6 py-5 sm:px-8">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="rounded-full bg-slate-900/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white">
                            {{ $course->status }}
                        </span>
                        <span class="text-sm text-slate-500">{{ $course->credit }} credits</span>
                        @if($course->category)
                            <span class="text-sm text-slate-500">Category: {{ $course->category->name }}</span>
                        @endif
                    </div>
                </div>

                <div class="px-6 py-8 sm:px-8">
                    <div class="overflow-hidden rounded-2xl border border-slate-200">
                        <div class="aspect-[16/9] w-full overflow-hidden bg-slate-100">
                            <img
                                src="{{ $imageUrl }}"
                                alt="{{ $course->name }} cover"
                                class="h-full w-full object-cover"
                                onerror="this.onerror=null;this.src='{{ $placeholderUrl }}';"
                            />
                        </div>
                        <div class="px-5 py-6 sm:px-8">
                            <h1 class="text-2xl font-semibold text-slate-900">{{ $course->name }}</h1>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $course->description }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200 px-6 py-6 sm:px-8">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="text-sm text-slate-500">
                            Updated {{ $course->updated_at->diffForHumans() }}
                        </div>
                        <div class="flex items-center gap-3">
                            <x-hyper-link href="{{route('courses.edit',$course->slug)}}">Edit</x-hyper-link>
                            <x-primary-button type="button">Enroll</x-primary-button>
                            @if(auth()->user()->hasRole('admin'))
                                <form method="post" action="{{ route('courses.destroy', $course->slug) }}" onsubmit="return confirm('Delete this course? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button type="submit" class="px-4 py-3" >
                                        Delete
                                    </x-danger-button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
