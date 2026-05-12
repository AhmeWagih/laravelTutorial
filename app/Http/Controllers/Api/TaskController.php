<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreApiTaskRequest;
use App\Http\Requests\Api\UpdateApiTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        $tasks = Tasks::query()
            ->with(['creator'])
            ->latest()
            ->paginate($perPage);

        return TaskResource::collection($tasks);
    }

    public function store(StoreApiTaskRequest $request)
    {
        $validated = $request->validated();
        $creator = $request->user();
        $assignee = User::find($validated['assignee_id']);

        $task = Tasks::create([
            ...$validated,
            'creator_id' => $creator->id,
        ]);

        $task->load('creator');

        return (new TaskResource($task))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Tasks $task)
    {
        $task->loadMissing('creator');

        return new TaskResource($task);
    }

    public function update(UpdateApiTaskRequest $request, Tasks $task)
    {
        $validated = $request->validated();
        $creator = User::find($task->creator_id);
        $assignee = User::find($validated['assignee_id']);

        $task->update($validated);

        $task->loadMissing('creator');

        return new TaskResource($task);
    }

    public function destroy(Tasks $task)
    {
        $task->loadMissing('taskImages');

        foreach ($task->taskImages as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $task->taskImages()->delete();
        $task->delete();

        return response()->noContent();
    }
}
