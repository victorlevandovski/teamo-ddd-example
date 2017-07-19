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

    // Discussion
    Route::get('project/{project}/discussions', ['as' => 'project.discussion.index', 'uses' => 'DiscussionController@index']);
    Route::get('project/{project}/discussions/archive', ['as' => 'project.discussion.archive', 'uses' => 'DiscussionController@archive']);
    Route::get('project/{project}/discussion/{discussion}/archive', ['as' => 'project.discussion.archive_discussion', 'uses' => 'DiscussionController@archiveDiscussion']);
    Route::get('project/{project}/discussion/{discussion}/restore', ['as' => 'project.discussion.restore_discussion', 'uses' => 'DiscussionController@restoreDiscussion']);
    Route::get('project/{project}/discussion/create', ['as' => 'project.discussion.create', 'uses' => 'DiscussionController@create']);
    Route::get('project/{project}/discussion/{discussion}/edit', ['as' => 'project.discussion.edit', 'uses' => 'DiscussionController@edit']);
    Route::get('project/{project}/discussion/{discussion}/comment/{comment}/edit', ['as' => 'project.discussion.edit_comment', 'uses' => 'DiscussionController@editComment']);
    Route::post('project/{project}/discussion', ['as' => 'project.discussion.store', 'uses' => 'DiscussionController@store']);
    Route::patch('project/{project}/discussion/{discussion}', ['as' => 'project.discussion.update', 'uses' => 'DiscussionController@update']);
    Route::post('project/{project}/discussion/{discussion}/comment', ['as' => 'project.discussion.store_comment', 'uses' => 'DiscussionController@storeComment']);
    Route::patch('project/{project}/discussion/{discussion}/comment/{comment}', ['as' => 'project.discussion.update_comment', 'uses' => 'DiscussionController@updateComment']);
    Route::get('project/{project}/discussion/{discussion}/delete', ['as' => 'project.discussion.delete', 'uses' => 'DiscussionController@destroy']);
    Route::get('project/{project}/discussion/{discussion}', ['as' => 'project.discussion.show', 'uses' => 'DiscussionController@show']);

    // Attachment
    Route::delete('ajax_delete_attachment/{attachment}', ['as' => 'project.attachment.ajax_delete_attachment', 'uses' => 'AttachmentController@ajaxDeleteAttachment']);
    Route::post('ajax_file_upload', 'AttachmentController@ajaxUploadFile');
    Route::patch('ajax_file_upload', 'AttachmentController@ajaxUploadFile');
    Route::get('download/{attachment}/{name}', ['as' => 'project.download', 'uses' => 'AttachmentController@download']);
    Route::get('attachment/{attachment}/{name}', ['as' => 'project.attachment', 'uses' => 'AttachmentController@attachment']);

});
