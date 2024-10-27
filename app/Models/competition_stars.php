<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Exception;


class competition_stars extends Authenticatable implements JWTSubject {

use Illuminate\Notifications\Notifiable;
 // 或者 use Laravel\Sanctum\HasApiTokens;
class competition_stars extends Authenticatable implements JWTSubject
{

    protected $table = "competition_stars";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    use Notifiable;

    public function getJWTIdentifier()
    {
        //getKey() 方法用于获取模型的主键值
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role => competition_stars'];
    }

    public static function check($student_id)
    {
        try {
            $data = competition_stars::where('student_id',$student_id)
                ->select(
                    'competition_name',
                    'registration_time',
                    'materials',
                    'status',
                )
                ->get();
            return $data;
        } catch (Exception $e) {
            return 'error' . $e->getMessage();
        }
    }

    public static function revise($student_id, $data)
    {
        try {
            // 使用 update() 方法来更新记录
            $affectedRows = competition_stars::where('student_id', $student_id)
                ->update([
                    'competition_name' => $data['competition_name'],
                    'registration_time' => $data['registration_time'],
                    'materials' => $data['materials'],
                ]);
            // 返回受影响的行数
            return $affectedRows;
        } catch (Exception $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    public static function deleted($student_id)
    {
        try {
            $data = competition_stars::where('student_id',$student_id)->delete();
            return $data;
        } catch (Exception $e) {
            return 'error' . $e->getMessage();
        }
    }

    public static function teacher_check()
    {
        try {
            $data = competition_stars::get();
            return $data;
        } catch (Exception $e) {
            return 'error' . $e->getMessage();
        }
    }
}
}




