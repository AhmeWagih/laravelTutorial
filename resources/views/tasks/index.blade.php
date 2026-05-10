<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('All Tasks') }}
    </h2>
  </x-slot>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1">My Todo Tasks</h3>
    <p class="text-muted mb-0">See all tasks with creator and assignee details.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
      + Create Task
    </a>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Slug</th>
            <th>Title</th>
            <th>Creator</th>
            <th>Assignee</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($tasks as $task)
          <tr class="{{ $task->trashed() ? 'table-danger' : '' }}">
            <td>{{ $task->id }}</td>
            <td><code>{{ $task->slug }}</code></td>
            <td class="fw-semibold">{{ $task->title }}</td>
            <td>{{ $task->creator ?? 'N/A' }}</td>
            <td>{{ $task->assignee?->name ?? 'N/A' }}</td>
            <td>
              <span
                class="badge {{ $task->priority === 'urgent' ? 'text-bg-danger' : ($task->priority === 'high' ? 'text-bg-warning' : ($task->priority === 'medium' ? 'text-bg-info' : 'text-bg-secondary')) }}">
                {{ ucfirst($task->priority) }}
              </span>
            </td>
            <td>
              @if ($task->trashed())
              <span class="badge text-bg-danger">Deleted</span>
              @else
              <span
                class="badge {{ ($task->status ?? 'to-do') === 'done' ? 'text-bg-success' : (($task->status ?? 'to-do') === 'in_progress' ? 'text-bg-primary' : 'text-bg-secondary') }}">
                {{ $task->status ?? 'to-do' }}
              </span>
              @endif
            </td>
            <td>{{ $task->created_at->format('M d, Y') }}</td>
            <td>
              <div class="d-flex flex-wrap gap-1">
                @if ($task->trashed())
                <form action="{{ route('tasks.restore', $task->id) }}" method="POST"
                  onsubmit="return confirm('Restore this deleted task?');">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-success btn-sm">Restore</button>
                </form>
                <form action="{{ route('tasks.forceDelete', $task->id) }}" method="POST"
                  onsubmit="return confirm('Permanently delete this task? This cannot be undone.');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
                @else
                <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-secondary btn-sm">View</a>
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this task?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center text-muted py-4">No tasks yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="mt-3">
  {{ $tasks->links() }}
</div>
</x-app-layout>