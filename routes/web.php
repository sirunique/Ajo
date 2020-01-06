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
Auth::routes();

Route::prefix('/admin')->name('admin.')->group(function(){
	Route::group(['middleware' => 'guest'],function(){
		// Route::get('/add','AdminController@add')->name('add');
		Route::get('/login', 'AdminController@login')->name('login');
		Route::post('/login', 'AdminController@postLogin');
	});

	Route::group(['middleware' => 'auth:admin'], function () {
		Route::get('/', 'AdminController@admin')->name('admin');
		Route::get('/datacheckout', 'AdminController@datacheckout')->name('datacheckout');
		Route::get('/logout', 'AdminController@logout')->name('logout');
	});

});

Route::get('/', function(){
	return view('welcome');
})->name('/');
Route::get('/notverify', 'UserController@notverify')->name('notverify');
Route::get('/email/verifydata', 'UserController@verifydata')->name('verifydata');
Route::post('/email/verifydata', 'UserController@postverifydata');

Route::group(['middleware' => 'guest'],function(){
	Route::get('/login', 'UserController@login')->name('login');
	Route::post('/login', "UserController@postlogin");
	Route::get('/register', 'UserController@register')->name('register');
	Route::post('/register', "UserController@postregister");
	//Email Verification Routes
	Route::get('/email/verify/{id}', 'UserController@verify')->name('verify');
});


Route::group(['middleware' => ['auth:investor', 'checkIfVerify']], function () { 
	Route::get('/dashboard', 'UserController@dashboard')->name('dashboard');
	Route::get('/logout', 'UserController@logout')->name('logout');
	Route::get('/group', 'UserController@group')->name('group');
	Route::get('/createThrift', 'UserController@createThrift')->name('createThrift');
	Route::post('/createThrift', "UserController@postCreateThrift");
	Route::get('/groupInfo/{id}', "UserController@groupInfo")->name('groupInfo');
	Route::post('/addMember', 'UserController@addMember')->name('addMember');
	Route::get('/startThrift/{id}', 'UserController@startThrift')->name('startThrift');
	Route::post('/weekPayment', 'UserController@weekPayment')->name('weekPayment');
	Route::post('/payout', 'UserController@payout')->name('payout');
});

