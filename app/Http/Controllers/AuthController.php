<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Jobs\MailSendJob;
use App\Jobs\VerifyMailSendJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function registerView()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        Log::info('Registration attempt', ['email' => $request->email, 'ip' => $request->ip()]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Log::info('User created', ['user_id' => $user->id, 'email' => $user->email]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            Log::info('Avatar uploaded', ['user_id' => $user->id, 'path' => $avatarPath]);
        }

        Profile::create([
            'user_id' => $user->id,
            'avatar' => $avatarPath,
        ]);
        Log::info('Profile created', ['user_id' => $user->id]);

        MailSendJob::dispatch($user);
        VerifyMailSendJob::dispatch($user);

        Log::info('Registration emails dispatched', ['user_id' => $user->id]);

        auth()->login($user);
        return redirect('/dashboard')->with('success', 'Регистрация прошла успешно! На вашу почту отправлено письмо.!');
    }
}
