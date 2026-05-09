@extends('layout.app')

@section('content')
  <div class="mb-6 flex items-end justify-between gap-4">
    <div>
      <h1 class="text-3xl font-black tracking-tight text-slate-900">Tasks</h1>
      <p class="mt-1 text-sm text-slate-500">Track all tasks and quickly manage their status.</p>
    </div>
  </div>

  <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-slate-200 text-left">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">ID</th>
          <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Title</th>
          <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">AssignedTo</th>
          <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Priority</th>
          <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Status</th>
          <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 bg-white">
        @forelse ($tasks as $task)
          <tr class="{{ $task->trashed() ? 'bg-rose-50/30' : 'hover:bg-slate-50/80' }}">
            <td class="px-6 py-5 text-sm font-semibold text-slate-600">{{ $task->id }}</td>
            <td class="px-6 py-5">
              <div class="max-w-88">
                <p class="font-semibold text-slate-900">{{ $task->title }}</p>
              </div>
            </td>
            <td class="px-6 py-5 text-sm font-semibold text-slate-700">
              {{ $task->user?->name }}
            </td>
            <td class="px-6 py-5">
              <span
                class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide {{ $task->priority === 'high' ? 'bg-rose-100 text-rose-700' : ($task->priority === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-sky-100 text-sky-700') }}">{{ $task->priority }}</span>
            </td>
            <td class="px-6 py-5">
              @if ($task->trashed())
                <span class="inline-flex items-center gap-2 text-sm font-semibold text-rose-600">
                  <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                  Deleted
                </span>
              @else
                <span
                  class="inline-flex items-center gap-2 text-sm font-semibold {{ $task->completed ? 'text-emerald-600' : 'text-amber-600' }}">
                  <span class="h-2.5 w-2.5 rounded-full {{ $task->completed ? 'bg-emerald-500' : 'bg-amber-400' }}"></span>
                  {{ $task->completed ? 'Yes' : 'No' }}
                </span>
              @endif
            </td>
            <td class="px-6 py-5">
              <div class="flex items-center gap-2">
                @if ($task->trashed())
                  <form action="{{ route('tasks.restore', $task->id) }}" method="POST"
                    onsubmit="return confirm('Restore this deleted task?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                      class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                      Restore
                    </button>
                  </form>
                  <form action="{{ route('tasks.forceDelete', $task->id) }}" method="POST"
                    onsubmit="return confirm('Permanently delete this task? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="inline-flex items-center justify-center gap-2 rounded-lg border border-rose-600 bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:border-rose-700 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                      Delete
                    </button>
                  </form>
                @else
                  <x-button href="{{ route('tasks.show', $task->id) }}" type="secondary" button-type="button">
                    View
                  </x-button>
                  <x-button href="{{ route('tasks.edit', $task->id) }}" type="secondary" button-type="button">
                    Edit
                  </x-button>
                  <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this task? Click YES to confirm or NO to cancel.');">
                    @csrf
                    @method('DELETE')
                    <x-button type="danger">Delete</x-button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500">
              No tasks yet. Click <span class="font-semibold text-slate-700">Add Task</span> to create your first one.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination Links -->
  <div class="mt-8">
    {{ $tasks->links() }}
  </div>
@endsection