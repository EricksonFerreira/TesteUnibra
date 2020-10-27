<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers\Api',
    'prefix' => 'auth'
], function($router){
    Route::post('login','UserController@login');
    Route::post('register','UserController@register');
    Route::post('logout','UserController@logout');
    Route::get('profile','UserController@profile');
    Route::post('refresh','UserController@refresh');

});
Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers\Api',
], function($router){
    Route::resource('artigos','ArtigoController')->except('create','edit');
});
