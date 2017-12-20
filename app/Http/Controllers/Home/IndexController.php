<?php

namespace App\Http\Controllers\Home;


use App\Http\Model\Article;
use App\Http\Model\Category;
use App\Http\Model\Links;

class IndexController extends CommonController
{
	//博客首页
    public function index(){
	    /*
	     * 点击量最高6篇文章  $hot
	     * 图文列表(带分页)  $data
	     * 最新发布文章8篇  $new
	     * 随机推荐5篇文章  $art_rand
	     * 友情链接  $links
	     * 网站配置项
	     */
	    $data = Article::where('art_pass', 1)->orderBy('art_time','desc')->paginate(5);
        $links = Links::orderBy('link_order','asc')->get();
	   return view('home.index',compact('data','links'));
    }
    //列表
	public function cate($cate_id) {
		$field = Category::find($cate_id);
		//带分页分类文章
		$data = Article::where(['art_pass'=> 1,'cate_id'=>$cate_id])->orderBy('art_time','desc')->paginate(5);
		//当前分类的子分类
		$submenu = Category::where('cate_pid',$cate_id)->get();
		//分类查看次数自增
		Category::where('cate_id',$cate_id)->increment('cate_view');

		return view('home.list',compact('field','data','submenu'));
	}
	//文章
	public function article($art_id) {
		$field = Article::where('art_pass', 1)->Join('category','article.cate_id','=','category.cate_id')->find($art_id);
		//文章查看次数自增
		Article::where('art_id',$art_id)->increment('art_view');
		$article['pre'] = Article::where('art_id','<',$art_id)->where(['art_pass'=> 1,'cate_id'=>$field['cate_id']])->orderBy('art_id','desc')->first();
		$article['next'] = Article::where('art_id','>',$art_id)->where(['art_pass'=> 1,'cate_id'=>$field['cate_id']])->orderBy('art_id','esc')->first();
		//相关文章
		$data = Article::where(['art_pass'=> 1,'cate_id'=>$field->cate_id])->orderBy('art_id','desc')->take(6)->get();
		return view('home.new',compact('field','article','data'));
	}
}
