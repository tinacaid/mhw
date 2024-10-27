<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Exception;
use Illuminate\Notifications\Notifiable;
 // 或者 use Laravel\Sanctum\HasApiTokens;
class research_stars extends Authenticatable implements JWTSubject
{
    protected $table = "research_stars";
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
        return ['role =>research_stars'];
    }
}


