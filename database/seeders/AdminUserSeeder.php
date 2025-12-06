<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем администратора
        $admin = User::create([
            'name' => 'Администратор',
            'email' => 'admin@blog.com',
            'password' => Hash::make('admin123'), // Пароль: admin123
            'role' => 'admin',
        ]);

        // Создаем профиль для админа
        Profile::create([
            'user_id' => $admin->id,
            'bio' => 'Системный администратор блога',
        ]);

        $this->command->info('Администратор: admin@blog.com / admin123');
    }
}
