<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
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
            'code' => 'required|string|max:50|unique:courses,code,' . $this->course->id,
            'description' => 'required|string',
            'instructor_id' => 'required|exists:users,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'capacity' => 'required|integer|min:1|max:1000',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Course title is required.',
            'code.required' => 'Course code is required.',
            'code.unique' => 'This course code already exists.',
            'description.required' => 'Course description is required.',
            'instructor_id.required' => 'An instructor must be assigned.',
            'instructor_id.exists' => 'The selected instructor does not exist.',
            'level.required' => 'Course level is required.',
            'level.in' => 'Course level must be beginner, intermediate, or advanced.',
            'capacity.required' => 'Course capacity is required.',
            'capacity.min' => 'Capacity must be at least 1.',
            'capacity.max' => 'Capacity cannot exceed 1000.',
            'start_date.after_or_equal' => 'Start date must be today or in the future.',
            'end_date.after' => 'End date must be after the start date.',
        ];
    }
}
