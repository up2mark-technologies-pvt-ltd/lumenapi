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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Routes for user
$router->post('createuser', ['as' => 'createUser', 'uses' => 'UserDataController@createUser']);
$router->get('readuser', 	['as' => 'readUser',   'uses' => 'UserDataController@readUser']);
$router->post('updateuser', ['as' => 'updateUser', 'uses' => 'UserDataController@updateUser']);
$router->post('deleteuser', ['as' => 'deleteUser', 'uses' => 'UserDataController@deleteUser']);
// Routes for team
$router->post('createteam', ['as' => 'createteam', 'uses' => 'TeamDataController@createTeam']);
$router->get('readteam', 	['as' => 'readteam',   'uses' => 'TeamDataController@readTeam']);
$router->post('updateteam', ['as' => 'updateteam', 'uses' => 'TeamDataController@updateTeam']);
$router->post('deleteteam', ['as' => 'deleteteam', 'uses' => 'TeamDataController@deleteTeam']);

// Route for A user can be assigned to a team and Set a user as team owner 
$router->post('assignusertoteam', ['as' => 'assignusertoteam', 'uses' => 'TeamDataController@assignUserToTeam']);

// Route for user belong team

$router->post('userbelongtoteam', ['as' => 'userbelongtoteam', 'uses' => 'TeamDataController@userBelongToTeam']);


