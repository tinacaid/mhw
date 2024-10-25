<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class software_stars extends Model
{
    protected $table = "software_stars";
    //是否开启时间戳（记录操作的时间）
    public $timestamps = false;
    //主键
    protected $primaryKey = "id";
    //白名单（不能进行修改）
    protected $guarded = [];


    use HasFactory;

    public static function cha($student_id)
    {
        try {
            $data = software_stars::where('student_id', $student_id)
                ->select('id', 'student_id', 'software_name', 'issuing_unit', 'ranking_total', 'approval_time', 'materials', 'status', 'rejection_reason')
                ->get();
            return $data;
        }catch (Exception $e){
            return 'error'.$e->getMessage();
        }
    }




    public static function id_shan($id)
    {
        try {
            $biao =software_stars::where('id', $id)->count();
            return $biao;
        } catch (Exception $e) {
            return 'error' . $e->getMessage();
        }
    }
    public static function shan($id)
    {
        try {
            $data=software_stars::where('id',$id)
                ->delete();
            return $data;
        }catch (Exception $e){
            return 'error'.$e->getMessage();
        }
    }
}
