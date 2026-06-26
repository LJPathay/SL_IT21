<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreModuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'duration_minutes' => 'required|integer|min:1|max:10080',
            'course_id' => 'nullable|exists:courses,id',
            'lesson_count' => 'required|integer|min:0',
            'required_roles' => 'nullable|array',
            'required_roles.*' => 'in:student,instructor,admin',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Module title is required.',
            'description.required' => 'Module description is required.',
            'category.required' => 'Module category is required.',
            'difficulty.required' => 'Module difficulty is required.',
            'difficulty.in' => 'Difficulty must be beginner, intermediate, or advanced.',
            'duration_minutes.required' => 'Duration is required.',
            'duration_minutes.min' => 'Duration must be at least 1 minute.',
            'duration_minutes.max' => 'Duration cannot exceed 10080 minutes (7 days).',
            'course_id.exists' => 'The selected course does not exist.',
            'lesson_count.required' => 'Lesson count is required.',
            'lesson_count.min' => 'Lesson count cannot be negative.',
            'required_roles.*.in' => 'Role must be student, instructor, or admin.',
            'order.min' => 'Order cannot be negative.',
        ];
    }
}
