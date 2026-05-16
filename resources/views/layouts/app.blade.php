<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Traffic') }} — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body class="min-h-full bg-slate-950 text-slate-100 antialiased font-sans">
    <nav class="border-b border-slate-800/80 bg-slate-900/80 backdrop-blur-md sticky top-0 z-[1000]">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route(auth()->check() ? auth()->user()->dashboardRoute() : 'dashboard') }}" class="flex items-center gap-2 text-lg font-semibold tracking-tight text-white">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-amber-400 to-orange-600 text-lg shadow-lg shadow-orange-500/20">🚦</span>
                <span>Traffic &amp; Safety</span>
            </a>
            <div class="flex flex-wrap items-center gap-2 text-sm">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 font-medium text-amber-300 transition hover:bg-slate-800 hover:text-amber-200">Admin</a>
                    @endif
                    @if(in_array(auth()->user()->role, ['admin', 'officer'], true))
                        <a href="{{ route('assignments.index') }}" class="rounded-lg px-3 py-2 font-medium text-indigo-300 transition hover:bg-slate-800 hover:text-indigo-200">Assignments</a>
                    @endif
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-2 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">User dashboard</a>
                        <a href="{{ route('alerts.index') }}" class="rounded-lg px-3 py-2 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Alerts</a>
                        <a href="{{ route('congestion.index') }}" class="rounded-lg px-3 py-2 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Congestion</a>
                        <a href="{{ route('reports.index') }}" class="rounded-lg px-3 py-2 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">My reports</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="rounded-lg px-3 py-2 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">Profile</a>
                    <span class="hidden sm:inline text-slate-500">|</span>
                    <span class="max-w-[10rem] truncate text-slate-400 sm:max-w-none">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="rounded-lg px-3 py-2 font-medium text-rose-400 transition hover:bg-rose-950/50 hover:text-rose-300">Log out</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-950/40 px-4 py-3 text-emerald-200 shadow-lg shadow-emerald-900/20" role="status">
                {{ session('success') }}
            </div>
        @endif
        @if(session('status'))
            <div class="mb-6 rounded-xl border border-sky-500/30 bg-sky-950/40 px-4 py-3 text-sky-200" role="status">
                {{ session('status') }}
            </div>
        @endif

        @isset($header)
            <header class="mb-8 border-b border-slate-800 pb-4">
                {{ $header }}
            </header>
        @endisset

        @isset($slot)
            {{ $slot }}
        @endisset
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
