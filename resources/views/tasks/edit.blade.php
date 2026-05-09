@extends('layout.app')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Task Editor</p>
                <h1 class="mt-1 text-3xl font-black tracking-tight text-slate-900">Edit Task #{{ $task->id }}</h1>
                <p class="mt-2 text-sm text-slate-500">Update task details and keep your board organized.</p>
            </div>
            <a href="{{ route('tasks.show', $task->id) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50">
                View Task
            </a>
        </div>

        <form method="POST" action="{{ route('tasks.update', $task->id) }}" class="space-y-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label for="title" class="block text-sm font-semibold text-slate-700">Title</label>
                <input type="text" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 placeholder-slate-400 transition focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20" id="title" name="title" value="{{ $task->title }}" required>
            </div>

            <div class="space-y-2">
                <label for="description" class="block text-sm font-semibold text-slate-700">Description</label>
                <textarea class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 placeholder-slate-400 transition focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20" id="description" name="description" rows="5">{{ $task->description }}</textarea>
            </div>

            <div class="space-y-2">
                <label for="user_id" class="block text-sm font-semibold text-slate-700">Assigned To</label>
                <select id="user_id" name="user_id" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 transition focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ (int) $task->user_id === (int) $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="due_date" class="block text-sm font-semibold text-slate-700">Due Date</label>
                    <input type="date" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 transition focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20" id="due_date" name="due_date" value="{{ $task->due_date ?? '' }}">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="priority" class="block text-sm font-semibold text-slate-700">Priority</label>
                    <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 transition focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20" id="priority" name="priority">
                        <option value="high" {{ ($task->priority ?? '') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ ($task->priority ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ ($task->priority ?? '') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label for="board_column" class="block text-sm font-semibold text-slate-700">Board Column</label>
                    <input type="text" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 placeholder-slate-400 transition focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20" id="board_column" name="board_column" value="{{ $task->board_column ?? '' }}">
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                <x-button href="{{ route('tasks.index') }}" type="secondary" button-type="button">Cancel</x-button>
                <x-button type="primary">Update Task</x-button>
            </div>
        </form>
    </div>
@endsection
