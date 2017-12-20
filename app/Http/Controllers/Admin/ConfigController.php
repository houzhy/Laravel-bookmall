<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $data = Config::orderBy('conf_order','asc')->get();
	    foreach ($data as $k=>$v){
		    switch ($v->field_type){
			    case 'input':
					$data[$k]->_html = '<input type="text" class="lg" name="conf_content[]" value="'.$v->conf_content.'">';
			    	break;
			    case 'textarea':
				    $data[$k]->_html = '<textarea type="text"  class="lg"  name="conf_content[]">'.$v->conf_content.'</textarea>';
			    	break;
			    case 'radio':
			    	$arr = explode(',',$v->field_value);
					$str = '';
					foreach ($arr as $m=>$n){
						$r = explode('|',$n);
						$c = $v->conf_content == $r[0]?' checked ':'';
						$str .= '<input type="radio" name="conf_content[]" value="'.$r[0].'"'.$c.'>'.$r[1].'　';
					}
				    $data[$k]->_html = $str;
			    	break;
		    }
	    }
        return view('admin.config.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    //添加配置项
	    return view('admin/config/add');
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
		    'conf_name'=>'required',
		    'conf_title'=>'required',
	    ];
	    $message = [
		    'conf_name.required'=>'配置项名称不能为空',
		    'conf_title.required'=>'配置项标题不能为空',
	    ];
	    $validator = Validator::make($input,$rules,$message);
	    if($validator->passes()){
//		    $input['conf_content'] = isset($input['conf_content'])?$input['conf_content']:'';
		    foreach ($input as $k=>$v){
			    $input[$k] = is_null($v)?'':$v;
		    }
		    $res = Config::create($input);
		    if($res){
			    return redirect('admin/config');
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
	    $field = Config::find($id);
	    return view('admin.config.edit',compact('field'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $conf_id)
    {
	    //获取更新提交数据
	    $input = Input::except("_token","_method");
	    $arr=array_filter ($input);//排除null字段(如果字段为false,''也会被排除)
	    $res = Config::where('conf_id',$conf_id)->update($arr);
	    if($res){
		    $this->putFile();
		    return redirect('admin/config');
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
    public function destroy($conf_id)
    {
	    //删除
	    $res = Config::where('conf_id',$conf_id)->delete();
	    if($res){
		    $this->putFile();
		    $data=[
			    'status' => 0,
			    'msg' => '配置项删除成功!',
		    ];
	    }else{
		    $data=[
			    'status' => 1,
			    'msg' => '配置项删除失败,请稍后重试!',
		    ];
	    }
	    return $data;
    }
	/*
	 * 排序ajax
	 */
	public function changeOrder() {
		$input = Input::all();
		$confs = Config::find($input['conf_id']);
		$confs->conf_order = $input['conf_order'];
		$re = $confs->update();
		if($re){
			$data = [
				'status' => 0,
				'msg' => '配置项排序更新成功!'
			];
		}else{
			$data = [
				'status' => 1,
				'msg' => '配置项排序更新失败,请稍后重试!'
			];
		}
		return $data;
	}
	//修改配置项内容ajax
	public function changeContent(){
		$input = Input::all();
//		dd($input);
		foreach ($input['conf_id'] as $k=>$v){
			Config::where('conf_id',$v)->update(['conf_content'=>$input['conf_content'][$k]]);
		}
		$this->putFile();//配置信息本地存储
		return back()->with('errors','配置项更新成功!');
	}
	//将配置项内容写入配置文件
	public function putFile() {
		//1.从数据库导出数据
		$config = Config::pluck('conf_content','conf_name')->all();

		$path = base_path().'/config/web.php';
		$str = '<?php return '.var_export($config,true).';';

		//2.将数据写入配置文件
		file_put_contents($path,$str);
		
	}
}
