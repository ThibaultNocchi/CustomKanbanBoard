<?php

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

$router->group(['prefix' => 'board'], function () use($router) {
    $router->get('{id}', ['uses' => 'BoardController@show']);
    $router->get('', ['middleware' => 'auth', 'uses' => 'BoardController@index']);
    $router->post('', ['uses' => 'BoardController@store']);
});

$router->group(['prefix' => 'user', 'middleware' => 'auth'], function () use($router) {
    $router->get('', ['uses' => 'UserController@index']);
    $router->post('', ['uses' => 'UserController@store']);
    $router->delete('{id:[0-9]+}', ['uses' => 'UserController@destroy']);
});

$router->group(['prefix' => 'card', 'middleware' => 'auth'], function () use($router) {
    $router->get('', ['uses' => 'CardController@index']);
    $router->post('', ['uses' => 'CardController@store']);
    $router->post('{card_id:[0-9]+}', ['uses' => 'TaskController@store']);
    $router->put('{id1:[0-9]+}/switch_to/{order:[0-9]+}', ['uses' => 'CardController@switch']);
    $router->put('{id:[0-9]+}', ['uses' => 'CardController@update']);
    $router->delete('{id:[0-9]+}', ['uses' => 'CardController@destroy']);
});

$router->group(['prefix' => 'task', 'middleware' => 'auth'], function () use($router) {
    $router->put('{id:[0-9]+}', ['uses' => 'TaskController@editTask']);
    $router->delete('{id:[0-9]+}', ['uses' => 'TaskController@destroy']);
});