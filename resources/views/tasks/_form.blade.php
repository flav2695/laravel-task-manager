<div class="mb-3">
    <label for="title">Title</label>
    <input id="title" type="text" name="title" value="{{ old('title', $task->title ?? '') }}"
        class="form-control @error('title') is-invalid @enderror" required maxlength="255">
    @error('title')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4"
        class="form-control @error('description') is-invalid @enderror">{{ old('description', $task->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="due_date">Due date</label>
    <input id="due_date" type="date" name="due_date" value="{{ old('due_date', $task->due_date ?? '') }}"
        class="form-control @error('due_date') is-invalid @enderror">
    @error('due_date')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="status">Status</label>
    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
        @foreach (['pending' => 'Pending', 'in_progress' => 'In progress', 'completed' => 'Completed'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $task->status ?? 'pending') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error('status')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
    @enderror
</div>
