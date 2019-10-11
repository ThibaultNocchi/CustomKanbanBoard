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
    $router->get('users', ['middleware' => 'auth', 'uses' => 'BoardController@users']);
});