@extends('layout.app')

@section('content')
@php
    $subtasks = is_array($task->subtasks) ? $task->subtasks : (is_string($task->subtasks) ? json_decode($task->subtasks, true) : []);
    $subtasks = is_array($subtasks) ? $subtasks : [];
    $totalSub = count($subtasks);
    $doneSub = collect($subtasks)->where('completed', true)->count();
@endphp

<div class="mx-auto max-w-3xl">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 transition hover:text-slate-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            Back to Tasks
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('tasks.edit', $task->id) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50">
                Edit Task
            </a>
        </div>
    </div>

    <div class="rounded-3xl">
        <div class="mb-8 border-b border-slate-100 pb-8">
            <div class="mb-4 flex items-center gap-3">
                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wide {{ $task->completed ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ ucfirst($task->status ?? 'Active') }}
                </span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">#{{ $task->id }}</span>
            </div>
            <h1 class="mb-4 text-3xl font-black tracking-tight text-slate-900">{{ $task->title }}</h1>
            <div class="rounded-2xl bg-slate-50 p-4 text-sm leading-relaxed text-slate-600">
                {{ $task->description ?? 'No description.' }}
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-400">Priority</p>
                <p class="text-sm font-semibold capitalize text-slate-800">{{ $task->priority }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-400">Due Date</p>
                <p class="text-sm font-semibold text-slate-800">{{ $task->due_date ? (\Carbon\Carbon::parse($task->due_date)->format('M d, Y')) : 'None' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-400">Board / Order</p>
                <p class="text-sm font-semibold text-slate-800">{{ $task->board_column ?? 'Backlog' }} ({{ $task->order ?? '0' }})</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-400">Project</p>
                <p class="text-sm font-semibold text-slate-800">#{{ $task->project_id ?? 'N/A' }}</p>
            </div>
        </div>

        @if($totalSub > 0)
        <div class="mt-10 rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Subtasks</h3>
                <span class="rounded-full bg-white px-2.5 py-1 text-xs font-bold text-slate-500">{{ $doneSub }}/{{ $totalSub }}</span>
            </div>
            <div class="space-y-3">
                @foreach($subtasks as $sub)
                <div class="group flex items-center gap-3 rounded-lg bg-white px-3 py-2">
                    <div class="h-4 w-4 rounded-full border-2 transition-colors {{ ($sub['completed'] ?? false) ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300 group-hover:border-slate-400' }}"></div>
                    <span class="text-sm {{ ($sub['completed'] ?? false) ? 'text-slate-400 line-through' : 'font-medium text-slate-700' }}">{{ $sub['title'] ?? 'Untitled' }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
