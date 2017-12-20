<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	//自定义表名
	protected $table = 'category';
	//自定义主键
	protected $primaryKey = 'cate_id';
	//禁用更新时间字段
	public $timestamps = false;
	//排除不能填充字段
	protected $guarded = [];

	public function tree() {
		//获取全部数据
		$cates = $this->orderBy('cate_order','asc')->get();
		return $this->getTree($cates,'cate_name','cate_id','cate_pid');
	}

	public function getTree($data,$field_name,$field_id='id',$field_pid='pid',$pid=0) {
//		dd($data);
		$arr = [];
		foreach ($data as $k=>$v){
			if($v->$field_pid == $pid){
				$data[$k]['_'.$field_name] = $data[$k][$field_name];
				$arr[]=$data[$k];
				foreach ($data as $m=>$n){
					if($n->$field_pid == $v->$field_id){
						$data[$m]['_'.$field_name] = '|一'.$data[$m][$field_name];
						$arr[]=$data[$m];
					}
				}
			}
		}
		return $arr;
	}

}
