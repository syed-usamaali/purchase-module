<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9fafb; color: #111827; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: #fff; border: 1px solid #d1d5db; border-radius: 8px; padding: 24px; width: 360px; box-shadow: 0 10px 20px rgba(15, 23, 42, 0.08); }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group input { width: 100%; padding: 10px 0px; border: 1px solid #d1d5db; border-radius: 6px; }
        .button { width: 100%; padding: 12px; border: none; border-radius: 6px; background: #2563eb; color: white; font-weight: 700; cursor: pointer; }
        .errors { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Login</h1>

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div class="form-group">
                <label><input type="checkbox" name="remember"> Remember me</label>
            </div>

            <button type="submit" class="button">Sign in</button>
        </form>
    </div>
</body>
</html>
