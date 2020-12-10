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

//测试路由
$router->get('test' , 'ExampleController@test');

//加入助力码
$router->group(['middleware' => ['LoadIp']], function () use ($router){
    $router->get('/{type}/create/{code}' , 'CodeController@create');
});
//读取助力码
$router->get('/{type}/read' , 'CodeController@read');
//读取助力码数量
$router->get('/{type}/count' , 'CodeController@count');
