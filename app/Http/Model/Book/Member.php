<?php

namespace App\Http\Model\Book;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    //绑定数据库
	protected $connection = 'mysql_book';
	//自定义表名
	protected $table = 'member';
	//自定义主键
	protected $primaryKey = 'id';
	//禁用更新时间字段
//	public $timestamps = false;
	//排除不能填充字段
	protected $guarded = [];
}
