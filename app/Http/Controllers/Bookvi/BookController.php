<?php

namespace App\Http\Controllers\Bookvi;

use App\Http\Model\Book\Category;
use App\Http\Model\Book\PdtContent;
use App\Http\Model\Book\PdtImages;
use App\Http\Model\Book\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
	public function toCategory($value='') {
		$categorys = Category::where('parent_id',0)->get();
		Log::info("进入书籍类别");
		return view('book.category')->with('categorys',$categorys);
	}
	//产品列表
	public function toProduct($category_id) {
		$products = Product::where('cate_id',$category_id)->get();
		return view('book.product')->with('products',$products);
	}
	//产品详情
	public function toPdtContent(Request $request,$product_id) {
		$product = Product::find($product_id);
		$pdt_content = PdtContent::where('product_id',$product_id)->first();
		$pdt_images = PdtImages::where('product_id',$product_id)->get();
		$bk_cart = $request->cookie('bk_cart');
		//定义的格式是{1:3,4:2,2:1} =>  {货品id:数量,货品id:数量,货品id:数量}
		$bk_cart_arr = $bk_cart!=null?explode(',',$bk_cart):array();
		$count = 0;
		foreach ($bk_cart_arr as $value){
			$index = strpos($value,':');
			if (substr($value,0,$index) == $product_id){
				$count = (int)substr($value,$index+1);
				break;
			}
		}
		return view('book.pdt_content')
					->with('product',$product)
					->with('pdt_content',$pdt_content)
					->with('pdt_images',$pdt_images)
					->with('count',$count);
	}
}
