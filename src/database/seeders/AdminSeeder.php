<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Admin::updateOrCreate(
            ['email' => 'admin@example.com'], // すでに存在するかチェック
            [
                'password' => Hash::make('password123'), // ✅ パスワードを暗号化
            ]
        );
    }
}
