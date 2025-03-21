<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        $startTime = Carbon::createFromTime(rand(8, 10), rand(0, 59), 0); // 8:00〜10:59 のランダムな時間
        $endTime = (clone $startTime)->addHours(rand(7, 9))->addMinutes(rand(0, 59)); // 7〜9時間後
        $restStart = (clone $startTime)->addHours(rand(3, 5))->addMinutes(rand(0, 30)); // 3〜5時間後
        $restEnd = (clone $restStart)->addMinutes(rand(30, 60)); // 30〜60分の休憩

        return [
            'user_id' => User::inRandomOrder()->first()->id, // ランダムなユーザー
            'date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'), // 今月の日付
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'rest_start' => $restStart->format('H:i:s'),
            'rest_end' => $restEnd->format('H:i:s'),
            'note' => $this->faker->sentence(),
            'status' => '承認済み', // デフォルトで承認済みにする
        ];
    }
}
