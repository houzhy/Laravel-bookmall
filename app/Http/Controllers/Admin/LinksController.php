<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Links;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $data = Links::orderBy('link_order','asc')->get();
        return view('admin.links.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    //添加友情链接
	    return view('admin/links/add');
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
		    'link_name'=>'required',
		    'link_url'=>'required',
	    ];
	    $message = [
		    'link_name.required'=>'名称不能为空',
		    'link_url.required'=>'链接不能为空',
	    ];
	    $validator = Validator::make($input,$rules,$message);
	    if($validator->passes()){
		    $res = Links::create($input);
		    if($res){
			    return redirect('admin/links');
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
	    $field = Links::find($id);
	    return view('admin.links.edit',compact('field'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $link_id)
    {
	    //获取更新提交数据
	    $input = Input::except("_token","_method");
	    $res = Links::where('link_id',$link_id)->update($input);
	    if($res){
		    return redirect('admin/links');
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
    public function destroy($link_id)
    {
	    //删除
	    $res = Links::where('link_id',$link_id)->delete();
	    if($res){
		    $data=[
			    'status' => 0,
			    'msg' => '友情链接删除成功!',
		    ];
	    }else{
		    $data=[
			    'status' => 1,
			    'msg' => '友情链接删除失败,请稍后重试!',
		    ];
	    }
	    return $data;
    }
	/*
	 * 排序ajax
	 */
	public function changeOrder() {
		$input = Input::all();
		$links = Links::find($input['link_id']);
		$links->link_order = $input['link_order'];
		$re = $links->update();
		if($re){
			$data = [
				'status' => 0,
				'msg' => '友情链接排序更新成功!'
			];
		}else{
			$data = [
				'status' => 1,
				'msg' => '友情链接排序更新失败,请稍后重试!'
			];
		}
		return $data;
	}
}
