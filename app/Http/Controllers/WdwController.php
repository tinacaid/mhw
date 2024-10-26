<?php

namespace App\Http\Controllers;

use App\Models\competition_stars;
use App\Models\research_stars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // 引入 Storage 类，用于处理文件上传到 OSS

class WdwController extends Controller
{
    //学生查询竞赛之星信息
    function check_competition(Request $request)
    {
        $student_id = $request['student_id'];
        $data = competition_stars::check($student_id);
        if (is_error($data) == true) {
            return json_fail('查询失败', null, 100);
        }
        return json_success('查询成功', $data, 200);
    }

    //学生修改竞赛之星
    function revise_competition(Request $request)
    {
        $file = $request->file('file'); // 从请求中获取名为 'file' 的上传文件
        // 生成唯一的文件名（避免文件名冲突），保持原始文件扩展名
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension(); // 使用 PHP 函数 uniqid() 生成唯一 ID，加上文件扩展名
        // 设置上传的文件路径，上传到 OSS 的 'uploads/' 目录
        $filePath = 'uploads/' . $fileName;
        // 上传文件到阿里云 OSS
        // 使用 Storage::disk('oss') 指定阿里云 OSS 存储盘，并调用 put() 方法上传文件，传入文件路径和文件内容
        $ossPath = Storage::disk('oss')->put($filePath, file_get_contents($file));
        if ($ossPath) {
            // 获取上传文件的 URL，oss 存储盘会自动生成文件的外部访问 URL
            $fileUrl = Storage::disk('oss')->url($filePath);

            // 准备存储到数据库的文件信息，包括文件名和文件 URL
            $data = [
                //'filename' => $fileName, // 上传文件的名称
                'materials' => $fileUrl   // 上传文件的 URL
            ];

            $student_id = $request['student_id'];
            $data['competition_name'] = $request['competition_name'];
            $data['registration_time'] = $request['registration_time'];
            $p = competition_stars::revise($student_id, $data);
            if (is_error($p) == true) {
                return json_fail('修改失败', $p, 100);
            }
            return json_success('修改成功', $p, 200);
        }
    }

        //学生删除竞赛之星
        function delete_competition(Request $request)
        {
            $student_id = $request['student_id'];
            $p = competition_stars::deleted($student_id);
            if (is_error($p) == true) {
                return json_fail('删除失败', $p, 100);
            }
            return json_success('删除成功', $p, 200);
        }

        //学生报名科研之星项目
        function create_research(Request $request)
        {
            $file = $request->file('file'); // 从请求中获取名为 'file' 的上传文件
            // 生成唯一的文件名（避免文件名冲突），保持原始文件扩展名
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension(); // 使用 PHP 函数 uniqid() 生成唯一 ID，加上文件扩展名
            // 设置上传的文件路径，上传到 OSS 的 'uploads/' 目录
            $filePath = 'uploads/' . $fileName;
            // 上传文件到阿里云 OSS
            // 使用 Storage::disk('oss') 指定阿里云 OSS 存储盘，并调用 put() 方法上传文件，传入文件路径和文件内容
            $ossPath = Storage::disk('oss')->put($filePath, file_get_contents($file));
            if ($ossPath) {
                // 获取上传文件的 URL，oss 存储盘会自动生成文件的外部访问 URL
                $fileUrl = Storage::disk('oss')->url($filePath);

                // 准备存储到数据库的文件信息，包括文件名和文件 URL
                $data = [
                    //'filename' => $fileName, // 上传文件的名称
                    'materials' => $fileUrl   // 上传文件的 URL
                ];

                $data['student_id'] = $request['student_id'];
                $data['project_name'] = $request['project_name'];
                $data['project_level'] = $request['project_level'];
                $data['ranking_total'] = $request['ranking_total'];
                $data['approval_time'] = $request['approval_time'];
                // $data['materials'] = $request['materials'];
                $p = research_stars::create($data);
                if (is_error($p) == true) {
                    return json_fail('报名失败', null, 100);
                }
                return json_success('报名成功', $data, 200);
            }
        }

            //学生查询自己的科研之星
            function check_research(Request $request)
            {
                $student_id = $request['student_id'];
                $data = research_stars::check($student_id);
                if (is_error($data) == true) {
                    return json_fail('查询失败', null, 100);
                }
                return json_success('查询成功', $data, 200);
            }

            //学生修改科研之星项目信息
            function revise_research(Request $request)
            {
                $file = $request->file('file'); // 从请求中获取名为 'file' 的上传文件
                // 生成唯一的文件名（避免文件名冲突），保持原始文件扩展名
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension(); // 使用 PHP 函数 uniqid() 生成唯一 ID，加上文件扩展名
                // 设置上传的文件路径，上传到 OSS 的 'uploads/' 目录
                $filePath = 'uploads/' . $fileName;
                // 上传文件到阿里云 OSS
                // 使用 Storage::disk('oss') 指定阿里云 OSS 存储盘，并调用 put() 方法上传文件，传入文件路径和文件内容
                $ossPath = Storage::disk('oss')->put($filePath, file_get_contents($file));
                if ($ossPath) {
                    // 获取上传文件的 URL，oss 存储盘会自动生成文件的外部访问 URL
                    $fileUrl = Storage::disk('oss')->url($filePath);

                    // 准备存储到数据库的文件信息，包括文件名和文件 URL
                    $data = [
                        //'filename' => $fileName, // 上传文件的名称
                        'materials' => $fileUrl   // 上传文件的 URL
                    ];
                }
                $student_id = $request['student_id'];
                $data['project_name'] = $request['project_name'];
                $data['project_level'] = $request['project_level'];
                $data['ranking_total'] = $request['ranking_total'];
                $data['approval_time'] = $request['approval_time'];
                $p = research_stars::revise($student_id, $data);
                if (is_error($p) == true) {
                    return json_fail('修改失败', $p, 100);
                }
                return json_success('修改成功', $p, 200);
            }

            //学生删除科研之星项目
            function delete_research(Request $request)
            {
                $student_id = $request['student_id'];
                $p = research_stars::deleted($student_id);
                if (is_error($p) == true) {
                    return json_fail('删除失败', $p, 100);
                }
                return json_success('删除成功', $p, 200);
            }

            //老师查询竞赛之星状态
            function teacher_check_competition(Request $request)
            {
                $data = competition_stars::teacher_check();
                if (is_error($data) == true) {
                    return json_fail('查询失败', null, 100);
                }
                return json_success('查询成功', $data, 200);
            }

            //老师修改科研之星项目状态

            function revise_research_status(Request $request)
            {
                $student_id = $request['student_id'];
                $data['status'] = $request['status'];
                $p = research_stars::revise_status($student_id, $data);
                if (is_error($p) == true) {
                    return json_fail('修改失败', $p, 100);
                }
                return json_success('修改成功', $p, 200);
            }
}
