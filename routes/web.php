<?php

use Illuminate\Contracts\Routing\ResponseFactory;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
return view('home');
});

$router->get('/login', function () {
return 'ini login';
});





$router->group(['prefix' => 'admin'], function() use($router){

    $router->get('galery', ['uses' => 'GaleryController@index']);

    $router->get('galery/{id}', ['uses' => 'GaleryController@show']);

    $router->post('galery', ['uses' => 'GaleryController@store']); // Changed this line to use the 'store' method

    $router->put('galery/{id}', ['uses' => 'GaleryController@update']);

    $router->delete('galery/{id}', ['uses' => 'GaleryController@destroy']);

});

