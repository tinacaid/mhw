<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Exception;

class software_stars extends Authenticatable implements JWTSubject
{
    protected $table = "software_stars";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getJWTIdentifier()
    {
        //getKey() 方法用于获取模型的主键值
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role => software_stars'];
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id', 'id');
    }

    public static function create($data)
    {
        try {
            $data = software_stars::insert([
                'student_id'=>$data['student_id'],
                'software_name' => $data['software_name'],
                'issuing_unit' => $data['issuing_unit'],
                'ranking_total' => $data['ranking_total'],
                'approval_time' => $data['approval_time'],
                'materials'=>$data['materials'],
            ]);
            return $data;
        } catch (Exception $e) {
            return 'error' . $e->getMessage();
        }
    }

    public static function revise($student_id, $data)
    {
        try {
            // 使用 update() 方法来更新记录
            $affectedRows = software_stars::where('student_id', $student_id)
                ->update([
                    'software_name' => $data['software_name'],
                    'issuing_unit' => $data['issuing_unit'],
                    'ranking_total' => $data['ranking_total'],
                    'approval_time' => $data['approval_time'],
                    'materials'=>$data['materials'],
                ]);
            // 返回受影响的行数
            return $affectedRows;
        } catch (Exception $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    public static function revise_status($student_id, $data)
    {
        try {
            // 使用 update() 方法来更新记录
            $affectedRows = software_stars::where('student_id', $student_id)
                ->update([
                    'status' => $data['status'],
                    'rejection_reason' => isset($data['reason']) ? $data['reason'] : null,
                ]);

            // 返回受影响的行数
            return $affectedRows;
        } catch (Exception $e) {
            return 'error: ' . $e->getMessage();
        }
    }
}


