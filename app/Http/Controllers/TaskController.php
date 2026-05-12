<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\UploadImageTrait;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    use UploadImageTrait;

    public function index()
    {
        $tasks = Tasks::withTrashed()
            ->with(['creator', 'assignee', 'taskComments.user', 'taskImages'])
            ->latest()
            ->paginate(10);
        return view("tasks.index", ["tasks" => $tasks]);
    }

    public function create()
    {
        $users = User::all();
        return view("tasks.create", compact('users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $images = $request->file('images', []);
        unset($validated['images']);
        $creator = User::find($validated['creator_id']);
        $assignee = User::find($validated['assignee_id']);

        $task = Tasks::create($validated);

        $this->storeTaskImages($task, $images);

        return redirect()
            ->route("tasks.index")
            ->with('success', 'Task created successfully.');
    }

    public function show(Tasks $task)
    {
        $task->load(['creator', 'assignee', 'taskComments.user', 'taskImages']);
        $users = User::select('id', 'name')->get();

        return view("tasks.show", compact('task', 'users'));
    }

    public function edit(Tasks $task)
    {
        $task->load('taskImages');
        $users = User::all();
        return view("tasks.edit", compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, Tasks $task)
    {
        $validated = $request->validated();
        $images = $request->file('images', []);
        unset($validated['images']);
        $creator = User::find($validated['creator_id']);
        $assignee = User::find($validated['assignee_id']);

        $task->update($validated);

        if (!empty($images)) {
            $this->deleteTaskImages($task);
            $this->storeTaskImages($task, $images);
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Tasks $task)
    {
        $this->deleteTaskImages($task);
        $task->delete();

        return redirect()->route("tasks.index");
    }

    public function restore(int $id)
    {
        $task = Tasks::withTrashed()->findOrFail($id);

        if ($task->trashed()) {
            $task->restore();
        }

        return redirect()->route("tasks.index");
    }

    public function forceDelete(int $id)
    {
        $task = Tasks::withTrashed()->findOrFail($id);
        $this->deleteTaskImages($task);
        $task->forceDelete();
        return redirect()->route("tasks.index");
    }

    private function storeTaskImages(Tasks $task, array $images): void
    {
        foreach ($images as $image) {
            if (!$image instanceof UploadedFile) {
                continue;
            }

            $path = $this->storeUploadedFile($image, 'tasks-images');

            if ($path === null) {
                continue;
            }

            $task->taskImages()->create([
                'path' => $path,
                'original_name' => $image->getClientOriginalName(),
            ]);
        }
    }

    private function deleteTaskImages(Tasks $task): void
    {
        $task->loadMissing('taskImages');

        foreach ($task->taskImages as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $task->taskImages()->delete();
    }
}