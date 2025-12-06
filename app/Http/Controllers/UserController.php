<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        // Проверяем авторизацию и права админа в методе
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Требуются права администратора');
        }

        $users = User::with(['posts', 'comments'])->latest()->get();
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        $posts = $user->posts()->with('category')->latest()->paginate(10);
        $comments = $user->comments()->with('post')->latest()->paginate(10);

        return view('users.show', compact('user', 'posts', 'comments'));
    }

    public function profile()
    {
        if (!Auth::check()) {
            return redirect()->route('login.view');
        }

        $user = Auth::user();
        $posts = $user->posts()->with('category')->latest()->paginate(10);

        return view('users.profile', compact('user', 'posts'));
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

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Обновляем профиль
        if ($user->profile) {
            $profileData = ['bio' => $request->bio ?? null];

            // Обработка аватарки
            if ($request->hasFile('avatar')) {
                if ($user->profile->avatar) {
                    Storage::disk('public')->delete($user->profile->avatar);
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $profileData['avatar'] = $avatarPath;
            }

            $user->profile()->update($profileData);
        }

        $user->update($updateData);

        Log::info('Profile updated', ['user_id' => $user->id]);

        return redirect()->route('user.profile')->with('success', 'Профиль успешно обновлен!');
    }

    public function updateRole(Request $request, User $user)
    {
        // Дополнительная проверка (middleware уже проверил, но на всякий случай)
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Требуются права администратора');
        }

        $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        Log::info('User role updated', [
            'user_id' => $user->id,
            'old_role' => $oldRole,
            'new_role' => $request->role,
            'updated_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Роль пользователя обновлена!');
    }

    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->profile && $user->profile->avatar) {
            Storage::disk('public')->delete($user->profile->avatar);
            $user->profile()->update(['avatar' => null]);

            Log::info('Avatar removed', ['user_id' => $user->id]);
        }

        return redirect()->route('user.profile')->with('success', 'Аватар успешно удален!');
    }
}
