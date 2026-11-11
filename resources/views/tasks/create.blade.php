@extends('layouts.app')

@section('content')
<div class="editor-shell">
<a class="back-link" href="{{ route('tasks.index') }}">&#8592; Back to tasks</a>
<header class="page-heading">
    <div><p class="eyebrow">A FRESH START</p><h1>Create Task<span class="heading-dot">.</span></h1>
    <p class="page-description">Turn your next idea into a plan.</p></div>
</header>

<form method="POST" action="{{ route('tasks.store') }}" class="panel editor-form">
    @csrf
    @include('tasks._form')
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Create Task <span aria-hidden="true">&#8594;</span></button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
</div>
@endsection
