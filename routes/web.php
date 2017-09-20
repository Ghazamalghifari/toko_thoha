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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');


	Route::get('/ubah-password',[
	'middleware' => ['auth'],
	'as' => 'users.ubah_password',
	'uses' => 'UbahPasswordController@ubah_password'
	]);

	Route::put('/proses-ubah-password/{id}',[
	'middleware' => ['auth'],
	'as' => 'users.proses_ubah_password',
	'uses' => 'UbahPasswordController@proses_ubah_password'
	]);
 

Route::group(['prefix'=>'admin', 'middleware'=>['auth', 'role:admin|pimpinan|pj_dosen']], function () {
   
	Route::resource('master_users', 'MasterUserController'); 
	Route::resource('master-satuan-barang', 'SatuanBarangController');  
	Route::resource('master-kategori-barang', 'KategoriBarangController'); 
	Route::resource('master-barang', 'BarangController');  
 
	Route::get('master_users/filterkonfirmasi/{id}',[
	'middleware' => ['auth'],
	'as' => 'master_users.filter_konfirmasi',
	'uses' => 'MasterUserController@filter_konfirmasi'
	]);

	Route::get('master_users/filterangkatan/{id}',[
	'middleware' => ['auth'],
	'as' => 'master_users.filter_angkatan',
	'uses' => 'MasterUserController@filter_angkatan'
	]);

	Route::get('master_users/filterotoritas/{id}',[
	'middleware' => ['auth'],
	'as' => 'master_users.filter_otoritas',
	'uses' => 'MasterUserController@filter_otoritas'
	]);
  
	Route::get('master_users/no_konfirmasi/{id}',[
	'middleware' => ['auth'],
	'as' => 'master_users.no_konfirmasi',
	'uses' => 'MasterUserController@no_konfirmasi'
	]);	 
 
	Route::get('master_users/konfirmasi/{id}',[
	'middleware' => ['auth','role:admin'],
	'as' => 'master_users.konfirmasi',
	'uses' => 'MasterUserController@konfirmasi'
	]);

	Route::get('master_users/reset/{id}',[
	'middleware' => ['auth','role:admin'],
	'as' => 'master_users.reset',
	'uses' => 'MasterUserController@reset'
	]);



});
