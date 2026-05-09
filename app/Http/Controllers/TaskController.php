<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller     
{
    public function index()
    {
        $tasks = Tasks::withTrashed()->with('user')->latest()->paginate(10);
        return view("tasks.index", ["tasks" => $tasks]);
    }

    public function create()
    {
        $users = User::all();
        return view("tasks.create", compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'assigned_to' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'nullable|in:open,pending,reviewing,closed',
            'board_column' => 'nullable|string|max:255',
        ]);

        Tasks::create($data);
        return redirect()->route("tasks.index");
    }

    public function show($id)
    {
        $task = Tasks::findOrFail($id);
        return view("tasks.show", ["task" => $task]);
    }

    public function edit($id)
    {
        $task = Tasks::findOrFail($id);
        $users = User::all();
        return view("tasks.edit", compact('task', 'users'));
    }

    public function update(Request $request, $id)
    {
        $task = Tasks::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'assigned_to' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'nullable|in:open,pending,reviewing,closed',
            'board_column' => 'nullable|string|max:255',
        ]);

        $task->update($data);
        return redirect('/');
    }

    public function destroy($id)
    {
        Tasks::findOrFail($id)->delete();
        return redirect()->route("tasks.index");
    }

    public function restore($id)
    {
        $task = Tasks::withTrashed()->findOrFail($id);

        if ($task->trashed()) {
            $task->restore();
        }

        return redirect()->route("tasks.index");
    }

    public function forceDelete($id)
    {
        $task = Tasks::withTrashed()->findOrFail($id);
        $task->forceDelete();
        return redirect()->route("tasks.index");
    }

}