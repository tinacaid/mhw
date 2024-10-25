<?php

namespace App\Http\Controllers;
use App\Models\admins;
use   Illuminate\Routing\Controller as Controller;
use Illuminate\Http\Request;
use App\Models\paper_stars;
use Illuminate\Support\Facades\Storage;


class MhwController extends Controller//发表论文
{
    // public function MhwStudentpaper(Request $request)
    //  {
    //    $data ['journal_name'] = $request['journal_name'];//期刊名称
    //   $data['paper_title'] = $request['paper_title'];//论文名称
    //    $data ['publication_time'] = $request['publication_time'];//发表时间
    //    $data['materials'] = $request['materials'];//oss上传材料连接
    //      $data['student_id'] = $request['student_id'];
    //     $tt = paper_stars::lunwen($data);
    //   if (!is_error($tt) == true) {
    //      return json_success('添加成功！', $data, 200);
    //  } else {
    //       return json_fail('添加失败！', null, 100);
    //   }
//}

    public function Mhwpapershanxun(Request $request)
    {
        $data['student_id'] = $request['student_id'];
        $ss = paper_stars::FindDate($data);
        if (!is_error($ss) == true) {
            // 查询成功
            return json_success(
                '查询成功',
                $ss,
                200
            );
        } else {
            // 无信息
            return json_fail(
                '查询失败,请输入正确的ID！', null, 100
            );

        }
    }

    public function Mhwshanchu(Request $request)
    {
        $data['student_id'] = $request['student_id'];
        $oo = paper_stars::shuliang($data);
        if (!$oo == 0) {
            $ee = paper_stars::shanchu($data);
            if (!is_error($ee) == true) {
                return json_success(
                    '删除成功！',
                    null,
                    200
                );
            }
        } else {
            // 无信息
            return json_fail(
                '删除失败！请输入正确的ID！', null, 100
            );
        }
    }
}


    //管理员注册
   /* public function zhuceadmin(Request $request)
    {
        $data['account'] = $request['account'];
        $data['password'] = bcrypt($request['password']);
        $account = $data['account'];
        $data['account'] = $request['account'];
        $data['major'] = $request['major'];
        $count = admins::shuliang($account);
        if ($count == 0) {
            $zhu = admins::zhuce($data);
            if (is_error($zhu== true)) {
                return json_fail('注册失败!检测是否存在的时候出错啦', $count, 100);
            } else {
                return json_success('账号注册成功！',null,200);
            }
        } else {
            return json_fail('已存在该学号的账号',null,101);
        }
    }
    // 从请求中获取账号和密码

    //管理员登录
    public function adminlogin(Request $request)
    {
        $user['account'] = $request['account'];//账号
        $user['password'] = $request['password'];//密码

        $token = auth('admins')->attempt($user);
        return $token ?
            json_success('登录成功!', $token, 200) :
            json_fail('登录失败!账号或密码错误!!', null, 100);
    }

    public function adminlogout()
    {//登出操作
        auth('admins')->logout();
        return json_success("管理者退出登录成功！", null, 200);
    }
}*/
