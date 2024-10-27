<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WdwController;
use App\Http\Controllers\LywController;

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






Route::post('/student/login', [LywController::class, 'LywStudentLogin']);//学生登录
Route::post('/admin/login', [LywController::class, 'LywAdminLogin']);//老师登录
Route::post('/student/register', [LywController::class, 'LywRegistration']);//学生注册
Route::post('/student/sendVerificationCode', [LywController::class, 'sendVerificationCode'])->name('send.verification.code');//验证码
Route::post('student/forgotPassword', [LywController::class, 'forgotPassword']);//忘记密码

Route::middleware('jwt.role:students')->prefix('students')->group(function () {
    Route::post('/logout', [LywController::class, 'logoutStudent']);
});// 学生登出接口

Route::middleware('jwt.role:admins')->prefix('admins')->group(function () {
    Route::post('logout', [LywController::class, 'logoutAdmin']);
});// 管理员登出接口
Route::post('user/forgotPassword', [LywController::class, 'LywUpdatePassword']);//忘记密码

Route::post('admin/password', [LywController::class, 'AdminPassword']);//老师密码加密


