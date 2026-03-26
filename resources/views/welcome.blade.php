<x-guest-layout>
    <div class="w-full min-h-screen">
        <div class="w-full px-6 py-10 sm:px-12 lg:px-16">
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3 text-lg font-semibold text-slate-900">
                    <div class="h-9 w-9 rounded-lg bg-slate-900"></div>
                    <a href="/">EduCore</a>
                </div>
                <nav class="flex gap-4 text-sm text-slate-600">
                    <a href="{{route('courses.index')}}" class="hover:text-blue-500">Course</a>
                </nav>
            </header>

            <section class="mt-10 grid gap-8 sm:grid-cols-2 sm:items-center">
                <div>
                    <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">
                        Learn, finish, and get certified.
                    </h1>
                    <p class="mt-3 text-base text-slate-600">
                        EduCore helps you learn with clear paths and automatic certificates when you complete courses.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white" href="{{ route('login') }}">
                            Get Started
                        </a>
                        @if (Route::has('register'))
                            <a class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700" href="{{ route('register') }}">
                                Create Account
                            </a>
                        @endif
                        <a>

                        </a>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Simple progress</h3>
                    <p class="mt-2 text-sm text-slate-600">
                        Finish a course and your certificate appears on your profile automatically.
                    </p>
                    <div class="mt-4 grid gap-3 text-sm text-slate-700">
                        <div class="rounded-lg bg-slate-50 p-3">Short lessons</div>
                        <div class="rounded-lg bg-slate-50 p-3">Mentor feedback</div>
                        <div class="rounded-lg bg-slate-50 p-3">Verified certificates</div>
                    </div>
                </div>
            </section>

            <section class="mt-10 grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <h4 class="text-base font-semibold text-slate-900">Clear paths</h4>
                    <p class="mt-2 text-sm text-slate-600">Pick a track and follow simple steps.</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <h4 class="text-base font-semibold text-slate-900">Real progress</h4>
                    <p class="mt-2 text-sm text-slate-600">Track your learning in one place.</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <h4 class="text-base font-semibold text-slate-900">Auto certificates</h4>
                    <p class="mt-2 text-sm text-slate-600">Issued by the platform when you finish.</p>
                </div>
            </section>

            <section class="mt-10 rounded-2xl bg-slate-900 px-6 py-8 text-white">
                <h3 class="text-xl font-semibold">Start learning today</h3>
                <p class="mt-2 text-sm text-slate-200">Create your account and build your profile.</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-900" href="{{ route('login') }}">
                        Enter Dashboard
                    </a>
                    @if (Route::has('register'))
                        <a class="rounded-lg border border-white/30 px-4 py-2 text-sm font-semibold text-white" href="{{ route('register') }}">
                            Join Free
                        </a>
                    @endif
                </div>
            </section>

        </div>
    </div>
</x-guest-layout>
