<?php

use Illuminate\Support\Facades\Route;

//Namespace Auth
use App\Http\Controllers\Auth\LoginController;

//Namespace Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ScheduleController;

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
Route::get('/home', function () {
    return redirect()->route('user2');
})->name('start');
Route::view('/','auth.login')->name('login');
Route::get('/user2',[UserController::class,'index2'])->name('user2');
Route::post('/check-in', 'JojoController@store')->name('check-in.store');
Route::get('/check-in', 'JojoController@index')->name('check-in');
Route::group(['namespace' => 'Admin','middleware' => 'auth','prefix' => 'admin'],function(){
	
	Route::get('/',[AdminController::class,'index'])->name('admin')->middleware(['can:admin']);
	Route::get('/hospital',[HospitalController::class,'index'])->name('admin.hospital')->middleware(['can:admin']);
	Route::get('/config',[ConfigController::class,'index'])->name('admin.config')->middleware(['can:admin']);
	Route::PATCH('/prospectupdate/{prospect}',[ProspectController::class,'update'])->name('admin.prospectupdate')->middleware(['can:admin']);
	Route::PATCH('/prospectinfoupdate/{prospect}',[ProspectController::class,'infoupdate'])->name('admin.prospectinfoupdate')->middleware(['can:admin']);
	Route::PATCH('/prospectinfoupdaterequest/{prospect}',[ProspectController::class,'infoupdaterequest'])->name('admin.prospect.infoupdaterequest')->middleware(['auth', 'role:admin,am,nsm']);
	Route::PATCH('/productupdaterequest/{prospect}',[ProspectController::class,'produpdaterequest'])->name('admin.prospect.produpdaterequest')->middleware(['auth', 'role:admin,am,nsm']);
	Route::PATCH('/promoteupdate/{prospect}',[ProspectController::class,'promodateupdate'])->name('admin.prospect.promoteupdate')->middleware(['auth', 'role:admin,am,nsm']);
	Route::PATCH('/reviewupdate/{prospect}',[ProspectController::class,'reviewupdate'])->name('admin.prospect.reviewupdate')->middleware(['auth', 'role:admin,am,nsm']);
	Route::get('/prospect/{prospect}/edit',[ProspectController::class,'edit'])->name('admin.prospectedit')->middleware(['auth', 'role:admin,am,nsm']);
	
	//Route::get('/province',[ProvinceController::class,'index'])->name('province')->middleware(['can:admin']);
	//Route Rescource
	//Route::resource('/prospect',ProspectController::class)->middleware(['can:admin']);
	Route::get('/prospecteditz/{prospect}',[ProspectController::class,'show'])->name('admin.prospecteditdata')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::get('/prospectvalidation',[ProspectController::class,'validationprospect'])->name('admin.prospectvalidationview')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::get('/prospectcheck',[ProspectController::class,'creationcheck'])->name('admin.prospectcheckview')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::PATCH('/prospectvalidation/{prospect}',[ProspectController::class,'validationupdate'])->name('admin.prospectvalidationupdate')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::get('/prospectvalidation/{prospect}/validation',[ProspectController::class,'validation'])->name('admin.prospectvalidation')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::get('/prospect',[ProspectController::class,'index'])->name('admin.prospect.index')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::POST('/prospect',[ProspectController::class,'store'])->name('admin.prospect.store')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::get('/prospectcreate',[ProspectController::class,'create'])->name('admin.prospectcreate')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::POST('/draft',[ProspectController::class,'savedraft'])->name('admin.prospect.saveDraft')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::get('/prospectcreation',[ProspectController::class,'creation'])->name('admin.prospectcreation')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::get('/hospital/{provinceId}/hospital',[HospitalController::class,'getHospitalsByProvince'])->name('admin.getHospitalsByProvince')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::get('/category/{unitId}/category',[CategoryController::class,'getCategoriesByUnit'])->name('admin.getCategoriesByUnit')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::get('/categorydata/{unitId}/category',[CategoryController::class,'getCatname'])->name('admin.getCatname')->middleware(['auth', 'role:admin,fs,am,nsm']);
	Route::get('/config/get-products', [ConfigController::class, 'getProducts'])->name('product.getProducts');
	Route::resource('/user','UserController')->middleware(['can:admin']);
	Route::resource('/province','ProvinceController')->middleware(['can:admin']);
	Route::resource('/principle','PrincipalController')->middleware(['can:admin']);
	Route::get('data1','DataCompileController@getData')->name('data.compile');
	Route::get('data2','DataCompileController@HospitalData')->name('data.hospital');
	Route::get('datacon','DataCompileController@ConfigData')->name('data.config');
	Route::get('createddata','DataCompileController@CreatedData')->name('data.createdcheck');
	Route::post('data3','DataCompileController@ProspectData')->name('data.prospect');
	Route::post('/get-product-details','DataCompileController@getProductDetail')->name('data.proddetail');
	Route::get('data/{user}/editrole','UserController@editrole')->name('user.editrole');
	Route::get('/dataa',[PrincipalBrandController::class,'index'])->name('dataa')->middleware(['can:admin']);
	
	//Route View
	Route::view('/jadwal','admin.scheduling')->name('schedule');
	Route::get('/load-tab-content', 'TabController@loadTabContent')->name('load-tab-content');
	Route::view('/404-page','admin.404-page')->name('404-page');
	Route::view('/blank-page','admin.blank-page')->name('blank-page');
	Route::view('/buttons','admin.buttons')->name('buttons');
	//Route::view('/prospectDetail/{prospect}/edit','admin.prospectedit')->name('prp.detail');
	Route::view('/cards','admin.cards')->name('cards');
	Route::view('/utilities-colors','admin.utilities-color')->name('utilities-colors');
	Route::view('/utilities-borders','admin.utilities-border')->name('utilities-borders');
	Route::view('/utilities-animations','admin.utilities-animation')->name('utilities-animations');
	Route::view('/utilities-other','admin.utilities-other')->name('utilities-other');
	Route::view('/chart','admin.chart')->name('chart');
	Route::view('/tables','admin.tables')->name('tables');
	
	
});
Route::get('/scheduledata','ScheduleController@index')->name('schedule.index');
Route::PATCH('/scheduleupdate','ScheduleController@update')->name('events.update');
Route::POST('/schedulestore','ScheduleController@store')->name('events.store');

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

Route::group(['namespace' => 'Auth','middleware' => 'guest','prefix' => 'Auth'],function(){
	Route::view('/login','auth.login')->name('login');
	Route::post('/login',[LoginController::class,'authenticate'])->name('login.post');
});

// Other
Route::view('/register','auth.register')->name('register');
Route::view('/forgot-password','auth.forgot-password')->name('forgot-password');
Route::post('/logout',function(){
	return redirect()->to('/')->with(Auth::logout());
})->name('logout');

//Route::get('/location', 'LocationController@index')->name('location.index');

//Route::get('/geoloc', 'GeolocationController@index');
//Route::post('/location', 'GeolocationController@getLocation')->name('location');
