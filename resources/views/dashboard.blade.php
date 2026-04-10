<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Dashboard</p>
                <h1 class="text-lg font-semibold text-slate-900">
                    Welcome, {{ auth()->user()->name }}
                </h1>
                <p class="text-xs text-slate-500">
                    Role: {{ implode(', ', $roles) ?: 'No role assigned' }}
                </p>
            </div>
            @can(\App\Enums\PermissionType::ProfileView->value)
                <a href="{{ route('profile.show') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Manage Profile
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($cards as $card)
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">{{ $card['label'] }}</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ is_numeric($card['value']) ? number_format((float) $card['value']) : $card['value'] }}
                        </p>
                    </article>
                @endforeach
            </section>

            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('student'))
                @php
                    $creditGoal = 120;
                    $totalCredits = $creditsEarned + $creditsInProgress;
                    $earnedPercent = $creditGoal > 0 ? min(100, round(($creditsEarned / $creditGoal) * 100)) : 0;
                    $inProgressPercent = $creditGoal > 0 ? min(100 - $earnedPercent, round(($creditsInProgress / $creditGoal) * 100)) : 0;
                @endphp
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-600">Credit Progress</h2>
                        <span class="text-sm font-semibold text-slate-700">{{ $creditsEarned }} / {{ $creditGoal }} credits</span>
                    </div>
                    <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-100">
                        <div class="flex h-3 rounded-full overflow-hidden">
                            <div class="h-3 bg-slate-900 transition-all" style="width: {{ $earnedPercent }}%"></div>
                            <div class="h-3 bg-slate-400 transition-all" style="width: {{ $inProgressPercent }}%"></div>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-6 text-xs text-slate-500">
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-slate-900"></span>
                            Earned ({{ $creditsEarned }} cr)
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-slate-400"></span>
                            In Progress ({{ $creditsInProgress }} cr)
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-slate-100 border border-slate-300"></span>
                            Remaining ({{ max(0, $creditGoal - $creditsEarned - $creditsInProgress) }} cr)
                        </span>
                    </div>
                    @if($creditsEarned >= $creditGoal)
                        <p class="mt-3 text-sm font-semibold text-green-600">🎓 Graduation requirement met!</p>
                    @endif
                </section>
            @endif

            <section class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-1">
                    <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-600">Quick Actions</h2>
                    <div class="mt-4 space-y-3">
                        @forelse($quickActions as $action)
                            @can($action['permission'])
                                <a href="{{ route($action['route']) }}" class="block rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                    {{ $action['label'] }}
                                </a>
                            @endcan
                        @empty
                            <p class="text-sm text-slate-500">No actions available for your role.</p>
                        @endforelse
                    </div>
                </div>
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                    <x-recent-table :headers="['Course', 'Assigned Instructors', 'Lessons']" name="Course Teaching Overview">
                        @forelse($coursesTeachOverview as $course)
                            <tr>
                                <td class="px-4 py-3">
                                    <a href="{{ route('courses.show', $course->slug) }}" class="font-medium text-slate-800 hover:text-slate-950">
                                        {{ $course->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $course->course_instructors_count }}</td>
                                <td class="px-4 py-3">{{ $course->lessons_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-slate-500">No courses found.</td>
                            </tr>
                        @endforelse
                    </x-recent-table>
                @endif
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole('student'))
                    <x-recent-table :headers="['Courses','Credits','Date']" name="Recent Enrollments">
                        @forelse($recentEnrollments as $enrollment)
                            <tr>
                                <td class="px-4 py-3">{{ $enrollment->course?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    @if($enrollment->course?->credit)
                                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">
                                            {{ $enrollment->course->credit }} cr
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $enrollment->created_at?->diffForHumans() ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-slate-500">No enrollments yet.</td>
                            </tr>
                        @endforelse
                    </x-recent-table>
                @endif    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('instructor'))
                    <x-recent-table :headers="['Courses','Students','Date']" name="Recent Assigned Courses">
                        @forelse($recentCourses as $courses)
                            <tr>
                                <td class="px-4 py-3">{{ $courses->course?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $courses->course?->enrollments_count ?? 0 }}</td>
                                <td class="px-4 py-3">{{ $courses->created_at?->diffForHumans() ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-slate-500">No enrollments yet.</td>
                            </tr>
                        @endforelse
                </x-recent-table>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
