@extends('layout.app')

@section('content')
@php
    $subtasks = is_array($task->subtasks) ? $task->subtasks : (is_string($task->subtasks) ? json_decode($task->subtasks, true) : []);
    $subtasks = is_array($subtasks) ? $subtasks : [];
    $totalSub = count($subtasks);
    $doneSub = collect($subtasks)->where('completed', true)->count();
@endphp

<div class="row justify-content-center">
    <div class="col-lg-9">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">Back to Tasks</a>
        <div>
            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-outline-primary btn-sm">
                Edit
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge {{ ($task->status ?? 'to-do') === 'done' ? 'text-bg-success' : (($task->status ?? 'to-do') === 'in_progress' ? 'text-bg-primary' : 'text-bg-secondary') }}">
                    {{ $task->status ?? 'to-do' }}
                </span>
                <span class="badge text-bg-light">#{{ $task->id }}</span>
            </div>
            <h3 class="fw-bold">{{ $task->title }}</h3>
            <div class="bg-light rounded p-3 text-muted">
                {{ $task->description ?? 'No description.' }}
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted d-block">Priority</small>
                    <strong>{{ ucfirst($task->priority) }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted d-block">Due Date</small>
                    <strong>{{ $task->due_date ? (\Carbon\Carbon::parse($task->due_date)->format('M d, Y')) : 'None' }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted d-block">Board / Order</small>
                    <strong>{{ $task->board_column ?? 'Backlog' }} ({{ $task->order ?? '0' }})</strong>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted d-block">Creator</small>
                    <strong>{{ $task->creator ?? 'N/A' }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted d-block">Assignee</small>
                    <strong>{{ $task->assignee?->name ?? 'N/A' }}</strong>
                </div>
            </div>
        </div>
    </div>

    @if($totalSub > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h6 class="mb-0">Subtasks</h6>
                    <span class="badge text-bg-secondary">{{ $doneSub }}/{{ $totalSub }}</span>
                </div>
                <ul class="list-group">
                    @foreach($subtasks as $sub)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="{{ ($sub['completed'] ?? false) ? 'text-decoration-line-through text-muted' : '' }}">
                                {{ $sub['title'] ?? 'Untitled' }}
                            </span>
                            <span class="badge {{ ($sub['completed'] ?? false) ? 'text-bg-success' : 'text-bg-light' }}">
                                {{ ($sub['completed'] ?? false) ? 'Done' : 'Open' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Comments</h5>
            <form method="POST" action="{{ route('tasks.comments.store', $task->id) }}" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label for="user_id" class="form-label">Commenter</label>
                    <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid border-danger @enderror">
                        <option value="" selected disabled>Select user</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ (int) old('user_id') === (int) $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="body" class="form-label">Comment</label>
                    <textarea id="body" name="body" rows="3" class="form-control @error('body') is-invalid border-danger @enderror">{{ old('body') }}</textarea>
                    @error('body')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                    Add Comment
                </button>
            </form>

            <div class="d-grid gap-2">
                @forelse ($task->taskComments as $comment)
                    <div class="border rounded p-3 bg-light">
                        <p class="fw-semibold mb-1">{{ $comment->user?->name ?? 'Unknown user' }}</p>
                        <p class="mb-0 text-muted">{{ $comment->body }}</p>
                    </div>
                @empty
                    <p class="text-muted mb-0">No comments yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
</div>
@endsection
