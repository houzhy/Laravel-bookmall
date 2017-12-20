<?php

namespace App\Http\Controllers\Service;

use App\Models\M3Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
	public function addCart(Request $request,$product_id) {
		$bk_cart = $request->cookie('bk_cart');
//		dd($bk_cart);
		//定义的格式是{1:3,4:2,2:1} =>  {货品id:数量,货品id:数量,货品id:数量}
		$bk_cart_arr = $bk_cart!=null?explode(',',$bk_cart):array();
		$count = 1;
		foreach ($bk_cart_arr as &$value){
			$index = strpos($value,':');
			if (substr($value,0,$index) == $product_id){
				$count = ((int)substr($value,$index+1))+1;
				$value = $product_id.':'.$count;
				break;
			}
		}
		if($count == 1){
			array_push($bk_cart_arr,$product_id.':'.$count);
		}
		$m3_result = new M3Result();
		$m3_result->status = 0;
		$m3_result->message = '添加成功';

		return response($m3_result->toJson())->withCookie('bk_cart',implode(',',$bk_cart_arr));
    }
}
