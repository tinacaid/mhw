<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;//引用Authenticatable类使得DemoModel具有用户认证功能
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

 class admins extends Authenticatable implements JWTSubject

 {

     // 定义可以批量赋值的字段
     protected $fillable = [
         'account',
         'password',
         'major',


     ];
     protected $table = "admins";
     public $timestamps = true;
     protected $primaryKey = "id";
     protected $guarded = [];

     // 隐藏密码字段
//    protected $hidden = [
//        'password',
//    ];
    //不知道有什么用


    use HasFactory;

    public function getJWTIdentifier()
    {
        //getKey() 方法用于获取模型的主键值
        return $this->getKey();
    }

    //返回一个包含自定义声明的关联数组。
    public function getJWTCustomClaims(): array
    {
        return ['role' => 'admins'];
    }

    public static function LywPassword($userdata)
    {
        try {
            $data = admins::insert([
                'account'=>$userdata['account'],
                'password' => $userdata['password']
            ]);
            return $data;

        } catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }


}

    // 修改器：在设置密码时自动进行哈希加密


