<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">Edit Task #{{ $task->id }}</h4>
                        <p class="text-muted mb-0">Update details for this todo item.</p>
                    </div>
                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-secondary btn-sm">View Task</a>
                </div>

                <form method="POST" action="{{ route('tasks.update', $task) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid border-danger @enderror" id="title" name="title" value="{{ old('title', $task->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid border-danger @enderror" id="description" name="description" rows="4">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="creator_id" class="form-label">Creator</label>
                            <select id="creator_id" name="creator_id" class="form-select @error('creator_id') is-invalid border-danger @enderror" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ (int) old('creator_id', $task->creator_id) === (int) $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('creator_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="assignee_id" class="form-label">Assignee</label>
                            <select id="assignee_id" name="assignee_id" class="form-select @error('assignee_id') is-invalid border-danger @enderror" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ (int) old('assignee_id', $task->assignee_id) === (int) $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assignee_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control @error('due_date') is-invalid border-danger @enderror" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date ?? '') }}">
                            @error('due_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select @error('priority') is-invalid border-danger @enderror" id="priority" name="priority">
                                <option value="low" {{ old('priority', $task->priority ?? '') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $task->priority ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $task->priority ?? '') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority', $task->priority ?? '') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid border-danger @enderror" id="status" name="status">
                            <option value="to-do" {{ old('status', $task->status ?? '') == 'to-do' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ old('status', $task->status ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="done" {{ old('status', $task->status ?? '') == 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="images" class="form-label">Replace Task Images (JPG/PNG)</label>
                        <input type="file" id="images" name="images[]" accept=".jpg,.jpeg,.png,image/jpeg,image/png" multiple class="form-control @error('images') is-invalid border-danger @enderror @error('images.*') is-invalid border-danger @enderror">
                        <!-- <small class="text-muted">Uploading new images will remove old images for this task.</small> -->
                        @error('images')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($task->taskImages->isNotEmpty())
                        <div class="mb-4">
                            <label class="form-label d-block">Current Images</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($task->taskImages as $image)
                                    <img src="{{ $image->url }}" alt="Task image" class="img-thumbnail" style="width: 110px; height: 110px; object-fit: cover;">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
