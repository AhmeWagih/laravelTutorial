<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Tasks;
use App\Models\User;

class TaskController extends Controller     
{
    public function index()
    {
        $tasks = Tasks::withTrashed()
            ->with(['creator', 'assignee', 'taskComments.user'])
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
        $creator = User::find($validated['creator_id']);
        $assignee = User::find($validated['assignee_id']);

        Tasks::create($validated + [
            // Keep legacy string columns in sync (existing schema).
            'creator' => $creator?->name,
            'assigned_to' => $assignee?->name,
        ]);

        return redirect()
            ->route("tasks.index")
            ->with('success', 'Task created successfully.');
    }

    public function show(Tasks $task)
    {
        $task->load(['creator', 'assignee', 'taskComments.user']);
        $users = User::select('id', 'name')->get();

        return view("tasks.show", compact('task', 'users'));
    }

    public function edit(Tasks $task)
    {
        $users = User::all();
        return view("tasks.edit", compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, Tasks $task)
    {
        $validated = $request->validated();
        $creator = User::find($validated['creator_id']);
        $assignee = User::find($validated['assignee_id']);

        $task->update($validated + [
            // Keep legacy string columns in sync (existing schema).
            'creator' => $creator?->name,
            'assigned_to' => $assignee?->name,
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Tasks $task)
    {
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
        $task->forceDelete();
        return redirect()->route("tasks.index");
    }

}