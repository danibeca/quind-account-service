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
$router->group([
    'prefix' => 'api/v1', 'middleware' => ['cors', 'log']], function () use ($router) {


    $router->group([
        'prefix'    => '/roottags',
        'namespace' => 'TagTree'], function () use ($router) {
        $router->get('/', ['uses' => 'RootTagController@index']);
        $router->post('/', ['uses' => 'RootTagController@store']);
    });

    $router->group([
        'prefix'    => '/components',
        'namespace' => 'Account'], function () use ($router) {
        $router->get('/', ['uses' => 'ComponentController@index']);
        $router->post('/', ['uses' => 'ComponentController@store']);
        $router->get('/{id:[\d]+}', ['as' => 'component.show', 'uses' => 'ComponentController@show']);
        $router->put('/{id:[\d]+}', ['as' => 'component.update', 'uses' => 'ComponentController@update']);
        $router->delete('/{id:[\d]+}', ['as' => 'component.destroy', 'uses' => 'ComponentController@destroy']);


        $router->get('/{id:[\d]+}/users', ['uses' => 'ComponentUserController@index']);
        $router->post('/{id:[\d]+}/users', ['uses' => 'ComponentUserController@store']);

        $router->get('/{id:[\d]+}/qas', ['uses' => 'ComponentQualitySystemController@index']);
        $router->post('/{id:[\d]+}/qas', ['uses' => 'ComponentQualitySystemController@store']);

    });

    $router->group([
        'prefix'    => '/qas',
        'namespace' => 'QualitySystem'], function () use ($router) {
        $router->get('/', ['uses' => 'QualitySystemController@index']);
    });

    $router->group([
        'prefix'    => '/qas/validate',
        'namespace' => 'QualitySystem'], function () use ($router) {
        $router->get('/', ['uses' => 'QualitySystemController@validate2']);
    });


    $router->group([
        'prefix'    => '/users',
        'namespace' => 'Account'], function () use ($router) {

        $router->get('/{id:[\d]+}/croot', ['uses' => 'UserRootComponentController@index']);
    });
});
