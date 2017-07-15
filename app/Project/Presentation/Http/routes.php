<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'my', 'namespace' => 'Project\Presentation\Http\Controller'], function() {

    // Projects
    Route::get('', ['as' => 'project.project.index', 'uses' => 'ProjectController@index']);
    Route::get('project/create', ['as' => 'project.project.create', 'uses' => 'ProjectController@create']);
    Route::get('project/{project}/team', ['as' => 'project.project.team', 'uses' => 'ProjectController@team']);
    Route::post('project/{project}/team', ['as' => 'project.project.store_invite', 'uses' => 'ProjectController@store_invite']);
    Route::get('project/{project}/user/remove', ['as' => 'project.project.remove_me', 'uses' => 'ProjectController@remove_me']);
    Route::get('project/{project}/user/{user}/remove', ['as' => 'project.project.remove_user', 'uses' => 'ProjectController@remove_user']);
    Route::get('project/{project}/invite/{invite}/remove', ['as' => 'project.project.remove_invite', 'uses' => 'ProjectController@remove_invite']);
    Route::get('project/{project}/edit', ['as' => 'project.project.edit', 'uses' => 'ProjectController@edit']);
    Route::get('project/archive', ['as' => 'project.project.archive', 'uses' => 'ProjectController@archive']);
    Route::get('project/{project}/archive', ['as' => 'project.project.archive_project', 'uses' => 'ProjectController@archive_project']);
    Route::get('project/{project}/restore', ['as' => 'project.project.restore_project', 'uses' => 'ProjectController@restore_project']);
    Route::post('project', ['as' => 'project.project.store', 'uses' => 'ProjectController@store']);
    Route::patch('project/{project}', ['as' => 'project.project.update', 'uses' => 'ProjectController@update']);
    Route::get('project/{project}', ['as' => 'project.project.show', 'uses' => 'ProjectController@show']);
    Route::get('project/{project}/activity', ['as' => 'project.project.activity', 'uses' => 'ProjectController@activity']);

});
