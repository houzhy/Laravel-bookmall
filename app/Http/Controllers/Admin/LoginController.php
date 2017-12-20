<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

require_once 'resources/org/code/Code.class.php';

class LoginController extends CommonController
{
    //登录页面
	public function login() {
		if($input=Input::all()){
			$code = new \Code;
			$_code = $code->get();
			if(strtoupper($input['code']) != $_code){
				return back()->with('msg','验证码错误');
			}
			$user = User::first();
			if($user->user_name != $input['user_name'] || Crypt::decrypt($user->user_pass)!=$input['user_pass']){
				return back()->with('msg','用户名或密码错误');
			}
			session(['user'=>$user]);
			return redirect('admin/index');
		}else{
//			session(['user'=>null]);
			//显示登录页
			return view('admin.login');
		}
	}
	//退出
	public function quit() {
		session(['user'=>null]);
		return redirect('admin/login');
	}
	//验证码
	public function code() {
		$code = new \Code;
		$code->make();
	}
//	//密码加密测试
//	public function crypt() {
//		$str = '123456';
//		echo Crypt::encrypt($str);
//	}
}
