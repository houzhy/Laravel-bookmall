<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//闭包方法
//Route::get('/', function () {
//    return view('welcome');
//});
////首页路由
//Route::get('/', 'IndexController@index');
//
///*
// * 开发路由
// */
//Route::get('/hoyu', function () {
//	echo 'get';
//	return view('welcome');
//});
//Route::get('test', 'Admin\IndexController@test');
//上面设置全部为测试

//子域名路由手机app
Route::group(['domain' => 'book.my_laravel.com'], function () {
	Route::get('/', function () {
		return \App\Http\Model\Book\Member::all();
	});
	//登录
	Route::get('/login','Bookvi\MemberController@toLogin');
	//注册
	Route::get('/register','Bookvi\MemberController@toRegister');
	//分类
	Route::get('/category','Bookvi\BookController@toCategory');
	//产品列表
	Route::get('/product/category_id/{category_id}','Bookvi\BookController@toProduct');
	//产品详情
	Route::get('/product/{product_id}','Bookvi\BookController@toPdtContent');


});
Route::group(['prefix'=>'service','namespace'=>'Service','domain' => 'book.my_laravel.com'],function(){
	//注册接口
	Route::post('register','MemberController@register');
	//验证码
	Route::get('validate_code/create','ValidateCodeController@create');
	//手机验证码
	Route::post('validate_phone/send','ValidateCodeController@sendSMS');
	//邮箱注册
	Route::post('validate_email','ValidateCodeController@validateEmail');
	//登录
	Route::post('login','MemberController@login');
	//获取子分类
	Route::get('category/parent_id/{parent_id}','BookController@getCategroyByParentId');
	//添加购物车
	Route::get('cart/add/{product_id}','CartController@addCart');
});

//浏览器pc端
Route::group(['middleware'=>['web']],function(){
	//前台首页
	Route::get('/','Home\IndexController@index');
	//列表路由
	Route::get('/cate/{cate_id}','Home\IndexController@cate');
	//文章
	Route::get('/a/{art_id}','Home\IndexController@article');
	//后台登录
		Route::any('admin/login','Admin\LoginController@login');
	//引入验证码路由
		Route::get('admin/code','Admin\LoginController@code');

});

Route::group(['middleware'=>['web','admin.login'],'prefix'=>'admin','namespace'=>'Admin'],function(){
	//后台首页
		Route::get('index','IndexController@index');
	//子页面-系统信息
		Route::get('info','IndexController@info');
	//退出
		Route::get('quit','LoginController@quit');
	//修改密码操作
		Route::any('pass','IndexController@pass');

	//文章分类
		Route::resource('category', 'CategoryController');
	//ajax分类排序
		Route::post('cate/changeorder','CategoryController@changeOrder');
	//文章
		Route::resource('article', 'ArticleController');
	//图片上传
		Route::any('upload','CommonController@upload');
	//文章
		Route::resource('links', 'LinksController');
	//ajax友情链接排序
		Route::post('links/changeorder','LinksController@changeOrder');
	//首页自定义导航
		Route::resource('navs', 'NavsController');
	//ajax自定义导航排序
		Route::post('navs/changeorder','NavsController@changeOrder');
	//首页自定义导航
		Route::resource('config', 'ConfigController');
	//ajax配置项排序
		Route::post('config/changeorder','ConfigController@changeOrder');
	//ajax配置项内容
		Route::post('config/changecontent','ConfigController@changeContent');
	//子页面-系统信息
		Route::get('config/putfile','ConfigController@putFile');

});
