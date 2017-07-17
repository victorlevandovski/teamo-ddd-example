<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'my', 'namespace' => 'Project\Presentation\Http\Controller'], function() {

    // Project
    Route::get('', ['as' => 'project.project.index', 'uses' => 'ProjectController@index']);
    Route::get('project/create', ['as' => 'project.project.create', 'uses' => 'ProjectController@create']);
    Route::get('project/{project}/edit', ['as' => 'project.project.edit', 'uses' => 'ProjectController@edit']);
    Route::get('project/archive', ['as' => 'project.project.archive', 'uses' => 'ProjectController@archive']);
    Route::get('project/{project}/archive', ['as' => 'project.project.archive_project', 'uses' => 'ProjectController@archiveProject']);
    Route::get('project/{project}/restore', ['as' => 'project.project.restore_project', 'uses' => 'ProjectController@restoreProject']);
    Route::post('project', ['as' => 'project.project.store', 'uses' => 'ProjectController@store']);
    Route::patch('project/{project}', ['as' => 'project.project.update', 'uses' => 'ProjectController@update']);
    Route::get('project/{project}', ['as' => 'project.project.show', 'uses' => 'ProjectController@show']);
    Route::get('project/{project}/activity', ['as' => 'project.project.activity', 'uses' => 'ProjectController@activity']);

    // Team
    Route::get('project/{project}/team', ['as' => 'project.team.index', 'uses' => 'TeamController@index']);
    Route::post('project/{project}/team', ['as' => 'project.team.store_invite', 'uses' => 'TeamController@storeInvite']);
    Route::get('project/{project}/team_member/remove', ['as' => 'project.team.remove_me', 'uses' => 'TeamController@removeMe']);
    Route::get('project/{project}/team_member/{user}/remove', ['as' => 'project.team.remove_team_member', 'uses' => 'TeamController@removeTeamMember']);
    Route::get('project/{project}/invite/{invite}/remove', ['as' => 'project.team.remove_invite', 'uses' => 'TeamController@removeInvite']);

});
