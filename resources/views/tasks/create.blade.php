@extends('layout.app')

@section('content')
<div class="mx-auto max-w-xl">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-black tracking-tight text-slate-900">New Task</h1>
        <p class="mt-1 text-sm text-slate-500">Add a new objective to your workflow.</p>
    </div>

    <div class="rounded-[2.5rem]">
        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6">
            @csrf
            
            <div class="space-y-1">
                <label for="title" class="text-[10px] font-bold text-slate-300 uppercase tracking-widest ml-1">Title</label>
                <input type="text" id="title" name="title" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 placeholder-slate-300 transition focus:bg-slate-100 focus:ring-0" placeholder="e.g. Project Launch" required>
            </div>

            <div class="space-y-1">
                <label for="description" class="text-[10px] font-bold text-slate-300 uppercase tracking-widest ml-1">Description</label>
                <textarea id="description" name="description" rows="4" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 placeholder-slate-300 transition focus:bg-slate-100 focus:ring-0" placeholder="Describe the task..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="user_id" class="text-[10px] font-bold text-slate-300 uppercase tracking-widest ml-1">Assigned To</label>
                    <select id="user_id" name="user_id" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition focus:bg-slate-100 focus:ring-0">
                        <option value="" selected disabled>Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label for="due_date" class="text-[10px] font-bold text-slate-300 uppercase tracking-widest ml-1">Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition focus:bg-slate-100 focus:ring-0">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="priority" class="text-[10px] font-bold text-slate-300 uppercase tracking-widest ml-1">Priority</label>
                    <select id="priority" name="priority" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition focus:bg-slate-100 focus:ring-0">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label for="status" class="text-[10px] font-bold text-slate-300 uppercase tracking-widest ml-1">Status</label>
                    <select id="status" name="status" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition focus:bg-slate-100 focus:ring-0">
                        <option value="open">Open</option>
                        <option value="pending">Pending</option>
                        <option value="reviewing">Reviewing</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('tasks.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50">Cancel</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-slate-900 bg-white px-4 py-2.5 text-sm font-semibold text-black transition hover:bg-slate-100 active:scale-[0.98]">
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
