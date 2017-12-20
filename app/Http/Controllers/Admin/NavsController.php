<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Navs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class NavsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $data = Navs::orderBy('nav_order','asc')->get();
        return view('admin.navs.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    //添加首页自定义导航
	    return view('admin/navs/add');
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
		    'nav_name'=>'required',
		    'nav_url'=>'required',
	    ];
	    $message = [
		    'nav_name.required'=>'名称不能为空',
		    'nav_url.required'=>'链接不能为空',
	    ];
	    $validator = Validator::make($input,$rules,$message);
	    if($validator->passes()){
		    if(stripos($input['nav_url'],'http://')!==0){
			    $input['nav_url'] = 'http://'.$input['nav_url'];
		    }
		    $res = Navs::create($input);
		    if($res){
			    return redirect('admin/navs');
		    }else{
			    return back()->with('errors','数据填充失败,请稍后重试!');
		    }
	    }else{
		    return back()->withErrors($validator);
	    }
    }

    /**
     * Display the specified resource.
     * get. admin/category/{id}    显示单个信息
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
	    $field = Navs::find($id);
	    return view('admin.navs.edit',compact('field'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nav_id)
    {
	    //获取更新提交数据
	    $input = Input::except("_token","_method");
	    if(stripos($input['nav_url'],'http://')!==0){
		    $input['nav_url'] = 'http://'.$input['nav_url'];
	    }
	    $res = Navs::where('nav_id',$nav_id)->update($input);
	    if($res){
		    return redirect('admin/navs');
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
    public function destroy($nav_id)
    {
	    //删除
	    $res = Navs::where('nav_id',$nav_id)->delete();
	    if($res){
		    $data=[
			    'status' => 0,
			    'msg' => '首页自定义导航删除成功!',
		    ];
	    }else{
		    $data=[
			    'status' => 1,
			    'msg' => '首页自定义导航删除失败,请稍后重试!',
		    ];
	    }
	    return $data;
    }
	/*
	 * 排序ajax
	 */
	public function changeOrder() {
		$input = Input::all();
		$navs = Navs::find($input['nav_id']);
		$navs->nav_order = $input['nav_order'];
		$re = $navs->update();
		if($re){
			$data = [
				'status' => 0,
				'msg' => '首页自定义导航排序更新成功!'
			];
		}else{
			$data = [
				'status' => 1,
				'msg' => '首页自定义导航排序更新失败,请稍后重试!'
			];
		}
		return $data;
	}
}
