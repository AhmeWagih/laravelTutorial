<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'unique:tasks,title'],
            'description' => ['required', 'string', 'min:10'],
            'due_date' => ['required', 'date'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'status' => ['required', 'in:to-do,in_progress,done'],
            'creator_id' => ['required', 'exists:users,id'],
            'assignee_id' => ['required', 'exists:users,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Task title is required.',
            'title.min' => 'Task title must be at least :min characters.',
            'title.unique' => 'This title is already used by another task. Please choose a different one.',
            'description.required' => 'Task description is required.',
            'description.min' => 'Task description must be at least :min characters.',
            'due_date.required' => 'Due date is required.',
            'due_date.date' => 'Please provide a valid due date.',
            'priority.required' => 'Priority is required.',
            'priority.in' => 'Priority must be one of: low, medium, high, or urgent.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: to-do, in_progress, or done.',
            'creator_id.required' => 'Creator is required.',
            'creator_id.exists' => 'The selected creator does not exist.',
            'assignee_id.required' => 'Assignee is required.',
            'assignee_id.exists' => 'The selected assignee does not exist.',
        ];
    }
}
