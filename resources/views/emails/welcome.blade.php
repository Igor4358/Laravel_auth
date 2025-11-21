<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Добро пожаловать!</title>
</head>
<body>
<h1>Добро пожаловать, {{ $user->name }}!</h1>
<p>Благодарим вас за регистрацию в нашем блоге.</p>
<p>Ваш email: {{ $user->email }}</p>
</body>
</html>
