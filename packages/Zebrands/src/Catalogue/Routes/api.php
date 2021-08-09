<?php

use Zebrands\Catalogue\Controllers\CatalogController;
use Zebrands\Catalogue\Controllers\AuthController;

/* API AUTH */
Route::post('/auth', AuthController::class.'@auth')->name('auth');

/* RUTAS SOLO CON JWT  */
Route::group(['middleware' => ['zebrands.auth']], function() {

	$auth =  AuthController::class;

	Route::get('ping', 		$auth.'@ping')->name('ping');
	Route::post('login', 	$auth.'@login')->name('login');
	Route::post('logout', 	$auth.'@logout')->name('logout');
});


Route::group(['middleware' => ['zebrands.auth', 'logged']], function() {

	$catalog = 	CatalogController::class;

	#Productos
	Route::get('products',			$catalog.'@index_products');
	Route::post('products',			$catalog.'@create_product');
	Route::get('products/{id}',		$catalog.'@show_product');
	Route::put('products/{id}',		$catalog.'@update_product');
	Route::delete('products/{id}',	$catalog.'@delete_product');

	#Usuarios
	Route::get('users',				$catalog.'@index_users');
	Route::post('users',			$catalog.'@create_user');
	Route::get('users/{id}',		$catalog.'@show_user');
	Route::put('users/{id}',		$catalog.'@update_user');
	Route::delete('users/{id}',		$catalog.'@delete_user');

	#Marcas
	Route::get('brands',			$catalog.'@index_brands');
	Route::post('brands',			$catalog.'@create_brand');
	Route::get('brands/{id}',		$catalog.'@show_brand');
	Route::put('brands/{id}',		$catalog.'@update_brand');
	Route::delete('brands/{id}',	$catalog.'@delete_brand');

});