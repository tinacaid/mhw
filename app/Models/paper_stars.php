<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Exception;

class paper_stars extends Authenticatable implements JWTSubject
{
    protected $table = "paper_stars";
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
        return ['role => paper_stars'];
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id', 'id');
    }

    public static function create($data)
    {
        try {
            $data = paper_stars::insert([
                'student_id'=>$data['student_id'],
                'journal_name' => $data['journal_name'],
                'paper_title' => $data['paper_title'],
                'publication_time' => $data['publication_time'],
                'materials' => $data['materials'],
                'ranking_total'=>$data['ranking_total'],
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
            $affectedRows = paper_stars::where('student_id', $student_id)
                ->update([
                    'journal_name' => $data['journal_name'],
                    'paper_title' => $data['paper_title'],
                    'publication_time' => $data['publication_time'],
                    'materials' => $data['materials'],
                    'ranking_total'=>$data['ranking_total'],
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
            $affectedRows = paper_stars::where('student_id', $student_id)
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

