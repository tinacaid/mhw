<?php

namespace App\Http\Controllers;

use App\Models\admins;
use Illuminate\Routing\Controller;

use App\Models\students;
use App\Mail\VerificationCode;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Facades\Password;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class LywController extends Controller

{

    public function LywRegistration(Request $request): \Illuminate\Http\JsonResponse// 学生注册
    {
        $sessionCode = Session::get('verification_code'); //在会话层获取验证码
        $sessionEmail = Session::get('email');
        $decodedSessionCode = base64_decode($sessionCode);  //将验证码解密
        //判断验证码是否匹配
        if ($request->verification_code != $decodedSessionCode || $request->email != $sessionEmail)//或运算
        {
            return json_fail('验证码错误或邮箱不匹配', null, 400);
        }
        $data['account'] = $request['account'];
        $data['password'] = bcrypt($request['password']);//加密
        $data['major'] = $request['major'];
        $data['class'] = $request['class'];
        $data['name'] = $request['name'];
        $data['email'] = $request['email'];
        $count = students::where('account', $request->account)->count();//查询
        if ($count != 0) {
            return json_fail('该账号已注册', null, 100);
        }
        try {
            //数据库添加相关数据
            students::LywcreateUser($data);
            return json_success('注册成功', null, 200);
        } catch (\Exception $e) {
            return json_fail('注册失败，请重新注册', $e->getMessage(), 100);
        }

    }

    public function LywStudentLogin(Request $request)// 学生登录接口
    {
        $user['account'] = $request['account'];
        $user['password'] = $request['password'];

        $token = auth('students')->attempt($user);//身份验证

        return $token ?//三目判断
            json_success('登录成功', $token, 200) :
            json_fail('登录失败', $token, 100);
    }


    public function logoutStudent()//学生用户登出
    {
        auth('students')->logout();
        return json_success("退出登录成功", null, 200);
    }

    public function AdminPassword(Request $request)//老师密码加密
    {
        $data['account'] = $request['account'];
       $data['password'] = bcrypt($request['password']);
       try {
           //数据库添加相关数据
           admins::LywPassword($data);
           return json_success('成功', $data, 200);
       } catch (\Exception $e) {
           return json_fail('失败，请重新加密', $e->getMessage(), 100);
       }

    }

    public function LywAdminLogin(Request $request): \Illuminate\Http\JsonResponse//管理员端登录
    {

        $user['account'] = $request['account'];
        $user['password'] = $request['password'];
        $token = auth('admins')->attempt($user);
        return $token ?//三目判断
            json_success('登录成功', $token, 200) :
            json_fail('登录失败', $token, 100);
    }

    public function logoutAdmin(): \Illuminate\Http\JsonResponse//管理员登出
    {
        auth('admins')->logout();
        return json_success("退出登录成功", null, 200);
    }

    public function sendVerificationCode(Request $request): \Illuminate\Http\JsonResponse
    {
        // 验证请求数据
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'sometimes|required' // 验证码字段，有时需要验证
        ]);

        if ($validator->fails()) {
            return json_fail('请求数据格式不正确', null, 400);
        }

        if ($request->has('code')) {
            // 验证验证码
            $verificationCode = Session::get('verification_code');
            $verificationTime = Session::get('verification_time', 0);
            $currentTime = time();

            if ($currentTime - $verificationTime > 60) {
                // 超过一分钟，验证码失效
                return json_fail('验证码已失效，请重新获取', null, 400);
            }

            $enteredCode = $request->input('code');
            $decodedCode = base64_decode($verificationCode);

            if ($enteredCode == $decodedCode) {
                // 验证通过
                return json_success('验证码验证成功', null, 200);
            } else {
                // 验证失败
                return json_fail('验证码错误', null, 400);
            }
        } else {
            // 发送验证码
            $code = rand(100000, 999999); // 生成随机验证码
            $currentTime = time(); // 获取当前时间戳
            $encodedCode = base64_encode($code); // 加密验证码
            Session::put('verification_code', $encodedCode); // 在会话层存储加密后的验证码
            Session::put('verification_time', $currentTime);
            Session::put('email', $request->email);  // 在会话层存储用户邮箱

            try {
                // 发送验证码
                Mail::to($request->email)->send(new VerificationCode($code));

                return json_success('验证码已发送', null, 200);
            } catch (\Exception $e) {
                return json_fail('验证码发送失败，请稍后重试', null, 500);
            }
        }
    }


    public function LywUpdatePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $new_password = $request->input('new_password');
        $confirm_password = $request->input('confirm_password');

        $student = students::where('account', $request->account)->first();

        if (!$student) {
            return json_fail('账号输入错误或未注册', null, 100);
        }

        // 验证验证码
        $sessionCode = Session::get('verification_code');
        $sessionEmail = Session::get('email');
        $decodedSessionCode = base64_decode($sessionCode);

        // 验证验证码是否正确
        if ($request->verification_code != $decodedSessionCode || $request->email != $sessionEmail) {
            return json_fail('验证码错误或邮箱不匹配', null, 400);
        }

        $currentTime = time();
        $lastVerificationTime = Session::get('last_verification_time', 0);

        // 检查验证码发送时间是否超过一分钟
        if ($currentTime - $lastVerificationTime >= 60) {
            // 一分钟限制内，执行密码更新操作
            try {
                $student->update(['password' => Hash::make($new_password)]);
                if ($new_password !== $confirm_password) {
                    return json_fail('两次密码不一致', null, 502);
                }
                return json_success('密码重置成功', null, 100);
            } catch (\Exception $exception) {
                return json_fail('密码重置失败，请重试', null, 501);
            }
        } else {
            return json_fail('请等待一分钟后再尝试更新密码', null, 400);
        }
    }


}
