<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;//引用Authenticatable类使得DemoModel具有用户认证功能
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;




class students extends Authenticatable implements JWTSubject
{

    // 定义可以批量赋值的字段
    protected $fillable = [
        'account',
        'password',
        'major',
        'class',
        'name',
        'email'
    ];
    protected $table = "students";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    // 隐藏密码字段
//    protected $hidden = [
//        'password',
//    ];
    //不知道有什么用
    public static function LywcreateUser($userdata)
    {
        try {
            $data = Students::insert([
                'account' => $userdata['account'],
                'password' => $userdata['password'],
                'major' => $userdata['major'],
                'class' => $userdata['class'],
                'email' => $userdata['email'],
                'name' => $userdata['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return $data;

        } catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    use HasFactory;

    // 修改器：在设置密码时自动进行哈希加密





    // 验证凭证的静态方法
    public static function validateCredentials($account, $password): bool
    {
        try {
            // 查找用户
            $user = self::where('account', $account)->first();

            // 检查用户是否存在以及密码是否正确
            if ($user && Hash::check($password, $user->password)) {
                // 如果密码正确，返回 true
                return true;
            }

            // 如果用户不存在或密码不正确，返回 false
            return false;

        } catch (Exception $e) {
            // 处理异常，记录日志或返回错误信息
            // Log::error('Error validating credentials: ' . $e->getMessage());
            return false;
        }
    }

    public function getJWTIdentifier()
    {
        //getKey() 方法用于获取模型的主键值
        return $this->getKey();
    }

    //返回一个包含自定义声明的关联数组。
    public function getJWTCustomClaims():array
    {
        return ['role' => 'students'];
    }

    public function guestinfo1()
    {
        // hasOne(被关联的名命空间，关联外键，关联的主键)
        //        hasOne
        //用途：hasOne 用于定义当前模型拥有另一个模型的关系。通常用于表示一对一关系中的“主”方。
        //外键位置：外键在另一个模型中。
        //方向：另一个模型指向当前模型。
        return $this->hasOne('App\Models\company_stars', 'student_id', 'id');
    }

    public function guestinfo2()
    {
        // hasOne(被关联的名命空间，关联外键，关联的主键)
//        hasOne
//用途：hasOne 用于定义当前模型拥有另一个模型的关系。通常用于表示一对一关系中的“主”方。
//外键位置：外键在另一个模型中。
//方向：另一个模型指向当前模型。
        return $this->hasOne('App\Models\competition_stars', 'student_id', 'id');
    }

    // 定义与 CompetitionStarRegistration 的一对多关系
    public function competitionRegistrations()
    {
        return $this->hasMany('App\Models\paper_stars', 'student_id');
    }

    // 定义与 InnovationStarRegistration 的一对多关系
    public function innovationRegistrations()
    {
        return $this->hasMany('App\Models\research_stars', 'student_id');
    }

    // 定义与 ScienceStarRegistration 的一对多关系
    public function scienceRegistrations()
    {
        return $this->hasMany('App\Models\software_stars', 'student_id');
    }
}
