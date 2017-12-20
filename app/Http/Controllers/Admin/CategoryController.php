<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


class CategoryController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //获取全部数据
	    $cate = new Category;
	    $data = $cate->tree();
//	    dd($data);
	    return view('admin.category.index')->with('data',$data);
    }


    /**
     * Show the form for creating a new resource.
     * get.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //添加分类
	    $data = Category::where('cate_pid',0)->get();
	    return view('admin/category/add',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //提交数据
	    $input = Input::except("_token");
	    $rules = [
		    'cate_name'=>'required',
	    ];
	    $message = [
		    'cate_name.required'=>'分类名称不能为空',
	    ];
	    $validator = Validator::make($input,$rules,$message);
	    if($validator->passes()){
			$res = Category::create($input);
		    if($res){
			    return redirect('admin/category');
		    }else{
			    return back()->with('errors','数据填充失败,请稍后重试!');
		    }
	    }else{
		    return back()->withErrors($validator);
	    }
//	    dd($input);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //更新
	    $field = Category::find($id);
	    $data = Category::where('cate_pid',0)->get();
	    return view('admin.category.edit',compact('field','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($cate_id)
    {
        //获取更新提交数据
	    $input = Input::except("_token","_method");
	    $res = Category::where('cate_id',$cate_id)->update($input);
	    if($res){
			return redirect('admin/category');
	    }else{
		    return back()->with('errors','更新数据信息失败,请稍后重试!');
	    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($cate_id)
    {
        //删除
	    $res = Category::where('cate_id',$cate_id)->delete();
	    Category::where('cate_pid',$cate_id)->update(['cate_pid'=>0]);
	    if($res){
		   $data=[
		   	    'status' => 0,
			    'msg' => '分类删除成功!',
		   ];
	    }else{
		    $data=[
			    'status' => 1,
			    'msg' => '分类删除失败,请稍后重试!',
		    ];
	    }
	    return $data;
    }

    /*
     * 排序ajax
     */
	public function changeOrder() {
		$input = Input::all();
		$cate = Category::find($input['cate_id']);
		$cate->cate_order = $input['cate_order'];
		$re = $cate->update();
		if($re){
			$data = [
				'status' => 0,
				'msg' => '分类排序更新成功!'
			];
		}else{
			$data = [
				'status' => 1,
				'msg' => '分类排序更新失败,请稍后重试!'
			];
		}
		return $data;
    }


}
