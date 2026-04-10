<?php

namespace App\Http\Requests;

use App\Enums\PermissionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateLessonRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'objectives' => $this->input('objectives', $this->input('learning_obj')),
            'resources' => $this->input('resources', $this->input('links')),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows(PermissionType::LessonUpdate);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2'],
            'description' => ['required', 'string', 'max:255', 'min:2'],
            'duration' => ['required', 'integer', 'min:1'],
            'objectives' => ['nullable', 'string', 'min:2', 'max:255'],
            'resources' => ['nullable', 'string', 'min:2', 'max:255'],
            'learning_obj' => ['nullable', 'string', 'min:2', 'max:255'],
            'links' => ['nullable', 'string', 'min:2', 'max:255'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['nullable', 'mimes:doc,docx,pdf,txt', 'max:10240'],
            'video' => ['nullable', 'mimes:mp4', 'max:102400'],
        ];
    }
}
