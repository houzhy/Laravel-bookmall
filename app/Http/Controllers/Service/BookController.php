<?php

namespace App\Http\Controllers\Service;

use App\Http\Model\Book\Category;
use App\Models\M3Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    //子分类
	public function getCategroyByParentId($parent_id) {
		$categorys = Category::where('parent_id',$parent_id)->get();
		$m3_result = new M3Result();
		$m3_result->status = 0;
		$m3_result->message = '返回成功';
		$m3_result->categorys = $categorys;
		return $m3_result->toJson();
	}
}
