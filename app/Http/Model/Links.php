<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
	//自定义表名
	protected $table = 'links';
	//自定义主键
	protected $primaryKey = 'link_id';
	//禁用更新时间字段
	public $timestamps = false;
	//排除不能填充字段
	protected $guarded = [];
}
