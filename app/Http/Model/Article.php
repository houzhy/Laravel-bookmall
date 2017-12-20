<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
	//自定义表名
	protected $table = 'article';
	//自定义主键
	protected $primaryKey = 'art_id';
	//禁用更新时间字段
	public $timestamps = false;
	//排除不能填充字段
	protected $guarded = [];
}
