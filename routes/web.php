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
Route::get('/welcome', 'HomeController@index');


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
 

Route::group(['middleware' => 'auth'], function()
{
	Route::resource('master_users', 'MasterUserController'); 
	Route::resource('master-satuan-barang', 'SatuanBarangController');  
	Route::resource('master-kategori-barang', 'KategoriBarangController'); 
	Route::resource('master-barang', 'BarangController');  
	Route::resource('penjualan', 'PenjualanController');
 
  	Route::post('master-barang/export-barang', [
  	'middleware' => ['auth'],
    'as'   => 'master-barang.export_barang',
    'uses' => 'BarangController@export_barang'
  	]);

  	Route::post('penjualan/export-penjualan', [
  	'middleware' => ['auth'],
    'as'   => 'penjualan.export_penjualan',
    'uses' => 'PenjualanController@export_penjualan'
  	]);

  	Route::post('penjualan/filter-penjualan', [
  	'middleware' => ['auth'],
    'as'   => 'penjualan.filter_penjualan',
    'uses' => 'PenjualanController@filter_penjualan'
  	]);

	Route::get('master-barang/filter-satuan-barang/{id}',[
	'middleware' => ['auth'],
	'as' => 'master-barang.filter_satuan_barang',
	'uses' => 'BarangController@filter_satuan_barang'
	]);

	Route::get('master-barang/filter-kategori-barang/{id}',[
	'middleware' => ['auth'],
	'as' => 'master-barang.filter_kategori_barang',
	'uses' => 'BarangController@filter_kategori_barang'
	]);

	Route::get('master-barang/filter-kelontongan-barang/{id}',[
	'middleware' => ['auth'],
	'as' => 'master-barang.filter_kelontongan_barang',
	'uses' => 'BarangController@filter_kelontongan_barang'
	]);

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

//proses penjualan

	Route::post('/penjualan/proses-hapus-tbs-penjualan/{id}',[
	'middleware' => ['auth'],
	'as' => 'penjualan.proses_hapus_tbs_penjualan',
	'uses' => 'PenjualanController@proses_hapus_tbs_penjualan'
	]);


	Route::post('/penjualan/proses-hapus-semua-tbs-penjualan/',[
	'middleware' => ['auth'],
	'as' => 'penjualan.proses_hapus_semua_tbs_penjualan',
	'uses' => 'PenjualanController@proses_hapus_semua_tbs_penjualan'
	]);

	Route::post('/penjualan/proses-tambah-tbs-penjualan',[
	'middleware' => ['auth'],
	'as' => 'penjualan.proses_tambah_tbs_penjualan',
	'uses' => 'PenjualanController@proses_tambah_tbs_penjualan'
	]);

	Route::get('/penjualan/proses-form-edit/{id}',[
	'middleware' => ['auth'],
	'as' => 'penjualan.proses_form_edit',
	'uses' => 'PenjualanController@proses_form_edit'
	]);

	Route::post('/penjualan/proses-tambah-edit-tbs-penjualan/{id}',[
	'middleware' => ['auth'],
	'as' => 'penjualan.proses_tambah_edit_tbs_penjualan',
	'uses' => 'PenjualanController@proses_tambah_edit_tbs_penjualan'
	]); 

	Route::post('/penjualan/proses-hapus-edit-tbs-penjualan/{id}',[
	'middleware' => ['auth'],
	'as' => 'penjualan.proses_hapus_edit_tbs_penjualan',
	'uses' => 'PenjualanController@proses_hapus_edit_tbs_penjualan'
	]);

	Route::post('/penjualan/proses-edit-penjualan/{id}',[
	'middleware' => ['auth'],
	'as' => 'penjualan.proses_edit_penjualan',
	'uses' => 'PenjualanController@proses_edit_penjualan'
	]);

	Route::post('/penjualan/proses-hapus-semua-edit-tbs-penjualan/{id}',[
	'middleware' => ['auth'],
	'as' => 'penjualan.proses_hapus_semua_edit_tbs_penjualan',
	'uses' => 'PenjualanController@proses_hapus_semua_edit_tbs_penjualan'
	]);
});
