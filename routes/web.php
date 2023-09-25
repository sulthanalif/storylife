<?php

use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Controllers\AuthController;

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



$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');

$router->get('/user/{id}', 'UserController@show');





$router->group(['prefix' => 'admin'], function() use($router){

    $router->get('gallery', ['uses' => 'GalleryController@index']);

    $router->get('gallery/{id}', ['uses' => 'GalleryController@show']);

    $router->post('gallery', ['uses' => 'GalleryController@store']); // Changed this line to use the 'store' method

    $router->put('gallery/{id}', ['uses' => 'GalleryController@update']);

    $router->delete('gallery/{id}', ['uses' => 'GalleryController@destroy']);

});

