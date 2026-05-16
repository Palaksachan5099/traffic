<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Page Expired') }}</title>
    <style>
        :root { color-scheme: dark; }
        body { margin: 0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Apple Color Emoji", "Segoe UI Emoji"; background: #0b1220; color: #e5e7eb; }
        .wrap { min-height: 100vh; display: grid; place-items: center; padding: 32px; }
        .card { width: 100%; max-width: 720px; background: #0f172a; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 24px; }
        .kicker { font-size: 12px; letter-spacing: .08em; text-transform: uppercase; color: rgba(229,231,235,.7); }
        h1 { margin: 8px 0 0; font-size: 26px; }
        p { margin: 12px 0 0; color: rgba(229,231,235,.8); line-height: 1.55; }
        .actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 18px; }
        a, button { appearance: none; border: 0; cursor: pointer; text-decoration: none; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 14px; border-radius: 10px; font-weight: 600; }
        .primary { background: #4f46e5; color: #fff; }
        .ghost { background: rgba(255,255,255,0.06); color: #e5e7eb; border: 1px solid rgba(255,255,255,0.08); }
        .hint { margin-top: 12px; font-size: 13px; color: rgba(229,231,235,.65); }
        code { background: rgba(255,255,255,0.06); padding: 2px 6px; border-radius: 8px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="kicker">419</div>
        <h1>{{ __('Page Expired') }}</h1>
        <p>
            {{ __("This usually happens when the CSRF token/session expired or the form was opened in another tab and then submitted later.") }}
        </p>

        <div class="actions">
            <button class="btn primary" onclick="window.location.reload()">{{ __('Refresh') }}</button>
            <a class="btn ghost" href="{{ url()->previous() }}">{{ __('Go Back') }}</a>
            <a class="btn ghost" href="{{ route('login') }}">{{ __('Login') }}</a>
            <a class="btn ghost" href="{{ route('register') }}">{{ __('Register') }}</a>
        </div>

        <div class="hint">
            {{ __('Tip: If it keeps happening, clear cookies for') }} <code>127.0.0.1</code> {{ __('and try again.') }}
        </div>
    </div>
</div>
</body>
</html>

