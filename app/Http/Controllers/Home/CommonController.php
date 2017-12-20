<?php

namespace App\Http\Controllers\Home;

use App\Http\Model\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Model\Navs;
use Illuminate\Support\Facades\View;

class CommonController extends Controller
{
	public function __construct() {
		$navs = Navs::all();
		/*
		 * 点击量最高6篇文章  $hot
		 * 最新发布文章8篇  $new
		 * 随机推荐5篇文章  $art_rand
		 */
		$hot = Article::where('art_pass', 1)->orderBy('art_view','desc')->take(6)->get();
		$new = Article::where('art_pass', 1)->orderBy('art_time','desc')->take(8)->get();
		//随机8篇
		$collection = Article::where('art_pass', 1)->get(['art_id'])->pluck('art_id');
		$shuffled = $collection->shuffle()->all();
		$art_ids = array_slice($shuffled, 0, 5);
		$art_rand = Article::find($art_ids);
		View::share('navs',$navs);
		View::share('hot',$hot);
		View::share('new',$new);
		View::share('art_rand',$art_rand);
    }
}
