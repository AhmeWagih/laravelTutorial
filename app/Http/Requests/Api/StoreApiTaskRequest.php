<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreApiTaskRequest extends FormRequest
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
            'assignee_id' => ['required', 'exists:users,id'],
        ];
    }
}
