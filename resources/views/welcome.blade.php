<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Traffic') }} — City incidents &amp; congestion</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-slate-950 text-slate-100 antialiased">
    <div class="relative isolate overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-amber-900/25 via-slate-950 to-slate-950"></div>
        <header class="mx-auto flex max-w-7xl items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
            <span class="flex items-center gap-2 text-lg font-semibold text-white">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-orange-600 text-xl shadow-lg shadow-orange-500/30">🚦</span>
                Traffic &amp; Safety
            </span>
            <nav class="flex gap-3 text-sm font-medium">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="rounded-lg px-4 py-2 text-slate-300 transition hover:bg-slate-800 hover:text-white">Log in</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 px-4 py-2 font-semibold text-slate-950 shadow-lg shadow-orange-500/25 transition hover:from-amber-400 hover:to-orange-500">Register</a>
                @endif
            </nav>
        </header>

        <main class="mx-auto max-w-7xl px-4 pb-24 pt-10 sm:px-6 lg:px-8 lg:pt-16">
            <div class="max-w-2xl">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">Report incidents on a live map</h1>
                <p class="mt-6 text-lg leading-relaxed text-slate-400">
                    Citizens submit accidents and traffic hotspots with precise coordinates. Administrators review the queue, approve what should appear on the map, and close resolved events.
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-slate-950 shadow hover:bg-slate-100">Create an account</a>
                    @endif
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-600 px-6 py-3 text-sm font-semibold text-white hover:border-slate-500 hover:bg-slate-900/50">Sign in</a>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>
