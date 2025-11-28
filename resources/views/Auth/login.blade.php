<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; background: #28a745; color: white; padding: 12px; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 14px; margin-top: 5px; }
        .success { color: green; margin-bottom: 15px; padding: 10px; background: #d4edda; border-radius: 4px; }
        h2 { text-align: center; margin-bottom: 30px; }
        .register-link {text-align: center;border: none;margin-top: 20px; }
        .register-btn{
            box-sizing: border-box;display: block; width: 100%; background: #28a745; color: white;  padding: 12px; text-align: center;text-decoration: none;
            border-radius: 4px; font-size: 16px;transition: background-color 0.3s;
        }
        .register-btn:hover {
            background: #218838;
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Вход</h2>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <button type="submit">Войти</button>
    </form>

    <div class="register-link">
        <a href="{{ route('register.view') }}" class="register-btn" >Нет аккаунта? Зарегистрируйтесь</a>
    </div>
    <div class="links" style="text-align: center; margin-top: 20px;">
        <a href="{{ route('password.request') }}">Забыли пароль?</a>
    </div>
</div>
</body>
</html>
