<?php

namespace App\Http\Controllers;

use App\Models\company_stars;
use App\Models\competition_stars;
use App\Models\paper_stars;
use App\Models\research_stars;
use App\Models\software_stars;
use App\Models\students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // 引入 Storage 类，用于处理文件上传到 OSS
use Illuminate\Support\Facades\Auth;

class WdwController extends Controller
{

    // 封装上传文件到阿里云 OSS 的逻辑
    function uploadFileToOSS($file, $directory = 'uploads/')
    {
        // 生成唯一的文件名，保持原始文件扩展名
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        // 设置上传的文件路径
        $filePath = $directory . $fileName;

        // 上传文件到 OSS
        $ossPath = Storage::disk('oss')->put($filePath, file_get_contents($file));

        // 如果上传成功，返回文件的外部访问 URL
        if ($ossPath) {
            return Storage::disk('oss')->url($filePath); // 获取上传文件的 URL
        }

        // 如果上传失败，返回 null
        return null;
    }


    //学生报名竞赛之星
    function create_competition(Request $request)
    {
        // 检查是否已存在同一学生对同一竞赛项目的申请
        $existingApplication = competition_stars::where('student_id', $request['student_id'])
            ->where('competition_name', $request['competition_name'])
            ->first();

        if ($existingApplication) {
            return json_fail('同一竞赛项目已申请过，不能重复报名', null, 100);
        }


        // 验证上传文件的类型为 PDF
        $request->validate([
            'file' => 'required|mimes:pdf',
        ]);

        // 调用上传函数并获取文件的 URL
        $fileUrl =$this->uploadFileToOSS($request->file('file'));

        if ($fileUrl) {
            // 准备存储到数据库的文件信息
            $data = [
                'materials' => $fileUrl,   // 上传文件的 URL
                'student_id' => $request['student_id'],
                'competition_name' => $request['competition_name'],
                'registration_time' => $request['registration_time'],
            ];

            // 创建记录
            $p = competition_stars::create($data);

            // 根据创建结果返回相应的响应
            if (is_error($p)) {
                return json_fail('报名失败', null, 100);
            }
            return json_success('报名成功', $data, 200);
        }
        return json_fail('文件上传失败', null, 100);
    }

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
        $request->validate([
            'file' => 'required|mimes:pdf', // 限制文件类型为 PDF
        ]);
        $fileUrl =$this->uploadFileToOSS($request->file('file'));

        if ($fileUrl) {
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
    function create_research_project(Request $request)
    {
        $existingApplication = research_stars::where('student_id', $request['student_id'])
            ->where('project_name', $request['project_name'])
            ->first();

        if ($existingApplication) {
            return json_fail('同一科研项目已申请过，不能重复报名', null, 100);
        }

        // 验证上传文件的类型为 PDF
        $request->validate([
            'file' => 'required|mimes:pdf',
        ]);

        // 调用上传函数并获取文件的 URL
        $fileUrl =$this->uploadFileToOSS($request->file('file'));

        if ($fileUrl) {
            // 准备存储到数据库的文件信息
            $data = [
                'materials' => $fileUrl,   // 上传文件的 URL
                'student_id' => $request['student_id'],
                'project_name' => $request['project_name'],
                'project_level' => $request['project_level'],
                'ranking_total' => $request['ranking_total'],
                'approval_time' => $request['approval_time']
            ];

            // 创建记录
            $p = research_stars::create($data);

            // 根据创建结果返回相应的响应
            if (is_error($p)) {
                return json_fail('报名失败', null, 100);
            }
            return json_success('报名成功', $data, 200);
        }

        return json_fail('文件上传失败', null, 100);
    }

    //学生查询科研之星项目信息
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
            function revise_research_project(Request $request)
            {
                $request->validate([
                    'file' => 'required|mimes:pdf', // 限制文件类型为 PDF
                ]);
                $fileUrl =$this->uploadFileToOSS($request->file('file'));
                if ($fileUrl) {

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
            function delete_research_project(Request $request)
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

    function revise_application_status(Request $request)
    {
        $student_id = $request['student_id'];
        $status = $request['status'];
        $application_type = $request['application_type'];  // 获取申请类型 ('competition', 'research', 'innovation')

        // 检查如果 status 为不通过，需要理由
        if ($status === '不通过' && empty($request['reason'])) {
            return json_fail('不通过状态必须提供理由', null, 101);
        }

        // 准备要更新的数据
        $data['status'] = $status;

        // 如果 status 是不通过，则添加理由到 $data
        if ($status === '不通过') {
            $data['reason'] = $request['reason'];
        } else {
            $data['reason'] = null; // 通过时清除理由
        }

        // 根据申请类型来确定需要修改的表
        switch ($application_type) {
            case 'competition':  // 竞赛之星
                $p = competition_stars::revise_status($student_id, $data);
                break;

            case 'innovation':  // 双创之星
                $p = company_stars::revise_status($student_id, $data);
                break;

            case 'research_paper':  // 科研之星论文
                $p = paper_stars::revise_status($student_id, $data);
                break;

            case 'research_software':  // 科研之星软件
                $p = software_stars::revise_status($student_id, $data);
                break;

            case 'research_project':  // 科研之星项目
                $p = research_stars::revise_status($student_id, $data);
                break;
            default:
                return json_fail('无效的申请类型', null, 102);  // 无效的申请类型处理
        }


        if (is_error($p)) {
            return json_fail('修改失败', $p, 100);
        }

        return json_success('修改成功', $p, 200);
    }

            //学生报名科研之星论文
    function create_research_paper(Request $request)
    {
        $existingApplication = paper_stars::where('student_id', $request['student_id'])
            ->where('journal_name', $request['journal_name'])
            ->first();

        if ($existingApplication) {
            return json_fail('同一科研项目已申请过，不能重复报名', null, 100);
        }

        // 验证上传文件的类型为 PDF
        $request->validate([
            'file' => 'required|mimes:pdf',
        ]);

        // 调用上传函数并获取文件的 URL
        $fileUrl =$this->uploadFileToOSS($request->file('file'));

        if ($fileUrl) {
            // 准备存储到数据库的文件信息
            $data = [
                'materials' => $fileUrl,   // 上传文件的 URL
                'journal_name' => $request['journal_name'],
                'paper_title' => $request['paper_title'],
                'publication_time' => $request['publication_time'],
                'student_id'=>$request['student_id'],
                'ranking_total'=>$request['ranking_total'],
            ];

            // 创建记录
            $p = paper_stars::create($data);

            // 根据创建结果返回相应的响应
            if (is_error($p)) {
                return json_fail('报名失败', null, 100);
            }
            return json_success('报名成功', $data, 200);
        }
        return json_fail('文件上传失败', null, 100);
    }

    //学生修改科研之星论文
    function revise_research_paper(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf', // 限制文件类型为 PDF
        ]);
        $fileUrl =$this->uploadFileToOSS($request->file('file'));
        if ($fileUrl) {

            // 准备存储到数据库的文件信息，包括文件名和文件 URL
            $data = [
                //'filename' => $fileName, // 上传文件的名称
                'materials' => $fileUrl   // 上传文件的 URL
            ];
        }
        $student_id = $request['student_id'];
        $data['journal_name'] = $request['journal_name'];
        $data['paper_title'] = $request['paper_title'];
        $data['publication_time'] = $request['publication_time'];
        $data['ranking_total'] = $request['ranking_total'];
        $p = paper_stars::revise($student_id, $data);
        if (is_error($p) == true) {
            return json_fail('修改失败', $p, 100);
        }
        return json_success('修改成功', $p, 200);
    }

    //学生报名科研之星软件
    function create_research_software(Request $request)
    {

        $existingApplication = software_stars::where('student_id', $request['student_id'])
            ->where('software_name', $request['software_name'])
            ->first();

        if ($existingApplication) {
            return json_fail('同一科研项目已申请过，不能重复报名', null, 100);
        }
        // 验证上传文件的类型为 PDF
        $request->validate([
            'file' => 'required|mimes:pdf',
        ]);

        // 调用上传函数并获取文件的 URL
        $fileUrl =$this->uploadFileToOSS($request->file('file'));

        if ($fileUrl) {
            // 准备存储到数据库的文件信息
            $data = [
                'materials' => $fileUrl,   // 上传文件的 URL
                'software_name' => $request['software_name'],
                'issuing_unit' => $request['issuing_unit'],
                'approval_time' => $request['approval_time'],
                'student_id'=>$request['student_id'],
                'ranking_total'=>$request['ranking_total'],
            ];

            // 创建记录
            $p = software_stars::create($data);

            // 根据创建结果返回相应的响应
            if (is_error($p)) {
                return json_fail('报名失败', null, 100);
            }
            return json_success('报名成功', $data, 200);
        }
        return json_fail('文件上传失败', null, 100);
    }

    //学生修改科研之星软件
    function revise_research_software(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf', // 限制文件类型为 PDF
        ]);
        $fileUrl =$this->uploadFileToOSS($request->file('file'));
        if ($fileUrl) {

            // 准备存储到数据库的文件信息，包括文件名和文件 URL
            $data = [
                //'filename' => $fileName, // 上传文件的名称
                'materials' => $fileUrl   // 上传文件的 URL
            ];
        }
        $student_id = $request['student_id'];
        $data['software_name'] = $request['software_name'];
        $data['issuing_unit'] = $request['issuing_unit'];
        $data['approval_time'] = $request['approval_time'];
        $data['ranking_total'] = $request['ranking_total'];
        $p = software_stars::revise($student_id, $data);
        if (is_error($p) == true) {
            return json_fail('修改失败', $p, 100);
        }
        return json_success('修改成功', $p, 200);
    }

    //学生报名双创之星
    function create_company(Request $request)
    {
        $existingApplication = company_stars::where('student_id', $request['student_id'])
            ->where('company_name', $request['company_name'])
            ->first();

        if ($existingApplication) {
            return json_fail('同一科研项目已申请过，不能重复报名', null, 100);
        }

        // 验证上传文件的类型为 PDF
        $request->validate([
            'file' => 'required|mimes:pdf',
        ]);

        // 调用上传函数并获取文件的 URL
        $fileUrl =$this->uploadFileToOSS($request->file('file'));

        if ($fileUrl) {
            // 准备存储到数据库的文件信息
            $data = [
                'materials' => $fileUrl,   // 上传文件的 URL
                'company_name' => $request['company_name'],
                'company_type' => $request['company_type'],
                'registration_time' => $request['registration_time'],
                'student_id'=>$request['student_id'],
                'applicant_rank'=>$request['applicant_rank'],
                'company_scale'=>$request['company_scale'],
            ];

            // 创建记录
            $p = company_stars::create($data);

            // 根据创建结果返回相应的响应
            if (is_error($p)) {
                return json_fail('报名失败', null, 100);
            }
            return json_success('报名成功', $data, 200);
        }
        return json_fail('文件上传失败', null, 100);
    }

    //学生修改双创之星信息
    function revise_company(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf', // 限制文件类型为 PDF
        ]);
        $fileUrl =$this->uploadFileToOSS($request->file('file'));
        if ($fileUrl) {

            // 准备存储到数据库的文件信息，包括文件名和文件 URL
            $data = [
                //'filename' => $fileName, // 上传文件的名称
                'materials' => $fileUrl   // 上传文件的 URL
            ];
        }
        $student_id = $request['student_id'];
        $data['company_name'] = $request['company_name'];
        $data['company_type'] = $request['company_type'];
        $data['registration_time'] = $request['registration_time'];
        $data['applicant_rank'] = $request['applicant_rank'];
        $data['company_scale'] = $request['company_scale'];
        $p = company_stars::revise($student_id, $data);
        if (is_error($p) == true) {
            return json_fail('修改失败', $p, 100);
        }
        return json_success('修改成功', $p, 200);
    }

    public function search(Request $request)
    {
        $teacherMajor = $request->input('teacher_major'); // 可以提供默认值
        // 获取搜索字段
        //$searchFields = $request->only('name', 'major', 'c_name', 'status');
        $searchFields = $request->input('search_fields', []);
        //dd($searchFields);
        // 如果所有搜索字段都为空，直接返回空结果
        if (empty(array_filter($searchFields))) {
            return response()->json([]);
        }

        // 过滤掉空字符串的搜索字段
        $searchFields = array_filter($searchFields, function ($value) {
            return !empty($value); // 过滤掉空值和空字符串
        });

        // 将专业字段传递到搜索函数中
        $students = students::searchStudents($searchFields, $teacherMajor);

        return response()->json($students);
    }
}
