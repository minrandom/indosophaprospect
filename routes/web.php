<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
//Namespace Auth
use App\Http\Controllers\Auth\LoginController;
//use App\Providers\GoogleDriveAdapter;
//use App\Providers\GoogleDriverServiceProvider;
//Namespace Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\prospectRemarksController;
use App\Http\Controllers\ProspectFiltersController;

use App\Http\Controllers\HospitalController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\sequenceController;
use App\Http\Controllers\JojoController;
use App\Http\Controllers\TampanController;


use App\Http\Controllers\AttendanceEvenInController;
use App\Http\Controllers\AttendanceEvenOutController;


 
//Namespace User
use App\Http\Controllers\User\UserController;


use App\Http\Controllers\User\ProfileController;

use App\Http\Controllers\Admin\PrincipalBrandController;
use App\Http\Controllers\Admin\DataCompileController;
use App\Http\Controllers\ConsumablesProspectController;
use App\Http\Controllers\DeptValidController;
use App\Models\prospectFilters;

//use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;


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


Route::GET('/testput', function() {
	//       $photoPaths=public_path($photoPath);
	Storage::disk('google')->put('test.txt', 'Hello World');
	//Storage::disk('google')->put('download.png', $photoPaths);
    //return 'File was saved to Google Drive';
});


//Route::resource('/gdrive',TampanController::class);


Route::get('/', function () {
    // Check if the user is already authenticated
    if (Auth::check()) {
        // Redirect authenticated users away from the login page
        return redirect('/home'); // Change '/dashboard' to the desired route
    }

    // Render the login view for non-authenticated users
    return view('auth.login');
})->name('start')->middleware('prevent-back');


Route::middleware('prevent-back')->group(function () {
    // Other routes...
});

//attendance normal
Route::post('/check-in', 'JojoController@store')->name('check-in.store')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
Route::post('/check-out', 'JojoController@outstore')->name('check-out.store')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
Route::get('/check-in', 'JojoController@index')->name('check-in')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
Route::get('/check-out', 'JojoController@indx')->name('check-out')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);


//attendance event
Route::post('/EventIn', 'AttendanceEventIn@store')->name('EventIn.store')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
Route::post('/EventOut', 'AttendanceEventOut@outstore')->name('EventOut.store')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
Route::get('/EventIn', 'AttendanceEventIn@index')->name('EventIn')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
Route::get('/EventOut', 'AttendanceEventOut@indx')->name('EventOut')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);



Route::get('/user2',[UserController::class,'index2'])->name('user2');
Route::post('/remarks', [prospectRemarksController::class,'store'])->name('remarks.store')->middleware(['auth', 'role:admin,bu,fs']);
Route::get('/remarksdata', [prospectRemarksController::class,'index'])->name('remarks.data')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
Route::get('/remarksdata', [prospectRemarksController::class,'index'])->name('remarks.data')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
Route::post('/sequencedata', [sequenceController::class,'upload'])->name('sequence.up')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
Route::post('/prospectFilterSave', [ProspectFiltersController::class,'upload'])->name('prospectfiltersave')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);

Route::get('/temperupdate/{id}', [JojoController::class, 'temperupdate'])->name('temperupdate')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
Route::get('/fixmiss', [TampanController::class, 'fixmissingitem'])->name('fixmiss')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);

Route::get('/fixdata', 'JojoController@fixtemperature')->name('fix.temperature')->middleware(['auth', 'role:admin']);

Route::get('/kehadiran', 'JojoController@kehadiran')->name('kehadiran')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
Route::get('/kehadiranevent', 'JojoController@kehadiranevent')->name('kehadiranevent')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);

Route::group(['namespace' => 'Admin','middleware' => 'auth','prefix' => 'admin'],function(){
	Route::get('/',[AdminController::class,'index'])->name('admin')->middleware(['can:admin']);
	Route::get('/deptvalidation',[DeptValidController::class,'create'])->name('admin.deptvalidation')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
	Route::get('/deptvalidationc',[DeptValidController::class,'creation'])->name('admin.deptvalidationc')->middleware(['auth', 'role:admin,am,nsm,bu,prj,fs']);
	Route::get('/configcreatedata',[ConfigController::class,'createconfdata'])->name('admin.createconfdata')->middleware(['auth', 'role:admin,dba']);

	Route::get('/hospital',[HospitalController::class,'index'])->name('admin.hospital')->middleware(['auth', 'role:admin,dba']);
	Route::get('/hospitalcreate',[HospitalController::class,'create'])->name('admin.hospitalcreate')->middleware(['auth', 'role:admin,dba']);
	Route::get('/hospitalcreatedata',[HospitalController::class,'createhosdata'])->name('admin.createhosdata')->middleware(['auth', 'role:admin,dba']);
	Route::POST('/hospitaladd',[HospitalController::class,'store'])->name('admin.hospitalstore')->middleware(['auth', 'role:admin,dba']);
	Route::POST('/configadd',[ConfigController::class,'store'])->name('admin.configstore')->middleware(['auth', 'role:admin,dba']);
	Route::get('/config',[ConfigController::class,'index'])->name('admin.config')->middleware(['auth', 'role:admin,dba']);
	Route::PATCH('/config/{config}',[ConfigController::class,'update'])->name('admin.configupdate')->middleware(['auth', 'role:admin,dba']);
	Route::get('/config/{config}/edit',[ConfigController::class,'show'])->name('admin.configdetail')->middleware(['auth', 'role:admin,dba']);
	Route::get('/configcreate',[ConfigController::class,'create'])->name('admin.configcreate')->middleware(['auth', 'role:admin,dba']);
	Route::PATCH('/prospectupdate/{prospect}',[ProspectController::class,'update'])->name('admin.prospectupdate')->middleware(['auth', 'role:admin,am,nsm,prj']);
	Route::PATCH('/prospectinfoupdate/{prospect}',[ProspectController::class,'infoupdate'])->name('admin.prospectinfoupdate')->middleware(['can:admin']);
	Route::PATCH('/prospectinfoupdaterequest/{prospect}',[ProspectController::class,'infoupdaterequest'])->name('admin.prospect.infoupdaterequest')->middleware(['auth', 'role:admin,am,nsm,bu,prj']);
	Route::PATCH('/productupdaterequest/{prospect}',[ProspectController::class,'produpdaterequest'])->name('admin.prospect.produpdaterequest')->middleware(['auth', 'role:admin,am,nsm,bu,prj']);
	Route::PATCH('/promoteupdate/{prospect}',[ProspectController::class,'promodateupdate'])->name('admin.prospect.promoteupdate')->middleware(['auth', 'role:admin,am,nsm,bu,prj']);
	Route::PATCH('/reviewupdate/{prospect}',[ProspectController::class,'reviewupdate'])->name('admin.prospect.reviewupdate')->middleware(['auth', 'role:admin,am,nsm,bu,prj']);
	Route::PATCH('/chcupdate/{prospect}',[ProspectController::class,'chcupdate'])->name('admin.prospect.chcupdate')->middleware(['auth', 'role:admin,am,nsm,bu,prj']);
	Route::get('/prospect/{prospect}/edit',[ProspectController::class,'edit'])->name('admin.prospectedit')->middleware(['auth', 'role:admin,am,nsm,bu,prj']);
	
	//Route::get('/province',[ProvinceController::class,'index'])->name('province')->middleware(['can:admin']);
	//Route Rescource
	//Route::resource('/prospect',ProspectController::class)->middleware(['can:admin']);
	Route::get('/prospecteditz/{prospect}',[ProspectController::class,'show'])->name('admin.prospecteditdata')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/cnprospectdetail/{consumablesProspect}',[ConsumablesProspectController::class,'show'])->name('admin.cnprospecteditdata')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/cnprospectshow/{consumablesProspect}',[ConsumablesProspectController::class,'detaildata'])->name('admin.cnprospectdetail')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/prospectvalidation',[ProspectController::class,'validationprospect'])->name('admin.prospectvalidationview')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/prospectcheck',[ProspectController::class,'creationcheck'])->name('admin.prospectcheckview')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::PATCH('/prospectvalidation/{prospect}',[ProspectController::class,'validationupdate'])->name('admin.prospectvalidationupdate')->middleware(['auth', 'role:admin,am,nsm,prj']);
	Route::get('/prospectvalidation/{prospect}/validation',[ProspectController::class,'validation'])->name('admin.prospectvalidation')->middleware(['auth', 'role:admin,am,nsm,prj']);
	
	Route::get('/consprospectvalidation',[ConsumablesProspectController::class,'validationprospect'])->name('admin.consprospectvalidationview')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/consprospect/{consumablesProspect}/edit',[ConsumablesProspectController::class,'edit'])->name('admin.consprospectedit')->middleware(['auth', 'role:admin,am,nsm,bu,prj']);
	//Route::get('/prospectcheck',[ProspectController::class,'creationcheck'])->name('admin.prospectcheckview')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::PATCH('/consprospectvalidation/{consumablesProspect}',[ConsumablesProspectController::class,'validationupdate'])->name('admin.consprospectvalidationupdate')->middleware(['auth', 'role:admin,am,nsm']);
	Route::PATCH('/consprospectupdate/{consumablesProspect}',[ConsumablesProspectController::class,'update'])->name('admin.consprospectupdate')->middleware(['auth', 'role:admin,am,nsm']);
	Route::PATCH('/consprospectupdaters/{consumablesProspect}',[ConsumablesProspectController::class,'rsupdate'])->name('admin.consprospectupdaters')->middleware(['auth', 'role:admin,am,nsm']);
	Route::PATCH('/consprospectupdatests/{consumablesProspect}',[ConsumablesProspectController::class,'updatests'])->name('admin.consprospectupdatests')->middleware(['auth', 'role:admin,am,nsm']);
	Route::get('/consprospectvalidation/{consumablesProspect}/validation',[ConsumablesProspectController::class,'validation'])->name('admin.consprospectvalidation')->middleware(['auth', 'role:admin,am,nsm']);
	Route::get('/cpdetailtvalidation/{consumablesProspect}',[ConsumablesProspectController::class,'validationproddetail'])->name('admin.cpvalidationdetail')->middleware(['auth', 'role:admin,am,nsm']);
	
	Route::get('/prospect',[ProspectController::class,'index'])->name('admin.prospect.index')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	
	Route::POST('/consprospect',[ConsumablesProspectController::class,'store'])->name('admin.consprospect.store')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj,prj']);
	Route::POST('/prospect',[ProspectController::class,'store'])->name('admin.prospect.store')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/prospectcreate',[ProspectController::class,'create'])->name('admin.prospectcreate')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/consprospectcreate',[ConsumablesProspectController::class,'create'])->name('admin.consprospectcreate')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/prospecteventcreate',[ProspectController::class,'eventcreate'])->name('admin.prospecteventcreate')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::POST('/draft',[ProspectController::class,'savedraft'])->name('admin.prospect.saveDraft')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/consprospectcreation',[ConsumablesProspectController::class,'creation'])->name('admin.consprospectcreation')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/prospectcreation',[ProspectController::class,'creation'])->name('admin.prospectcreation')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/prospecteventcreation',[ProspectController::class,'eventcreation'])->name('admin.prospecteventcreation')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/hospital/{provinceId}/hospital',[HospitalController::class,'getHospitalsByProvince'])->name('admin.getHospitalsByProvince')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/hospital/{hospital}/edit',[HospitalController::class,'show'])->name('admin.hospitaldetail')->middleware(['auth', 'role:admin,dba']);
	Route::PATCH('/hospital/{hospital}',[HospitalController::class,'update'])->name('admin.hospitalupdate')->middleware(['auth', 'role:admin,dba']);
	Route::get('/hospital2/{provinceId}/hospital2',[HospitalController::class,'getHospitalsByProvince2'])->name('admin.getHospitalsByProvince2')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/dept/{hospitalId}/dept',[DeptValidController::class,'getDeptValid'])->name('admin.getDeptValid')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::POST('/deptvalid',[DeptValidController::class,'store'])->name('admin.deptvalid')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj,dba']);
	Route::get('/category/{unitId}/category',[CategoryController::class,'getCategoriesByUnit'])->name('admin.getCategoriesByUnit')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj,dba']);
	Route::get('/categorydata/{unitId}/category',[CategoryController::class,'getCatname'])->name('admin.getCatname')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);
	Route::get('/config/get-products', [ConfigController::class, 'getProducts'])->name('product.getProducts');
	Route::resource('/user','UserController')->middleware(['can:admin']);
	Route::resource('/province','ProvinceController')->middleware(['can:admin']);
	Route::resource('/principle','PrincipalController')->middleware(['can:admin']);
	Route::get('data1','DataCompileController@getData')->name('data.compile');
	Route::get('data2','DataCompileController@HospitalData')->name('data.hospital');
	Route::get('datacon','DataCompileController@ConfigData')->name('data.config');
	Route::get('createddata','DataCompileController@CreatedData')->name('data.createdcheck');
	Route::post('data3','DataCompileController@ProspectData')->name('data.prospect');
	Route::post('dataconsum','DataCompileController@ConsumablesProspectData')->name('data.consprospect');
	Route::post('/get-product-details','DataCompileController@getProductDetail')->name('data.proddetail');
	Route::get('data/{user}/editrole','UserController@editrole')->name('user.editrole');
	Route::get('/dataa',[PrincipalBrandController::class,'index'])->name('dataa')->middleware(['can:admin']);
	Route::view('/jadwal','admin.scheduling')->name('schedule');


	//consumables prospect
	Route::get('/consprospect',[ConsumablesProspectController::class,'index'])->name('admin.consprospects')->middleware(['auth', 'role:admin,fs,am,nsm,bu,prj']);



	
	//Route stay for example check
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
	Route::view('/pchart','admin.prospectchart')->name('pchart');
	Route::view('/tables','admin.tables')->name('tables');

	
});
Route::get('/scheduledata','ScheduleController@index')->name('schedule.index');
Route::PATCH('/scheduleupdate/{schedule}','ScheduleController@update')->name('events.update');
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
	
});

// Other
Route::view('/register','auth.register')->name('register');
Route::view('/forgot-password','auth.forgot-password')->name('forgot-password');

//Route::get('/location', 'LocationController@index')->name('location.index');
Route::post('/login',[LoginController::class,'authenticate'])->name('login.post');

Route::post('/logout',function(){
	return redirect()->to('/')->with(Auth::logout())->with('refresh', true);
})->name('logout');

Route::get('/home', function () {
    return redirect()->route('user')->with('refresh', true);
})->name('homestart');



//Route::get('/geoloc', 'GeolocationController@index');
//Route::post('/location', 'GeolocationController@getLocation')->name('location');
