<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    //图片上传
	public function upload(){
		$file=Input::file('Filedata');
		//检测上传文件是否有效
		if($file->isValid()){
//			$realPath = $file -> getRealPath();//临时文件绝对路径
			$entension = $file -> getClientOriginalExtension();  //上传文件的后缀
			$newName = date('YmdHis').mt_rand(100,999).'.'.$entension; //文件重命名
			$path = $file -> move(base_path().'/uploads',$newName);//移动文件
			$filepath = 'uploads/'.$newName;
			return $filepath;
		}

	}
}
