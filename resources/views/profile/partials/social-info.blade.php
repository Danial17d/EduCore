@php
    $experiences = $user->experiences ?? collect();
    $certificates = $user->certificates ?? collect();
@endphp

<div class="space-y-10">
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 sm:p-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Experience</h3>
                <p class="text-sm text-slate-600">Show your roles and impact like a LinkedIn timeline.</p>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            @forelse($experiences as $experience)
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-start">
                        <div>
                            <div class="text-base font-semibold text-slate-900">{{ $experience->title }}</div>
                            <div class="text-sm text-slate-600">
                                {{ $experience->company }}
                                @if($experience->employment_type)
                                    · {{ $experience->employment_type }}
                                @endif
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ optional($experience->start_date)->format('M Y') }}
                                -
                                {{ $experience->is_current ? 'Present' : optional($experience->end_date)->format('M Y') }}
                                @if($experience->location)
                                    · {{ $experience->location }}
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                class="text-xs font-semibold text-slate-600 hover:text-slate-900"
                                data-experience-edit
                                data-experience-route="{{ route('experience.update', $experience) }}"
                                data-experience-title="{{ e($experience->title) }}"
                                data-experience-company="{{ e($experience->company) }}"
                                data-experience-location="{{ e($experience->location) }}"
                                data-experience-employment-type="{{ e($experience->employment_type) }}"
                                data-experience-start-date="{{ optional($experience->start_date)->format('Y-m-d') }}"
                                data-experience-end-date="{{ optional($experience->end_date)->format('Y-m-d') }}"
                                data-experience-is-current="{{ $experience->is_current ? '1' : '0' }}"
                                data-experience-description="{{ e($experience->description) }}"
                            >
                                Update
                            </button>
                            <x-delete-modal
                                :action="route('experience.destroy', $experience)"
                                :message="'Delete \'' . $experience->title . '\' at ' . $experience->company . '?'"
                                label="Delete" />
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

        <div class="mt-6 hidden rounded-xl border border-slate-200 bg-white p-4" data-experience-edit-form>
            <h4 class="text-sm font-semibold text-slate-900">Update experience</h4>
            <form class="mt-4 grid gap-4" method="post" action="">
                @csrf
                @method('PATCH')
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label value="Title" />
                        <x-text-input name="title" placeholder="Frontend Developer" />
                    </div>
                    <div>
                        <x-input-label value="Company" />
                        <x-text-input name="company" placeholder="EduCore" />
                    </div>
                    <div>
                        <x-input-label value="Location" />
                        <x-text-input name="location" placeholder="Saudi" />
                    </div>
                    <div>
                        <x-input-label value="Employment Type" />
                        <x-text-input name="employment_type" placeholder="Full-time" />
                    </div>
                    <div>
                        <x-input-label value="Start Date" />
                        <x-text-input name="start_date" type="date" />
                    </div>
                    <div>
                        <x-input-label value="End Date" />
                        <x-text-input name="end_date" type="date" />
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input id="experience_is_current" name="is_current" type="checkbox" value="1" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                    <label for="experience_is_current" class="text-sm text-slate-600">I currently work here</label>
                </div>
                <div>
                    <x-input-label value="Description" />
                    <textarea name="description" rows="3" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900"></textarea>
                </div>
                <div class="flex items-center gap-3">
                    <x-primary-button>Update experience</x-primary-button>
                    <button type="button" class="text-xs font-semibold text-slate-600 hover:text-slate-900" data-experience-edit-cancel>Cancel</button>
                </div>
            </form>
        </div>

        <form class="mt-6 grid gap-4 rounded-xl border border-slate-200 bg-white p-4" method="post" action="{{ route('experience.store') }}">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label value="Title" />
                    <x-text-input name="title" placeholder="Frontend Developer" />
                </div>
                <div>
                    <x-input-label value="Company" />
                    <x-text-input name="company" placeholder="EduCore" />
                </div>
                <div>
                    <x-input-label value="Location" />
                    <x-text-input name="location" placeholder="Saudi Arabia" />
                </div>
                <div>
                    <x-input-label value="Employment Type" />
                    <x-text-input name="employment_type" placeholder="Full-time" />
                </div>
                <div>
                    <x-input-label value="Start Date" />
                    <x-text-input name="start_date" type="date" />
                </div>
                <div>
                    <x-input-label value="End Date" />
                    <x-text-input name="end_date" type="date" />
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input id="is_current" name="is_current" type="checkbox" value="1" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                <label for="is_current" class="text-sm text-slate-600">I currently work here</label>
            </div>
            <div>
                <x-input-label value="Description" />
                <textarea name="description" rows="3" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900"></textarea>
            </div>
            <div>
                <x-primary-button>Add experience</x-primary-button>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 sm:p-8">
        <div>
            <h3 class="text-lg font-semibold text-slate-900">Certificates</h3>
            <p class="text-sm text-slate-600">Issued automatically by {{ config('app.name', 'EduCore') }} when you complete a course.</p>
        </div>

        <div class="mt-6 space-y-4">
            @forelse($certificates as $certificate)
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-start">
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
                                    · Expires {{ $certificate->expiry_date->format('M Y') }}
                                @endif
                            </div>
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
            Certificates are generated from completed courses and can’t be edited here.
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editWrapper = document.querySelector('[data-experience-edit-form]');
        if (!editWrapper) {
            return;
        }

        var form = editWrapper.querySelector('form');
        var endDateInput = form.querySelector('[name="end_date"]');
        var isCurrentInput = form.querySelector('[name="is_current"]');
        var cancelButton = form.querySelector('[data-experience-edit-cancel]');

        var setEndDateState = function () {
            if (isCurrentInput.checked) {
                endDateInput.value = '';
                endDateInput.disabled = true;
            } else {
                endDateInput.disabled = false;
            }
        };

        isCurrentInput.addEventListener('change', setEndDateState);

        document.querySelectorAll('[data-experience-edit]').forEach(function (button) {
            button.addEventListener('click', function () {
                form.action = button.dataset.experienceRoute || '';
                form.querySelector('[name="title"]').value = button.dataset.experienceTitle || '';
                form.querySelector('[name="company"]').value = button.dataset.experienceCompany || '';
                form.querySelector('[name="location"]').value = button.dataset.experienceLocation || '';
                form.querySelector('[name="employment_type"]').value = button.dataset.experienceEmploymentType || '';
                form.querySelector('[name="start_date"]').value = button.dataset.experienceStartDate || '';
                form.querySelector('[name="end_date"]').value = button.dataset.experienceEndDate || '';
                form.querySelector('[name="description"]').value = button.dataset.experienceDescription || '';

                isCurrentInput.checked = button.dataset.experienceIsCurrent === '1';
                setEndDateState();

                editWrapper.classList.remove('hidden');
                editWrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        cancelButton.addEventListener('click', function () {
            form.reset();
            endDateInput.disabled = false;
            editWrapper.classList.add('hidden');
        });
    });
</script>

