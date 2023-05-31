<?php

use Illuminate\Support\Facades\Route;

//Namespace Auth
use App\Http\Controllers\Auth\LoginController;

//Namespace Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\ConfigController;

//Namespace User
use App\Http\Controllers\User\UserController;


use App\Http\Controllers\User\ProfileController;

use App\Http\Controllers\Admin\PrincipalBrandController;
use App\Http\Controllers\Admin\DataCompileController;
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

Route::view('/','auth.login')->name('login');


Route::group(['namespace' => 'Admin','middleware' => 'auth','prefix' => 'admin'],function(){
	
	Route::get('/',[AdminController::class,'index'])->name('admin')->middleware(['can:admin']);
	Route::get('/hospital',[HospitalController::class,'index'])->name('admin.hospital')->middleware(['can:admin']);
	Route::get('/config',[ConfigController::class,'index'])->name('admin.config')->middleware(['can:admin']);
	//Route::get('/province',[ProvinceController::class,'index'])->name('province')->middleware(['can:admin']);
	//Route Rescource
	Route::resource('/user','UserController')->middleware(['can:admin']);
	Route::resource('/province','ProvinceController')->middleware(['can:admin']);
		Route::resource('/principle','PrincipalController')->middleware(['can:admin']);
	Route::get('data1','DataCompileController@getData')->name('data.compile');
	Route::get('data2','DataCompileController@HospitalData')->name('data.hospital');
	Route::get('data2','DataCompileController@ConfigData')->name('data.config');
	Route::get('data/{user}/editrole','UserController@editrole')->name('user.editrole');
	Route::get('/dataa',[PrincipalBrandController::class,'index'])->name('dataa')->middleware(['can:admin']);
	//Route View
	
	Route::view('/404-page','admin.404-page')->name('404-page');
	Route::view('/blank-page','admin.blank-page')->name('blank-page');
	Route::view('/buttons','admin.buttons')->name('buttons');
	Route::view('/cards','admin.cards')->name('cards');
	Route::view('/utilities-colors','admin.utilities-color')->name('utilities-colors');
	Route::view('/utilities-borders','admin.utilities-border')->name('utilities-borders');
	Route::view('/utilities-animations','admin.utilities-animation')->name('utilities-animations');
	Route::view('/utilities-other','admin.utilities-other')->name('utilities-other');
	Route::view('/chart','admin.chart')->name('chart');
	Route::view('/tables','admin.tables')->name('tables');
	

});

Route::group(['namespace' => 'User','middleware' => 'auth' ,'prefix' => 'fs'],function(){
	Route::get('/',[UserController::class,'index'])->name('user');
	//Route::get('/profile',[ProfileController::class,'index'])->name('profile');
	//Route::patch('/profile/update/{user}',[ProfileController::class,'update'])->name('profile.update');
});

Route::group(['namespace' => 'User','middleware' => 'auth' ,'prefix' => 'user'],function(){
	Route::get('/',[UserController::class,'index2'])->name('user');
	Route::get('/profile',[ProfileController::class,'index'])->name('profile');
	Route::patch('/profile/update/{user}',[ProfileController::class,'update'])->name('profile.update');
});

Route::group(['namespace' => 'Auth','middleware' => 'guest'],function(){
	Route::view('/login','auth.login')->name('login');
	Route::post('/login',[LoginController::class,'authenticate'])->name('login.post');
});

// Other
Route::view('/register','auth.register')->name('register');
Route::view('/forgot-password','auth.forgot-password')->name('forgot-password');
Route::post('/logout',function(){
	return redirect()->to('/login')->with(Auth::logout());
})->name('logout');

//Route::get('/location', 'LocationController@index')->name('location.index');

//Route::get('/geoloc', 'GeolocationController@index');
//Route::post('/location', 'GeolocationController@getLocation')->name('location');
