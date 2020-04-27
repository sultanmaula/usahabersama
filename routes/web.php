<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::namespace('Auth')->group(function(){

    //Login Routes
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout')->name('logout');

    //Forgot Password Routes
    Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');

    //Reset Password Routes
    Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.update');
});
Route::get('/home', 'DashboardController@index')->name('home');
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

//Administrator Route
Route::get('/administrator', 'AdministratorController@index')->name('list-admin');
Route::get('/indexadministrator', 'AdministratorController@getIndex')->name('list-admin-index');
Route::get('/roleadministrator/{query}', 'AdministratorController@roleIndex')->name('role-admin-index');
Route::get('/administrator/add', 'AdministratorController@create')->name('add-admin');
Route::get('/administrator/edit/{id}', 'AdministratorController@edit')->name('edit-admin');
Route::post('/administrator/store', 'AdministratorController@store')->name('store-admin');
Route::post('/administrator/update/{id}', 'AdministratorController@update')->name('update-admin');
Route::post('/administrator/delete/{id}', 'AdministratorController@destroy')->name('delete-admin');
Route::get('/administrator/show/{id}', 'AdministratorController@show')->name('show-admin');
//End Administrator Route

//Master Admin Role Route
Route::get('/administrator/role', 'RoleController@index')->name('role-admin');
Route::get('/administrator/role/add', 'RoleController@create')->name('add-role');
Route::post('/administrator/role/store', 'RoleController@store')->name('store-role');
Route::get('/administrator/role/edit/{id}', 'RoleController@edit')->name('edit-role');
Route::post('/administrator/role/update/{id}', 'RoleController@update')->name('update-role');
Route::post('/administrator/role/delete/{id}', 'RoleController@destroy')->name('delete-role');
//End Master Admin Role Route

Route::get('/logs', 'LogsController@index')->name('logs');
Route::get('/logs-admin', 'LogsController@admin')->name('logs_admin');
Route::get('/logs-admin-data', 'LogsController@admin_data')->name('logs-admin-data');


Route::get('/master/kelompok', 'MasterController@listKelompok')->name('list-kelompok');
Route::get('/master/kelompok/get', 'MasterController@listKelompokGet')->name('list-kelompok-get');
Route::get('/master/kelompok/add', 'MasterController@addKelompok')->name('add-kelompok');
Route::get('/master/kelompok/edit/{id}', 'MasterController@editKelompok')->name('edit-kelompok');
Route::post('/master/kelompok/update', 'MasterController@updateKelompok')->name('update-kelompok');
Route::post('/master/kelompok/store', 'MasterController@storeKelompok')->name('store-kelompok');
Route::post('/master/kelompok/delete/{id}', 'MasterController@deleteKelompok')->name('delete-kelompok');

Route::get('/master/marginkeuntungan', 'MasterController@listMarginkeuntungan')->name('list-marginkeuntungan');
Route::get('/master/marginkeuntungan/get', 'MasterController@listMarginkeuntunganGet')->name('list-marginkeuntungan-get');
Route::get('/master/marginkeuntungan/add', 'MasterController@addMarginkeuntungan')->name('add-marginkeuntungan');
Route::get('/master/marginkeuntungan/edit/{id}', 'MasterController@editMarginkeuntungan')->name('edit-marginkeuntungan');
Route::post('/master/marginkeuntungan/update', 'MasterController@updateMarginkeuntungan')->name('update-marginkeuntungan');
Route::post('/master/marginkeuntungan/store', 'MasterController@storeMarginkeuntungan')->name('store-marginkeuntungan');
Route::post('/master/marginkeuntungan/delete/{id}', 'MasterController@deleteMarginkeuntungan')->name('delete-marginkeuntungan');

Route::get('/nasabah', 'NasabahController@listNasabah')->name('list-nasabah');
Route::get('/nasabah/get', 'NasabahController@listNasabahGet')->name('list-nasabah-get');
Route::get('/nasabah/add', 'NasabahController@addNasabah')->name('add-nasabah');
Route::get('/nasabah/edit/{id}', 'NasabahController@editNasabah')->name('edit-nasabah');
Route::post('/nasabah/update', 'NasabahController@updateNasabah')->name('update-nasabah');
Route::post('/nasabah/store', 'NasabahController@storeNasabah')->name('store-nasabah');
Route::post('/nasabah/delete/{id}', 'NasabahController@deleteNasabah')->name('delete-nasabah');

Route::get('/transaksi', 'TransaksiController@list')->name('list-transaksi');
Route::get('/transaksi/get-list', 'TransaksiController@get_list')->name('list-transaksi-get');
Route::get('/transaksi/add', 'TransaksiController@add')->name('add-transaksi');
Route::get('/transaksi/edit/{id}', 'TransaksiController@edit')->name('edit-transaksi');
Route::post('/transaksi/update{id}', 'TransaksiController@update')->name('update-transaksi');
Route::post('/transaksi/store', 'TransaksiController@store')->name('store-transaksi');
Route::post('/transaksi/delete/{id}', 'TransaksiController@delete')->name('delete-transaksi');
Route::get('/transaksi/detail/{id}', 'TransaksiController@detail')->name('detail-transaksi');
