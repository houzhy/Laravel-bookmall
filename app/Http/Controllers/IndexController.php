<?php

namespace App\Http\Controllers;

use App\Http\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    //首页方法
	public function index(){

//		echo 'success';
		//测试数据库连接
//		$pdo = DB::connection()->getPdo();
//		dd($pdo);
//		$users = DB::table('user')->where('user_id','>',2)->get();
//		$users = User::where('user_id',2)->get();
		$users = User::find(1);
//		$users->user_name = 'wangwu';
//		$users->update();
		dd($users);
		return view('welcome');
	}

}
