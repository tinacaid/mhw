<?php

namespace App\Http\Controllers;
use App\Models\admins;
use   Illuminate\Routing\Controller as Controller;
use Illuminate\Http\Request;
use App\Models\paper_stars;
use Illuminate\Support\Facades\Storage;


class MhwController extends Controller//发表论文
{
   

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


 
