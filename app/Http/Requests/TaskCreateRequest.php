<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class TaskCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date|after_or_equal:today',
            'status'      => ['required', Rule::in(Task::STATUSES)],
            'priority'    => ['required', Rule::in(Task::PRIORITIES)],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'     => 'The title field is required.',
            'title.max'          => 'The title must not exceed 255 characters.',
            'due_date.date'      => 'The due date must be a valid date.',
            'due_date.after_or_equal' => 'The due date must be today or a future date.',
            'status.in'          => 'The status must be one of: Todo, In Progress, Done.',
            'priority.in'        => 'The priority must be one of: Low, Medium, High.',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $validator->errors()->first(),
            'code' => 422,
        ], 422));
    }
}
