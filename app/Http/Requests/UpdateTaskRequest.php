<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
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
        $task = $this->route('task');
        $taskId = is_object($task) ? $task->id : $task;

        return [
            'title' => ['required', 'string', 'min:3', Rule::unique('tasks', 'title')->ignore($taskId)],
            'description' => ['required', 'string', 'min:10'],
            'due_date' => ['required', 'date'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'status' => ['required', 'in:to-do,in_progress,done'],
            'creator_id' => ['required', 'exists:users,id'],
            'assignee_id' => ['required', 'exists:users,id'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png'],
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
            'images.array' => 'Images must be uploaded as a list of files.',
            'images.*.image' => 'Each uploaded file must be an image.',
            'images.*.mimes' => 'Only JPG and PNG images are allowed.',
        ];
    }
}
