<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MhwController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Route::post('user/paper_stars/{student_id}', [MhwController::class, 'MhwStudentpaper']);//发表论文
Route::delete('usershanchu/paper_stars/{student_id}', [\App\Http\Controllers\MhwController::class, 'Mhwshanchu']);//学生注册测试
Route::get('userchaxun/paper_stars/{student_id}', [MhwController::class, 'Mhwpapershanxun']);//发表论文
//Route::post('admin/login',[\App\Http\Controllers\MhwController::class,"adminlogin"]);
//Route::post('adminzhuce',[\App\Http\Controllers\MhwController::class,"zhuceadmin"]);
//Route::middleware('jwt.role:admins')->prefix('admins')->group(function () {
   // Route::post('logout',[\App\Http\Controllers\MhwController::class,'adminlogout']);//登出用户
//});



