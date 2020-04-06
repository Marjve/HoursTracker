<?php

use Illuminate\Http\Request;

Route::group([
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::get('/lastLog', 'TimeLogController@lastLogByUser');
Route::post('/addcheckin', 'TimeLogController@addCheckIn');
Route::post('/addcheckout', 'TimeLogController@addCheckOut');

//Route::get('/activeUsers', 'TimeLogController@getActiveUsers');

Route::get('/activeUsers', 'UsersController@getActiveUsers');
Route::get('/getall', 'UsersController@getAllLoggedTime');
Route::get('/getUserByid', 'UsersController@getUserByID');
Route::post('/updateStatus', 'UsersController@updateActiveStatus');


Route::get('/getAllTodos',   'TodoController@getTodosByUserID');
Route::post('/addTodo',      'TodoController@addTodo');
Route::post('/updateTodo',   'TodoController@updateTodo');
Route::delete('/removeTodo', 'TodoController@removeTodo');
Route::post('/removeCompletedTodos', 'TodoController@removeCompletedTodos');


