<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        Log::info('Login attempt', ['email' => $request->email, 'ip' => $request->ip()]);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);



        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            Log::info('Login successful', ['user_id' => Auth::id()]);
            return redirect('/dashboard')->with('success', 'Вход выполнен успешно!');
        }
        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect('/')->with('success', 'Выход выполнен успешно!');
    }
}
