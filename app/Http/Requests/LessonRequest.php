<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:video,reading,audio,interactive',
            'content_url' => 'nullable|url|max:500',
            'content_text' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1|max:999',
            'order' => 'required|integer|min:1|max:999',
            'is_free' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The lesson title is required.',
            'title.max' => 'The lesson title may not be greater than 255 characters.',
            'description.max' => 'The lesson description may not be greater than 1000 characters.',
            'type.required' => 'The lesson type is required.',
            'type.in' => 'The lesson type must be one of: video, reading, audio, interactive.',
            'content_url.url' => 'The content URL must be a valid URL.',
            'content_url.max' => 'The content URL may not be greater than 500 characters.',
            'duration_minutes.integer' => 'The duration must be a number.',
            'duration_minutes.min' => 'The duration must be at least 1 minute.',
            'duration_minutes.max' => 'The duration may not be greater than 999 minutes.',
            'order.required' => 'The lesson order is required.',
            'order.integer' => 'The lesson order must be a number.',
            'order.min' => 'The lesson order must be at least 1.',
            'order.max' => 'The lesson order may not be greater than 999.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'lesson title',
            'description' => 'lesson description',
            'type' => 'lesson type',
            'content_url' => 'content URL',
            'content_text' => 'content text',
            'duration_minutes' => 'duration',
            'order' => 'lesson order',
            'is_free' => 'free status',
        ];
    }
}
