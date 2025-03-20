<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UpdateAttendanceRequest extends FormRequest
{
    /**
     * 認証されたユーザーのみがこのリクエストを実行できるかどうかを判定
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルールを定義
     */
    public function rules()
    {
        return [
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'rest_start' => ['nullable', 'after_or_equal:start_time', 'before_or_equal:end_time'],
            'rest_end' => ['nullable', 'after:rest_start', 'before_or_equal:end_time'],
            'note' => ['required', 'string'],
        ];
    }

    /**
     * カスタムエラーメッセージ
     */
    public function messages()
    {
        return [
            'start_time.required' => '出勤時間を入力してください',
            'end_time.required' => '退勤時間を入力してください',
            'end_time.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'rest_start.after_or_equal' => '休憩時間が勤務時間外です',
            'rest_start.before_or_equal' => '休憩時間が勤務時間外です',
            'rest_end.after' => '休憩開始時間より後の時間を設定してください',
            'rest_end.before_or_equal' => '休憩時間が勤務時間外です',
            'note.required' => '備考を記入してください。',
        ];
    }
}
