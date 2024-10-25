<?php

namespace App\Http\Controllers;

use App\Models\software_stars;
use http\Env\Response;
use Illuminate\Http\Request;

class WkxController extends Controller
{
    public function chaxun(Request $request)              //查询自己信息
    {
        $student_id=$request->input('student_id');

        if (empty($student_id)) {
            return response()->json(['errpr' => '请输入学号'], 400);
        }
        $project=software_stars::cha($student_id);
        return $project;
    }


    public function shanchu(Request $request)           //删除自己的
    {
        $id=$request->input('id');
        $count=software_stars::id_shan($id);

        if ($count==1){
            $data=software_stars::shan($id);

            if (is_error($data)==true){
                return json_fail('失败',$data,100);
            }
            return json_success('成功',$data,200);
        }
        else{
            return json_fail('删除失败，输入id为空');
        }
    }
}
