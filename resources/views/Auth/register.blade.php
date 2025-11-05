<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button, .login-btn {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            box-sizing: border-box;
            display: block;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
            font-family: Arial, sans-serif;
        }
        button:hover, .login-btn:hover {
            background: #0056b3;
            text-decoration: none;
            color: white;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        .success {
            color: green;
            font-size: 14px;
            margin-bottom: 15px;
            padding: 10px;
            background: #d4edda;
            border-radius: 4px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .login-link {
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Регистрация</h2>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            @error('password')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Подтвердите пароль:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label for="avatar">Аватар (опционально):</label>
            <input type="file" id="avatar" name="avatar" accept="image/*">
            @error('avatar') <div class="error">{{ $message }}</div> @enderror
        </div>

        <button type="submit">Зарегистрироваться</button>
    </form>

    <div class="login-link">
        <a href="{{ route('login.view') }}" class="login-btn">Уже есть аккаунт? Войдите</a>
    </div>
</div>
</body>
</html>
