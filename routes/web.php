<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->get('/gallery/get-list', 'GalleryController@getList');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/logout', 'AuthController@logout');
        $router->get('gallery', ['uses' => 'GalleryController@index']);
        $router->get('gallery/{id}', ['uses' => 'GalleryController@show']);
        $router->post('gallery', ['uses' => 'GalleryController@store']);
        $router->put('gallery/{id}', ['uses' => 'GalleryController@update']);
        $router->delete('gallery/{id}', ['uses' => 'GalleryController@destroy']);

        //status
        $router->get('status', ['uses' => 'StatusController@index']);
        $router->get('status/{id}', ['uses' => 'StatusController@show']);
        $router->post('status', ['uses' => 'StatusController@store']);
        $router->put('status/{id}', ['uses' => 'StatusController@update']);
        $router->delete('status/{id}', ['uses' => 'StatusController@destroy']);
        $router->put('status/{id}', ['uses' => 'StatusController@restore']);
        // $router->group(['middleware' => 'check-signature-url-expiration'], function () use ($router) {
        //     $router->get('/form', 'ExampleController@form');
        // });

        //service
        $router->get('service', ['uses' => 'ServiceController@index']);
        $router->get('service/form', ['uses' => 'ServiceController@create']);
        $router->post('service', ['uses' => 'ServiceController@store']);
        $router->get('service/{id}', ['uses' => 'ServiceController@show']);
        $router->get('service/edit/{id}', ['uses' => 'ServiceController@edit']);
        $router->put('service/{id}', ['uses' => 'ServiceController@update']);
        $router->delete('service/{id}', ['uses' => 'ServiceController@destroy']);
        $router->put('service/{id}', ['uses' => 'ServiceController@restore']);

        //user
        $router->get('user', ['uses' => 'UserController@index']);

        $router->get('user/{id}', ['uses' => 'UserController@show']);

        $router->post('user', ['uses' => 'UserController@store']);

        $router->put('user/profile/{id}', [
            'uses' => 'UserController@updateProfile',
            'as' => 'profile'
        ]);

        $router->put('user/password/{id}', ['uses' => 'UserController@updatePass', 'as' => 'password']);

        $router->delete('user/{id}', ['uses' => 'UserController@destroy']);

        $router->get('cekcek', ['uses', 'UserController@cek']);
        //category
        $router->get('category', ['uses' => 'CategoryController@index']);

        $router->get('category/{id}', ['uses' => 'CategoryController@show']);

        $router->post('category', ['uses' => 'CategoryController@store']);

        $router->put('category/{id}', ['uses' => 'CategoryController@update']);

        $router->delete('category/{id}', ['uses' => 'CategoryController@destroy']);
        //review
        $router->get('review', ['uses' => 'ReviewController@index']);

        $router->get('review/{id}', ['uses' => 'ReviewController@show']);

        $router->post('review', ['uses' => 'ReviewController@store']);

        $router->put('review/{id}', ['uses' => 'ReviewController@update']);

        $router->delete('review/{id}', ['uses' => 'ReviewController@destroy']);
    });

    $router->get('review/test', ['uses' => 'ReviewController@test']);
});











