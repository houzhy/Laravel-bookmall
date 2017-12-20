<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
	//自定义表名
	protected $table = 'config';
	//自定义主键
	protected $primaryKey = 'conf_id';
	//禁用更新时间字段
	public $timestamps = false;
	//排除不能填充字段
	protected $guarded = [];
}
