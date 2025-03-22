<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 勤怠ステータスが正しく表示される()
    {
        $user = User::factory()->create();

        // 勤務外の状態
        $this->actingAs($user)
            ->get('/attendance/status')
            ->assertSee('勤務外');

        // 出勤中の状態
        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'start_time' => Carbon::now()->subHours(1),
        ]);
        $this->actingAs($user)
            ->get('/attendance/status')
            ->assertSee('勤務中');

        // 休憩中の状態
        Attendance::where('user_id', $user->id)
            ->update(['rest_start' => Carbon::now()->subMinutes(30)]);
        $this->actingAs($user)
            ->get('/attendance/status')
            ->assertSee('休憩中');

        // 退勤済の状態
        Attendance::where('user_id', $user->id)
            ->update(['end_time' => Carbon::now()]);
        $this->actingAs($user)
            ->get('/attendance/status')
            ->assertSee('退勤済');
    }

    /** @test */
    public function 出勤ボタンの動作を確認()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/attendance/start')
            ->assertRedirect('/dashboard');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'date' => Carbon::today()->toDateString(),
            'start_time' => Carbon::now()->format('H:i:s'),
        ]);
    }

    /** @test */
    public function 一日一回の出勤制限()
    {
        $user = User::factory()->create();
        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'start_time' => Carbon::now(),
        ]);

        $this->actingAs($user)
            ->post('/attendance/start')
            ->assertSessionHasErrors(['error' => 'すでに出勤しています。']);
    }

    /** @test */
    public function 出勤時刻が管理画面で確認できる()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'start_time' => '09:00:00',
        ]);

        $this->actingAs($admin)
            ->get("/admin/attendance/{$user->id}")
            ->assertSee('09:00');
    }

    /** @test */
    public function 休憩ボタンの動作を確認()
    {
        $user = User::factory()->create();
        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'start_time' => '09:00:00',
        ]);

        $this->actingAs($user)
            ->post('/attendance/break')
            ->assertRedirect('/dashboard');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'rest_start' => Carbon::now()->format('H:i:s'),
        ]);
    }

    /** @test */
    public function 休憩は1日に何回でもできる()
    {
        $user = User::factory()->create();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'start_time' => '09:00:00',
        ]);

        for ($i = 0; $i < 3; $i++) {
            $this->actingAs($user)
                ->post('/attendance/break')
                ->assertRedirect('/dashboard');

            $this->assertDatabaseHas('attendances', [
                'user_id' => $user->id,
            ]);
        }
    }

    /** @test */
    public function 休憩戻りボタンの動作を確認()
    {
        $user = User::factory()->create();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'start_time' => '09:00:00',
            'rest_start' => Carbon::now()->subMinutes(30)->format('H:i:s'),
        ]);

        $this->actingAs($user)
            ->post('/attendance/break-end')
            ->assertRedirect('/dashboard');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'rest_end' => Carbon::now()->format('H:i:s'),
        ]);
    }

    /** @test */
    public function 休憩時刻が管理画面で確認できる()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'start_time' => '09:00:00',
            'rest_start' => '12:30:00',
            'rest_end' => '13:00:00',
        ]);

        $this->actingAs($admin)
            ->get("/admin/attendance/{$user->id}")
            ->assertSee('12:30')
            ->assertSee('13:00');
    }
}
