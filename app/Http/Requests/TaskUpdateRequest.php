<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class TaskUpdateRequest extends FormRequest
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
            'title.string'       => 'The title must be a string.',
            'title.max'          => 'The title must not exceed 255 characters.',
            'description.string' => 'The description must be a string.',
            'due_date.date'      => 'The due date must be a valid date.',
            'status.in'          => 'The selected status is invalid.',
            'priority.in'        => 'The selected priority is invalid.',
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
