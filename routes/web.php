<?php

// Route::get('/', function () {
//     return view('welcome');
// });
Route::post('aportacion', 'ContribController@recuperaAportacion');
Route::post('adeudo', 'DebitController@recuperaAdeudo');
Route::post('login/acceso', 'LoginController@validaAcceso');
Route::post('docbox/tramites', 'DockBoxController@tramitesEmpleado');
Route::post('docbox/secretaria', 'DockBoxController@tramitesSecretaria');
Route::post('login/captchaValidate', 'LoginController@validaCaptcha');