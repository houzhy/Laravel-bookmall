<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $data = [
		    [
			    'link_name' => 'laravel官网',
			    'link_title' => '优雅代码书写',
			    'link_url' => 'http://www.goLaravel.com',
			    'link_order' => 1,
		    ],[
			    'link_name' => 'laravel手册查看',
			    'link_title' => '优雅代码书写',
			    'link_url' => 'http://www.yyuc.net/laravel/input.html',
			    'link_order' => 2,
		    ],
	    ];
	    DB::table('links')->insert($data);
    }
}
