<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;//引用Authenticatable类使得DemoModel具有用户认证功能
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Exception;


class paper_stars extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'journal_name',
        'paper_title',
        'publication_time',
        'materials',

    ];
    protected $table = "paper_stars";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    //不知道有什么用
    use HasFactory;

    public function getJWTIdentifier()
    {
        //getKey() 方法用于获取模型的主键值
        return $this->getKey();
    }

    //返回一个包含自定义声明的关联数组。
    public function getJWTCustomClaims()
    {
        return ["role" => "paper_stars"];
    }

    public  static function lunwen($user)
    {
        try {
            $add = paper_stars::insert([
                'journal_name' => $user['journal_name'],
                'paper_title' => $user['paper_title'],
                'publication_time' => $user['publication_time'],//发表时间
                'materials' => $user['materials'],
                'student_id'=>$user['student_id'],
            ]);
            return $add;
        } catch (Exception $e) {
            return 'error' . $e->getMessage();
        }
    }
            public static function FindDate($id)//学生查询
            {
                try {
                    $data = paper_stars::where('student_id', $id)
                        ->get([
                            'journal_name', 'paper_title',  'publication_time', 'materials', 'status',  'rejection_reason'
                        ]);
                    return $data;
                } catch (Exception $e) {
                    return 'error ' . $e->getMessage();
                }
            }
public static function shanchu($user)
{
    try{
        $dd=paper_stars::where('student_id',$user)
            ->delete();
        return $dd;
    }catch (Exception $e) {
        return 'error ' . $e->getMessage();
    }
}
    public static function shuliang($user)
    {
        try{
            $dd=paper_stars::where('student_id',$user)
                -> count();
            return $dd;
        }catch (Exception $e) {
            return 'error ' . $e->getMessage();
        }
    }
}
