<?php

namespace App\Http\Requests\Api;

use App\Models\Tasks;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApiTaskRequest extends FormRequest
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
        /** @var Tasks|null $task */
        $task = $this->route('task');

        return [
            'title' => [
                'required',
                'string',
                'min:3',
                Rule::unique('tasks', 'title')->ignore($task?->getKey()),
            ],
            'description' => ['required', 'string', 'min:10'],
            'due_date' => ['required', 'date'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'status' => ['required', 'in:to-do,in_progress,done'],
            'assignee_id' => ['required', 'exists:users,id'],
        ];
    }
}
