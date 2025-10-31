<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
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
        $moduleId = $this->route('module') ? $this->route('module')->id : null;
        
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:1|max:999',
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
            'title.required' => 'The module title is required.',
            'title.max' => 'The module title may not be greater than 255 characters.',
            'description.max' => 'The module description may not be greater than 1000 characters.',
            'order.required' => 'The module order is required.',
            'order.integer' => 'The module order must be a number.',
            'order.min' => 'The module order must be at least 1.',
            'order.max' => 'The module order may not be greater than 999.',
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
            'title' => 'module title',
            'description' => 'module description',
            'order' => 'module order',
        ];
    }
}
