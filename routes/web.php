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
    $router->get('/order', 'OrderController@index');

    $router->get('/review', ['uses' => 'ReviewController@index']);
    $router->get('/category', ['uses' => 'CategoryController@index']);
    $router->get('service', ['uses' => 'ServiceController@index']);

    //cek
    $router->get('/order/form-create', ['uses' => 'OrderController@create']);



    //getList
    $router->get('/gallery/get-list', 'GalleryController@getList');
    $router->get('/review/get-list', 'ReviewController@getList');
    $router->get('/category/get-list', 'CategoryController@getList');
    $router->get('/order/get-list', 'OrderController@getList');
    $router->get('/user/get-list', 'UserController@getList');
    $router->get('/service/get-list', 'ServiceController@getList');

    //Auth
    $router->group(['middleware' => 'jwt.auth'], function () use ($router) {
        $router->get('/dashboard', ['uses' => 'DashboardController@index']);
        $router->post('/logout', 'AuthController@logout');
        $router->get('/get-token', 'AuthController@getToken');
        $router->get('/refresh-token', 'AuthController@refreshToken');

        //gallery
        $router->get('gallery', ['uses' => 'GalleryController@index']);
        $router->get('gallery/create', ['uses' => 'GalleryController@create']);
        $router->get('gallery/show', ['uses' => 'GalleryController@show']);
        $router->get('gallery/edit', ['uses' => 'GalleryController@edit']);
        $router->post('gallery', ['uses' => 'GalleryController@store']);
        $router->post('gallery/{id}', ['uses' => 'GalleryController@update']);
        $router->delete('gallery/delete', ['uses' => 'GalleryController@destroy']);

        //status
        $router->get('status', ['uses' => 'StatusController@index']);
        $router->get('status/{id}', ['uses' => 'StatusController@show']);
        $router->post('status', ['uses' => 'StatusController@store']);
        $router->post('status/{id}', ['uses' => 'StatusController@update']);
        $router->delete('status/{id}', ['uses' => 'StatusController@destroy']);
        $router->put('status/{id}', ['uses' => 'StatusController@restore']);
        // $router->group(['middleware' => 'check-signature-url-expiration'], function () use ($router) {
        //     $router->get('/form', 'ExampleController@form');
        // });

        //service

        $router->get('service/form', ['uses' => 'ServiceController@create']);
        $router->post('service', ['uses' => 'ServiceController@store']);
        $router->get('service/{id}', ['uses' => 'ServiceController@show']);
        $router->get('service/edit/{id}', ['uses' => 'ServiceController@edit']);
        $router->post('service/{id}', ['uses' => 'ServiceController@update']);
        $router->delete('service/{id}', ['uses' => 'ServiceController@destroy']);
        $router->put('service/{id}', ['uses' => 'ServiceController@restore']);

        //user
        $router->get('user', ['uses' => 'UserController@index']);

        $router->get('user/show', ['uses' => 'UserController@show']);

        $router->post('user', ['uses' => 'UserController@store']);

        $router->post('user/profile/{id}', [
            'uses' => 'UserController@updateProfile',
            'as' => 'profile'
        ]);

        $router->post('user/password/{id}', ['uses' => 'UserController@updatePass', 'as' => 'password']);

        $router->delete('user/{id}', ['uses' => 'UserController@destroy']);


        //category
        $router->get('category/{id}', ['uses' => 'CategoryController@show']);

        $router->post('category', ['uses' => 'CategoryController@store']);

        $router->post('category/{id}', ['uses' => 'CategoryController@update']);

        $router->delete('category', ['uses' => 'CategoryController@destroy']);

        //review
        $router->get('review/show', ['uses' => 'ReviewController@show']);
        $router->get('review/create', ['uses' => 'ReviewController@create']);

        $router->post('review', ['uses' => 'ReviewController@store']);

        $router->post('review/{id}', ['uses' => 'ReviewController@update']);

        $router->delete('review', ['uses' => 'ReviewController@destroy']);
            //search
        $router->get('review/search', ['uses' => 'ReviewController@search']);
            //approve and reject
        $router->put('review/approve', ['uses' => 'ReviewController@approve']);
        $router->put('review/reject', ['uses' => 'ReviewController@reject']);
            //trash
        $router->get('review/trash', ['uses' => 'ReviewController@trash']);
            //restore
        $router->put('review/restore', ['uses' => 'ReviewController@restore']);


        //order
        $router->get('/order/form-create', ['uses' => 'OrderController@create']);

    });
});











