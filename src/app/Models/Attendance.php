<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'start_time', 'end_time', 'note', 
        'status'];

    public function rests()
    {
        return $this->hasMany('App\Models\Rest');
         //AttendanceとRestモデルは「1対多」の関係
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', "user_id");
        //AttendanceはUserモデルと「多対1」の関係
    }

    public static function getAttendance()
    {
        $id = Auth::id();

        $dt = new Carbon();
        $date = $dt->toDateString();
        //toDateString()メソッドは、日時を「YYYY-MM-DD」形式の文字列として取得

        $attendance = Attendance::where('user_id', $id)->where('date', $date)->first();
        //user_idが$id、かつdateが$dateに一致する最初のレコードを取得し、変数$attendanceに格納

        return $attendance;
    }
}
