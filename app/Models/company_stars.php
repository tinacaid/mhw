<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Exception;

class company_stars extends Authenticatable implements JWTSubject
{
    protected $table = "company_stars";
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
        return ['role => company_stars'];
    }

    // 与 students 表的关联
    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id', 'id');
    }

    public static function create($data)
    {
        try {
            $data = company_stars::insert([
                'student_id'=>$data['student_id'],
                'company_name' => $data['company_name'],
                'company_type' => $data['company_type'],
                'applicant_rank' => $data['applicant_rank'],
                'materials' => $data['materials'],
                'registration_time'=>$data['registration_time'],
                'company_scale'=>$data['company_scale'],
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
            $affectedRows = company_stars::where('student_id', $student_id)
                ->update([
                    'company_name' => $data['company_name'],
                    'company_type' => $data['company_type'],
                    'applicant_rank' => $data['applicant_rank'],
                    'materials' => $data['materials'],
                    'registration_time'=>$data['registration_time'],
                    'company_scale'=>$data['company_scale'],
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
            $affectedRows = company_stars::where('student_id', $student_id)
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


