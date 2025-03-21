<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ja_JP');

        // ✅ ダミーユーザーを10人作成
        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => "test{$i}@example.com",
                'password' => Hash::make('password123'), // ハッシュ化
                'email_verified_at' => Carbon::now(), // ✅ メール認証済みにする
            ]);

            // ✅ 各ユーザーの過去1ヶ月分の勤怠データを作成
            for ($j = 0; $j < 30; $j++) {
                $date = Carbon::now()->subDays($j)->format('Y-m-d'); // 30日前から今日まで

                $startTime = Carbon::parse('09:00')->addMinutes(rand(0, 30)); // 9:00～9:30の間
                $endTime = Carbon::parse('18:00')->subMinutes(rand(0, 30)); // 17:30～18:00の間

                // 勤怠データを作成
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date,
                    'start_time' => $startTime->format('H:i:s'),
                    'end_time' => $endTime->format('H:i:s'),
                    'note' => '勤怠のメモ ' . ($j + 1),
                    'status' => rand(0, 1) ? '承認済み' : '承認待ち', // ランダムにステータス設定
                ]);

                // ✅ 休憩データを作成（1回または2回の休憩）
                for ($k = 0; $k < rand(1, 2); $k++) {
                    $restStart = Carbon::parse('12:00')->addMinutes(rand(0, 30)); // 12:00～12:30
                    $restEnd = $restStart->copy()->addMinutes(rand(30, 60)); // 30～60分後に終了

                    Rest::create([
                        'attendance_id' => $attendance->id,
                        'start_time' => $restStart->format('H:i:s'),
                        'end_time' => $restEnd->format('H:i:s'),
                    ]);
                }
            }
        }
    }
}
