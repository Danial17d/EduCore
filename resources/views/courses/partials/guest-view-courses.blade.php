<x-guest-layout>
        <div class="mx-auto w-full max-w-6xl px-4 pb-12 pt-10 sm:px-6 lg:px-8">
            <section class="relative overflow-hidden rounded-3xl px-6 py-10 text-white shadow-2xl sm:px-10 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,0.25),transparent_55%),radial-gradient(circle_at_80%_20%,rgba(14,165,233,0.3),transparent_50%),linear-gradient(135deg,#0b1120_0%,#0f172a_40%,#111827_100%)]">
                <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml;utf8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2760%27 height=%2760%27 viewBox=%270 0 60 60%27%3E%3Cpath d=%27M0 0h60v60H0z%27 fill=%27none%27/%3E%3Cpath d=%27M0 30h60M30 0v60%27 stroke=%27%23ffffff%27 stroke-opacity=%270.15%27/%3E%3C/svg%3E');"></div>
                <div class="relative z-10 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-sky-200">EduCore Courses</p>
                        <h1 class="mt-3 text-3xl font-semibold sm:text-4xl">Build your skills with focused, real-world courses.</h1>
                        <p class="mt-4 max-w-2xl text-sm text-slate-200 sm:text-base">
                            Browse curated learning paths, track credits, and dive into content that helps you grow.
                        </p>
                    </div>
                    <div class="relative rounded-2xl px-5 py-4 text-slate-900 shadow-lg bg-[linear-gradient(180deg,rgba(255,255,255,0.98)_0%,rgba(248,250,252,0.98)_100%)]">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Available</p>
                        <p class="mt-2 text-3xl font-semibold text-center">{{ $courses->count() }}</p>
                        <p class="mt-1 text-sm text-slate-500">Active courses</p>
                    </div>
                </div>
            </section>

            <section class="mt-10 space-y-10">
                @forelse ($coursesByCategory as $categoryName => $categoryCourses)
                    <div>
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-slate-900">{{ $categoryName }}</h2>
                            <span class="text-sm text-slate-500">{{ count($categoryCourses) }} courses</span>
                        </div>
                        <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($categoryCourses as $course)
                                @php
                                    $imageUrl = $course->image;

                                    if (!$imageUrl) {
                                        $imageUrl = $placeholderUrl;
                                    }
                                    elseif (!\Illuminate\Support\Str::startsWith($imageUrl, ['http://', 'https://', '//', 'data:'])) {
                                        $imageUrl = \Illuminate\Support\Str::startsWith($imageUrl, 'storage/')
                                            ? asset($imageUrl)
                                            : Storage::url($imageUrl);
                                    }

                                @endphp
                                <article class="group flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-2xl">
                                    <div class="relative aspect-[4/3] overflow-hidden">
                                        <img src="{{ $imageUrl }}" alt="{{ $course->name }} cover" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" onerror="this.onerror=null;this.src='{{ $placeholderUrl }}';">
                                        @if (!$course->image)
                                            <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-700">Default cover</span>
                                        @endif
                                        <span class="absolute right-4 top-4 rounded-full bg-slate-900/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-100">
                                        {{ $course->status }}
                                    </span>
                                    </div>
                                    <div class="flex flex-1 flex-col gap-3 px-5 pb-6 pt-5">
                                        <div class="flex items-center justify-between text-xs uppercase tracking-[0.2em] text-slate-400">
                                            <span>{{ optional($course->category)->name ?? 'General' }}</span>
                                            <span>{{ $course->code }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <h2 class="text-xl font-semibold text-slate-900">{{ $course->name }}</h2>
                                            <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($course->description, 120) }}</p>
                                        </div>
                                        <div class="mt-auto space-y-3">
                                            <div class="flex items-center justify-between text-sm font-semibold text-slate-700">
                                                <span>{{ $course->credit }} credits</span>
                                            </div>
                                            <div>
                                                <x-hyper-link href="{{route('courses.store')}}">Enroll</x-hyper-link>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-600 shadow-sm">
                        <p class="text-lg font-semibold text-slate-900">No courses yet.</p>
                        <p class="mt-2 text-sm">Add courses to the database to see them listed here.</p>
                    </div>
                @endforelse
            </section>
        </div>
</x-guest-layout>
