<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Purchase Module' }}</title>
    <style>
        body { margin: 0; font-family: system-ui, sans-serif; background: #f4f5f7; color: #1f2937; }
        .container { max-width: 1100px; margin: 0 auto; padding: 1.5rem; }
        .card { background: white; border: 1px solid #d1d5db; border-radius: 14px; box-shadow: 0 12px 30px rgba(15,23,42,0.08); padding: 1.5rem; }
        .button { display: inline-flex; align-items: center; justify-content: center; border-radius: 9999px; border: none; cursor: pointer; font-weight: 600; }
        .button-primary { background: #2563eb; color: white; }
        .button-secondary { background: #f3f4f6; color: #111827; }
        .button-danger { background: #dc2626; color: white; }
        input, select { width: 100%; padding: 0.75rem 0.9rem; border: 1px solid #d1d5db; border-radius: 0.75rem; background: white; }
        input:focus, select:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37,99,235,0.12); }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.9rem 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { color: #374151; font-weight: 700; }
        .grid { display: grid; gap: 1rem; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .error { color: #b91c1c; font-size: 0.95rem; margin-top: 0.4rem; }
        .notice { background: #f8fafc; border: 1px solid #cbd5e1; padding: 1rem; border-radius: 1rem; margin-bottom: 1rem; }
        .badge { display: inline-flex; padding: 0.35rem 0.75rem; border-radius: 9999px; background: #e2e8f0; font-size: 0.85rem; }
        .flex { display: flex; gap: 0.75rem; align-items: center; }
        .stack { display: flex; flex-direction: column; gap: 1rem; }
        .mt-2 { margin-top: 0.75rem; }
        .mt-3 { margin-top: 1rem; }
        .mt-4 { margin-top: 1.5rem; }
    </style>
    @livewireStyles
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    @livewireScripts
</body>
</html>
