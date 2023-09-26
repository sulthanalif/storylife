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
    
     //gallery
    $router->get('gallery', ['uses' => 'GalleryController@index']);

    $router->get('gallery/{id}', ['uses' => 'GalleryController@show']);

    $router->post('gallery', ['uses' => 'GalleryController@store']); 

    $router->post('gallery/{id}', ['uses' => 'GalleryController@update']);

    $router->delete('gallery/{id}', ['uses' => 'GalleryController@destroy']);


    //category
    $router->get('category', ['uses' => 'CategoryController@index']);

    $router->get('category/{id}', ['uses' => 'CategoryController@show']);

    $router->post('category', ['uses' => 'CategoryController@store']);

    $router->post('category/{id}', ['uses' => 'CategoryController@update']);

    $router->delete('category/{id}', ['uses' => 'CategoryController@destroy']);
});

