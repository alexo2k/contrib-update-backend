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

Route::get('/', function () {
    return view('welcome');
});
// Route::get('aportacion', 'ContribController@index');
// Route::get('aportacion/{idEmpleado}', 'ContribController@show');
// Route::get('adeudo/{idEmpleado}', 'DebitController@show');
Route::post('aportacion', 'ContribController@recuperaAportacion');
Route::post('adeudo', 'DebitController@recuperaAdeudo');
Route::post('login/acceso', 'LoginController@validaAcceso');
// Route::post('docbox/{rfcEmpleado}','LoginController@recuperaIdDocBox');
Route::post('docbox/obtenEmpleado', 'LoginController@recuperaIdDocBox');
Route::post('docbox/tramites', 'DockBoxController@tramitesEmpleado');

