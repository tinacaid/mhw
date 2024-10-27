<?php

namespace App\Models;

 masteruse Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class students extends Model
{
    use HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Exception;
use Illuminate\Notifications\Notifiable;
 // 或者 use Laravel\Sanctum\HasApiTokens;
class students extends Authenticatable implements JWTSubject
{
    protected $table = "students";
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
        return ['role => students'];
    }

}





