<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');


Route::get('auth/login', 'Auth\AuthController@getLogin');

Route::get('auth/register', 'Auth\AuthController@getRegistration');

Route::post('auth/login', 'Auth\AuthController@postLogin');

Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::post('create-new-user-admin', 'HomeController@postCreateNewUserByAdmin');

Route::get('create-admin-user', array(
    'as' => 'create-admin-user',
    'uses' => 'HomeController@getCreateAdminUser'
));

Route::post('create-new-project', 'HomeController@postCreateNewProject');
Route::post('save-edit-project', 'HomeController@postEditNewProject');
Route::post('edit-new-user', 'HomeController@postEditNewUser');

Route::get('list-projects', array(
    'as' => 'list-projects',
    'uses' => 'HomeController@getListProjects'
));

Route::get('list-admin-projects', array(
    'as' => 'list-projects',
    'uses' => 'HomeController@getListProjectsForAdmin'
));

Route::get('projects', array(
    'as' => 'projects',
    'uses' => 'HomeController@getProjects'
));

//Route::get('create-user', array(
//    'as' => 'create-user',
//    'uses' => 'HomeController@getCreateUser'
//));

Route::get('create-project', array(
    'as' => 'create-project',
    'uses' => 'HomeController@getCreateProject'
));

Route::get('list-users', array(
    'as' => 'list-users',
    'uses' => 'HomeController@getListUsers'
));

Route::get('logout', array(
    'as' => 'logout',
    'uses' => 'HomeController@getLogout'
));

Route::get('approve-request/{user_id}', array(
    'as' => 'approve-request',
    'uses' => 'HomeController@getApprovalRequest'
));

Route::get('reject-request/{user_id}', array(
    'as' => 'reject-request',
    'uses' => 'HomeController@getRejectionRequest'
));

Route::get('download-document/{pdf_link}', array(
    'as' => 'download-document',
    'uses' => 'HomeController@getDownloadDocument'
));

Route::get('edit-project/{project_id}', array(
    'as' => 'edit-project',
    'uses' => 'HomeController@getEditProject'
));

Route::get('edit-user/{user_id}', array(
    'as' => 'edit-user',
    'uses' => 'HomeController@getEditUser'
));