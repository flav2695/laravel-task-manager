<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#214e3c">
    <title>@yield('title', 'Task Manager')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>

<body>
    <a class="skip-link" href="#main-content">Skip to content</a>
    <header class="site-header">
        <a class="brand" href="{{ auth()->check() ? route('tasks.index') : route('login') }}">
            <span class="brand-mark" aria-hidden="true">&#10003;</span>
            Task Manager<span class="brand-dot">.</span>
        </a>
        <nav class="header-nav" aria-label="Main navigation">
            @auth
                <a href="{{ route('tasks.index') }}" @class(['nav-link', 'active' => request()->routeIs('tasks.*')])>Workspace</a>
                <span class="user-name">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-secondary btn-small" type="submit">Log out</button>
                </form>
            @else
                <a href="{{ route('login') }}" @class(['nav-link', 'active' => request()->routeIs('login')])>Log in</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-small">Get started <span aria-hidden="true">&#8599;</span></a>
            @endauth
        </nav>
    </header>
    <main id="main-content" class="page-shell @guest guest-page @endguest">
        @yield('content')
    </main>
    <footer class="site-footer"><span>Task Manager</span> A little clarity. A lot more done.</footer>
</body>
</html>
