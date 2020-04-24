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

