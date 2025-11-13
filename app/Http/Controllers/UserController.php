<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['profile', 'posts'])->get();
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['profile', 'posts']);
        return view('users.show', compact('user'));
    }

    public function profile()
    {
        $user = Auth::user();
        $user->load('profile');
        return view('users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Обновляем основные данные пользователя
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Обновляем пароль если указан
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Обработка аватарки
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            // Удаляем старую аватарку если есть
            if ($user->profile && $user->profile->avatar) {
                Storage::disk('public')->delete($user->profile->avatar);
            }

            // Сохраняем новую аватарку
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Обновляем или создаем профиль
        if ($user->profile) {
            $user->profile->update([
                'avatar' => $avatarPath ?: $user->profile->avatar,
                'bio' => $request->bio ?: $user->profile->bio
            ]);
        } else {
            $user->profile()->create([
                'avatar' => $avatarPath,
                'bio' => $request->bio
            ]);
        }

        return redirect()->route('user.profile')->with('success', 'Профиль успешно обновлен!');
    }

    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->profile && $user->profile->avatar) {
            // Удаляем файл аватарки
            Storage::disk('public')->delete($user->profile->avatar);

            // Обновляем профиль
            $user->profile->update(['avatar' => null]);
        }

        return redirect()->route('user.profile')->with('success', 'Аватарка успешно удалена!');
    }
}
