<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Article;
use App\Http\Model\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ArticleController extends CommonController
{
    //get.admin/article   显示列表
	public function index(){
		$data = (new Article())->orderBy('art_id','desc')->paginate(10);
//		dd($data);
		return view('admin.article.index',compact('data'));
	}
	/**
	 * Show the form for creating a new resource.
	 * get.admin/article/create    添加文章页面显示
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//添加文章
		$data = (new Category)->tree();
		return view('admin.article.add',compact('data'));

	}

	/**
	 * Store a newly created resource in storage.
	 * post. admin/article      添加文章接收提交
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store()
	{
		//提交数据
		$input = Input::except("_token");
		if(is_null($input['art_thumb'])){
			unset($input['art_thumb']);
		}
		$input['art_pass'] = 0;
		$input['art_time'] = time();
		$rules = [
			'art_title'=>'required',
			'art_content'=>'required',
		];
		$message = [
			'art_title.required'=>'文章标题不能为空',
			'art_content.required'=>'内容不能为空',
		];
		$validator = Validator::make($input,$rules,$message);
		if($validator->passes()){
			$res = Article::create($input);
			if($res){
				return redirect('admin/article');
			}else{
				return back()->with('errors','数据填充失败,请重试!');
			}
		}else{
			return back()->withErrors($validator);
		}

	}
	/**
	 * Show the form for editing the specified resource.
	 * get. admin/article/{id)/edit      编辑修改文章
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($art_id)
	{
		//更新
		$data = (new Category)->tree();
		$field = Article::find($art_id);
		return view('admin.article.edit',compact('data','field'));
	}
	/**
	 * Update the specified resource in storage.
	 * put. admin/article/{id}     接收并更新文章数据
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update($art_id)
	{
		//获取更新提交数据
		$input = Input::except("_token","_method");
		if(is_null($input['art_thumb'])){
			unset($input['art_thumb']);
		}
		$res = Article::where('art_id',$art_id)->update($input);
		if($res){
			return redirect('admin/article');
		}else{
			return back()->with('errors','更新数据信息失败,请稍后重试!');
		}
	}
	/**
	 * Remove the specified resource from storage.
	 * delete.  admin/article/{id}     删除操作(删除单个文章)
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($art_id)
	{
		//删除
		$res = Article::where('art_id',$art_id)->delete();
		if($res){
			$data=[
				'status' => 0,
				'msg' => '文章删除成功!',
			];
		}else{
			$data=[
				'status' => 1,
				'msg' => '文章删除失败,请稍后重试!',
			];
		}
		return $data;
	}

}
