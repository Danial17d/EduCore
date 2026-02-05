@php
    $experiences = $user->experiences ?? collect();
    $certificates = $user->certificates ?? collect();
@endphp

<div class="space-y-10">
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 sm:p-8">
        <div>
            <h3 class="text-lg font-semibold text-slate-900">Experience</h3>
            <p class="text-sm text-slate-600">Your roles and impact.</p>
        </div>

        <div class="mt-6 space-y-4">
            @forelse($experiences as $experience)
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="text-base font-semibold text-slate-900">{{ $experience->title }}</div>
                            <div class="text-sm text-slate-600">
                                {{ $experience->company }}
                                @if($experience->employment_type)
                                    - {{ $experience->employment_type }}
                                @endif
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ optional($experience->start_date)->format('M Y') }}
                                -
                                {{ $experience->is_current ? 'Present' : optional($experience->end_date)->format('M Y') }}
                                @if($experience->location)
                                    - {{ $experience->location }}
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($experience->description)
                        <p class="mt-3 text-sm text-slate-700">{{ $experience->description }}</p>
                    @endif
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-4 text-sm text-slate-500">
                    No experience added yet.
                </div>
            @endforelse
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 sm:p-8">
        <div>
            <h3 class="text-lg font-semibold text-slate-900">Certificates</h3>
            <p class="text-sm text-slate-600">
                Issued automatically by {{ config('app.name', 'EduCore') }} when you complete a course.
            </p>
        </div>

        <div class="mt-6 space-y-4">
            @forelse($certificates as $certificate)
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="text-base font-semibold text-slate-900">{{ $certificate->name }}</div>
                            <div class="text-sm text-slate-600">
                                {{ $certificate->issuer ?? 'Issuer not specified' }}
                            </div>
                            <div class="text-xs text-slate-500">
                                @if($certificate->issue_date)
                                    Issued {{ $certificate->issue_date->format('M Y') }}
                                @endif
                                @if($certificate->expiry_date)
                                    - Expires {{ $certificate->expiry_date->format('M Y') }}
                                @endif
                            </div>
                            @if($certificate->credential_id)
                                <div class="text-xs text-slate-500">ID: {{ $certificate->credential_id }}</div>
                            @endif
                            @if($certificate->credential_url)
                                <a href="{{ $certificate->credential_url }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900" target="_blank" rel="noopener">
                                    View credential
                                </a>
                            @endif
                        </div>
                    </div>
                    @if($certificate->description)
                        <p class="mt-3 text-sm text-slate-700">{{ $certificate->description }}</p>
                    @endif
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-4 text-sm text-slate-500">
                    No certificates added yet.
                </div>
            @endforelse
        </div>

        <div class="mt-6 rounded-xl border border-dashed border-slate-300 bg-white p-4 text-sm text-slate-500">
            Certificates are generated from completed courses and cannot be edited here.
        </div>
    </div>
</div>
