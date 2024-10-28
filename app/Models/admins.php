<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Exception;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens; // 或者 use Laravel\Sanctum\HasApiTokens;
class admins extends Authenticatable implements JWTSubject
{
    protected $table = "admins";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    use Notifiable;
    use HasApiTokens;
    public function getJWTIdentifier()
    {
        //getKey() 方法用于获取模型的主键值
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role => admins'];
    }
}




