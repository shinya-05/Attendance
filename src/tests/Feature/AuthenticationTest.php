<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ユーザー登録時のバリデーションチェック()
    {
        // 名前未入力
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);

        // メールアドレス未入力
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);

        // パスワードが8文字未満
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);
        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);

        // パスワード不一致
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpassword',
        ]);
        $response->assertSessionHasErrors(['password' => 'パスワードが一致しません']);

        // 登録成功
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertRedirect('/home');
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** @test */
    public function 一般ユーザーのログイン認証()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // メールアドレス未入力
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);

        // パスワード未入力
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);
        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);

        // 登録されていない情報
        $response = $this->post('/login', [
            'email' => 'notfound@example.com',
            'password' => 'password123',
        ]);
        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);

        // 正常ログイン
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function 管理者のログイン認証()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpassword'),
            'is_admin' => true,
        ]);

        // メールアドレス未入力
        $response = $this->post('/admin/login', [
            'email' => '',
            'password' => 'adminpassword',
        ]);
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);

        // パスワード未入力
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => '',
        ]);
        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);

        // ログイン失敗
        $response = $this->post('/admin/login', [
            'email' => 'notfound@example.com',
            'password' => 'adminpassword',
        ]);
        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);

        // 正常ログイン
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'adminpassword',
        ]);
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function 現在の日時が正しく取得できる()
    {
        $response = $this->get('/get-current-time');
        $currentTime = Carbon::now()->format('Y-m-d H:i:s');

        $response->assertStatus(200)
            ->assertJson([
                'current_time' => $currentTime
            ]);
    }
}
