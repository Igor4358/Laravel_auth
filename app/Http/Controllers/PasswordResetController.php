<?php

namespace App\Http\Controllers;

use App\Jobs\PasswordResetMailJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    // Показать форму запроса сброса пароля
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Отправить ссылку для сброса пароля
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Пользователь с таким email не найден']);
        }

        // Отправляем письмо через очередь
        PasswordResetMailJob::dispatch($user);

        return back()->with('success', 'Ссылка для восстановления пароля отправлена на вашу почту!');
    }

    // Показать форму сброса пароля
    public function showResetForm(Request $request, $user, $hash)
    {
        if (! $request->hasValidSignature()) {
            return redirect()->route('password.request')->with('error', 'Неверная или просроченная ссылка');
        }

        $user = User::findOrFail($user);

        if ($hash !== sha1($user->email)) {
            return redirect()->route('password.request')->with('error', 'Неверная подпись');
        }

        return view('auth.passwords.reset', compact('user', 'hash'));
    }

    // Сбросить пароль
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'hash' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Пользователь не найден']);
        }

        if ($request->hash !== sha1($user->email)) {
            return back()->withErrors(['hash' => 'Неверная подпись']);
        }

        // Обновляем пароль
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('login.view')->with('success', 'Пароль успешно изменен! Теперь вы можете войти.');
    }
}
