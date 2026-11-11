@extends('layouts.app')

@section('content')
<div class="editor-shell">
<a class="back-link" href="{{ route('tasks.index') }}">&#8592; Back to tasks</a>
<header class="page-heading">
    <div><p class="eyebrow">KEEP THINGS MOVING</p><h1>Edit Task<span class="heading-dot">.</span></h1>
    <p class="page-description">A small update. A clearer path forward.</p></div>
</header>

<form method="POST" action="{{ route('tasks.update', $task) }}" class="panel editor-form">
    @csrf
    @method('PUT')
    @include('tasks._form')
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Changes <span aria-hidden="true">&#8594;</span></button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
</div>
@endsection
