<?php


use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WdwController;
use App\Http\Controllers\mohuchaxun;


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

//学生查询竞赛之星信息
Route::GET('/student/competition', [WdwController::class, 'check_competition']);
//学生修改竞赛之星信息
Route::POST('/student/competition/{student_id}', [WdwController::class, 'revise_competition']);
//学生删除竞赛之星信息
Route::DELETE('/student/competition/{student_id}', [WdwController::class, 'delete_competition']);

//学生报名科研之星项目
Route::POST('/student/research/create_project/{student_id}', [WdwController::class, 'create_research']);
//学生查询科研之星项目
Route::GET('/student/research/project', [WdwController::class, 'check_research']);
//学生修改科研之星项目
Route::POST('/student/research/project/{student_id}', [WdwController::class, 'revise_research']);
//学生删除科研之星项目
Route::DELETE('/student/research/project/{student_id}', [WdwController::class, 'delete_research']);
//老师查看竞赛之星状态
Route::GET('/admin/students', [WdwController::class, 'teacher_check_competition']);
//老师修改科研之星项目状态
Route::POST('/admin/students/{student_id}/status', [WdwController::class, 'revise_research_status']);




//Route::post('user/paper_stars/{student_id}', [MhwController::class, 'MhwStudentpaper']);//发表论文
Route::delete('usershanchu/paper_stars/{student_id}', [\App\Http\Controllers\MhwController::class, 'Mhwshanchu']);//学生注册测试
Route::get('userchaxun/paper_stars/{student_id}', [MhwController::class, 'Mhwpapershanxun']);//发表论文
//Route::post('admin/login',[\App\Http\Controllers\MhwController::class,"adminlogin"]);
//Route::post('adminzhuce',[\App\Http\Controllers\MhwController::class,"zhuceadmin"]);
//Route::middleware('jwt.role:admins')->prefix('admins')->group(function () {
   // Route::post('logout',[\App\Http\Controllers\MhwController::class,'adminlogout']);//登出用户
//});






Route::post('/student/research/software',[\App\Http\Controllers\WkxController::class,'chaxun']);//学生查询科研之星
Route::post('/student/research/software/{id}',[\App\Http\Controllers\WkxController::class,'shanchu']);//学生删除


