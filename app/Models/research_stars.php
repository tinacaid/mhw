<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Exception;

class research_stars extends Authenticatable implements JWTSubject {
    protected $table = "research_stars";
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
        return ['role => research_stars'];
    }

    public static function create($data)
    {
        try {
            $data = research_stars::insert([
                'student_id' => $data['student_id'],
                'project_name' => $data['project_name'],
                'project_level' => $data['project_level'],
                'ranking_total' => $data['ranking_total'],
                'approval_time' => $data['approval_time'],
                'materials' => $data['materials'],
            ]);
            return $data;
        } catch (Exception $e) {
            return 'error' . $e->getMessage();
        }
    }

    public static function check($student_id)
    {
        try {
            $data = research_stars::where('student_id',$student_id)
                ->select(
                    'project_name',
                    'project_level',
                    'ranking_total',
                    'approval_time',
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
            $affectedRows = research_stars::where('student_id', $student_id)
                ->update([
                    'project_name' => $data['project_name'],
                    'project_level' => $data['project_level'],
                    'ranking_total' => $data['ranking_total'],
                    'approval_time' => $data['approval_time'],
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
            $data = research_stars::where('student_id',$student_id)->delete();
            return $data;
        } catch (Exception $e) {
            return 'error' . $e->getMessage();
        }
    }

    public static function revise_status($student_id, $data)
    {
        try {
            // 使用 update() 方法来更新记录
            $affectedRows = research_stars::where('student_id', $student_id)
                ->update([
                    'status' => $data['status'],
                ]);
            // 返回受影响的行数
            return $affectedRows;
        } catch (Exception $e) {
            return 'error: ' . $e->getMessage();
        }
    }

}
