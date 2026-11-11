@extends('layouts.app')

@section('title', 'My Tasks | Task Manager')
@section('content')
<header class="page-heading">
    <div>
        <p class="eyebrow">YOUR PERSONAL WORKSPACE</p>
        <h1>My Tasks<span class="heading-dot">.</span></h1>
        <p class="page-description">Make room for what matters. One task at a time.</p>
    </div>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary"><span aria-hidden="true">+</span> Create Task</a>
</header>

@if (session('success'))
    <div class="alert alert-success" role="status">{{ session('success') }}</div>
@endif

<section class="stats-grid" aria-label="Task overview">
    @foreach (['pending' => ['Pending', 'Ready when you are'], 'in_progress' => ['In progress', 'Good things in motion'], 'completed' => ['Completed', 'A little win, every time']] as $status => [$label, $hint])
        <article class="stat-card stat-{{ $status }}" aria-label="{{ $label }}: {{ $counts->get($status, 0) }}">
            <div class="stat-label"><span class="status-dot" aria-hidden="true"></span>{{ $label }}</div>
            <strong class="stat-number">{{ str_pad($counts->get($status, 0), 2, '0', STR_PAD_LEFT) }}</strong>
            <p>{{ $hint }}</p>
        </article>
    @endforeach
</section>

<section class="panel task-panel" aria-labelledby="task-list-heading">
<div class="panel-heading">
    <h2 id="task-list-heading">Your task list <span class="count-badge">{{ $tasks->total() }}</span></h2>
    <span class="muted">A clear view of what's next</span>
</div>
<form method="GET" action="{{ route('tasks.index') }}" class="filter-bar">
    <div class="search-field">
        <label class="sr-only" for="search">Search by title</label>
        <span class="search-icon" aria-hidden="true"></span>
        <input id="search" type="search" name="search" placeholder="Search your tasks..." value="{{ request('search') }}" class="form-control">
    </div>

    <label class="sr-only" for="status">Status</label>
    <select id="status" name="status" class="form-select">
        <option value="">All statuses</option>
        @foreach (['pending' => 'Pending', 'in_progress' => 'In progress', 'completed' => 'Completed'] as $value => $label)
            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-secondary">Filter</button>
    <a class="clear-filters" href="{{ route('tasks.index') }}">Clear filters</a>
</form>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Due date</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                <tr>
                    <td data-label="Title" class="task-title">{{ $task->title }}</td>
                    <td data-label="Description" class="task-description">{{ $task->description ?: 'No description' }}</td>
                    <td data-label="Due date" class="task-date">{{ $task->due_date ? \Illuminate\Support\Carbon::parse($task->due_date)->format('M j, Y') : 'No due date' }}</td>
                    <td data-label="Status"><span class="status-badge status-{{ $task->status }}"><span class="status-dot" aria-hidden="true"></span>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span></td>
                    <td data-label="Actions">
                        <div class="task-actions">
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-secondary btn-small" aria-label="Edit {{ $task->title }}">Edit</a>
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="d-inline"
                            onsubmit="return confirm('Delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-small" aria-label="Delete {{ $task->title }}">Delete</button>
                        </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state">
                        <span class="empty-icon" aria-hidden="true">&#10003;</span>
                        <h3>No tasks found.</h3>
                        <p>{{ request()->filled('search') || request()->filled('status') ? 'Try another search or clear your filters.' : 'Start with one small thing. Your next win is waiting.' }}</p>
                        <a class="btn btn-primary" href="{{ route('tasks.create') }}">+ Create Task</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($tasks->hasPages())
    <div class="pagination-wrap">{{ $tasks->links('pagination::simple-default') }}</div>
@endif
</section>
@endsection
