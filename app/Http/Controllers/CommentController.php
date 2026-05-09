<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Tasks;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Tasks $task)
    {
        $task->taskComments()->create($request->validated());

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Comment added successfully.');
    }
}
