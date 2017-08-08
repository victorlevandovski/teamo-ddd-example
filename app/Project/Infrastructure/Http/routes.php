<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'my', 'namespace' => 'Project\Infrastructure\Http\Controller'], function() {

    // Project
    Route::get('', ['as' => 'project.project.index', 'uses' => 'ProjectController@index']);
    Route::get('project/create', ['as' => 'project.project.create', 'uses' => 'ProjectController@create']);
    Route::get('project/{projectId}/edit', ['as' => 'project.project.edit', 'uses' => 'ProjectController@edit']);
    Route::get('project/archive', ['as' => 'project.project.archive', 'uses' => 'ProjectController@archive']);
    Route::get('project/{projectId}/archive', ['as' => 'project.project.archive_project', 'uses' => 'ProjectController@archiveProject']);
    Route::get('project/{projectId}/restore', ['as' => 'project.project.restore_project', 'uses' => 'ProjectController@restoreProject']);
    Route::post('project', ['as' => 'project.project.store', 'uses' => 'ProjectController@store']);
    Route::patch('project/{projectId}', ['as' => 'project.project.update', 'uses' => 'ProjectController@update']);
    Route::get('project/{projectId}', ['as' => 'project.project.show', 'uses' => 'ProjectController@show']);
    Route::get('project/{projectId}/activity', ['as' => 'project.project.activity', 'uses' => 'ProjectController@activity']);

    // Team
    Route::get('project/{projectId}/team', ['as' => 'project.team.index', 'uses' => 'TeamController@index']);
    Route::post('project/{projectId}/team', ['as' => 'project.team.store_invite', 'uses' => 'TeamController@storeInvite']);
    Route::get('project/{projectId}/team_member/remove', ['as' => 'project.team.remove_me', 'uses' => 'TeamController@removeMe']);
    Route::get('project/{projectId}/team_member/{user}/remove', ['as' => 'project.team.remove_team_member', 'uses' => 'TeamController@removeTeamMember']);
    Route::get('project/{projectId}/invite/{invite}/remove', ['as' => 'project.team.remove_invite', 'uses' => 'TeamController@removeInvite']);

    // Discussion
    Route::get('project/{projectId}/discussions', ['as' => 'project.discussion.index', 'uses' => 'DiscussionController@index']);
    Route::get('project/{projectId}/discussions/archive', ['as' => 'project.discussion.archive', 'uses' => 'DiscussionController@archive']);
    Route::get('project/{projectId}/discussion/{discussionId}/archive', ['as' => 'project.discussion.archive_discussion', 'uses' => 'DiscussionController@archiveDiscussion']);
    Route::get('project/{projectId}/discussion/{discussionId}/restore', ['as' => 'project.discussion.restore_discussion', 'uses' => 'DiscussionController@restoreDiscussion']);
    Route::get('project/{projectId}/discussion/create', ['as' => 'project.discussion.create', 'uses' => 'DiscussionController@create']);
    Route::get('project/{projectId}/discussion/{discussionId}/edit', ['as' => 'project.discussion.edit', 'uses' => 'DiscussionController@edit']);
    Route::get('project/{projectId}/discussion/{discussionId}/comment/{commentId}/edit', ['as' => 'project.discussion.edit_comment', 'uses' => 'DiscussionController@editComment']);
    Route::post('project/{projectId}/discussion', ['as' => 'project.discussion.store', 'uses' => 'DiscussionController@store']);
    Route::patch('project/{projectId}/discussion/{discussionId}', ['as' => 'project.discussion.update', 'uses' => 'DiscussionController@update']);
    Route::post('project/{projectId}/discussion/{discussionId}/comment', ['as' => 'project.discussion.store_comment', 'uses' => 'DiscussionController@storeComment']);
    Route::patch('project/{projectId}/discussion/{discussionId}/comment/{commentId}', ['as' => 'project.discussion.update_comment', 'uses' => 'DiscussionController@updateComment']);
    Route::get('project/{projectId}/discussion/{discussionId}/delete', ['as' => 'project.discussion.delete', 'uses' => 'DiscussionController@destroy']);
    Route::get('project/{projectId}/discussion/{discussionId}', ['as' => 'project.discussion.show', 'uses' => 'DiscussionController@show']);
    Route::delete('project/{projectId}/discussion/{discussionId}/comment/{commentId}', ['as' => 'project.discussion.ajax_delete_comment', 'uses' => 'DiscussionController@ajaxDestroyComment']);
    Route::delete('project/{projectId}/discussion/{discussionId}/attachment/{attachmentId}', ['as' => 'project.discussion.ajax_delete_attachment', 'uses' => 'DiscussionController@ajaxDestroyAttachment']);
    Route::delete('project/{projectId}/discussion/{discussionId}/comment/{commentId}/attachment/{attachmentId}', ['as' => 'project.discussion.ajax_delete_comment_attachment', 'uses' => 'DiscussionController@ajaxDestroyCommentAttachment']);

    // Event
    Route::get('project/{projectId}/events', ['as' => 'project.event.index', 'uses' => 'EventController@index']);
    Route::get('project/{projectId}/events/archive', ['as' => 'project.event.archive', 'uses' => 'EventController@archive']);
    Route::get('project/{projectId}/event/{eventId}/archive', ['as' => 'project.event.archive_event', 'uses' => 'EventController@archiveEvent']);
    Route::get('project/{projectId}/event/{eventId}/restore', ['as' => 'project.event.restore_event', 'uses' => 'EventController@restoreEvent']);
    Route::get('project/{projectId}/event/create', ['as' => 'project.event.create', 'uses' => 'EventController@create']);
    Route::get('project/{projectId}/event/{eventId}/edit', ['as' => 'project.event.edit', 'uses' => 'EventController@edit']);
    Route::get('project/{projectId}/event/{eventId}/comment/{commentId}/edit', ['as' => 'project.event.edit_comment', 'uses' => 'EventController@editComment']);
    Route::post('project/{projectId}/event', ['as' => 'project.event.store', 'uses' => 'EventController@store']);
    Route::patch('project/{projectId}/event/{eventId}', ['as' => 'project.event.update', 'uses' => 'EventController@update']);
    Route::post('project/{projectId}/event/{eventId}/comment', ['as' => 'project.event.store_comment', 'uses' => 'EventController@storeComment']);
    Route::patch('project/{projectId}/event/{eventId}/comment/{commentId}', ['as' => 'project.event.update_comment', 'uses' => 'EventController@updateComment']);
    Route::get('project/{projectId}/event/{eventId}/delete', ['as' => 'project.event.delete', 'uses' => 'EventController@destroy']);
    Route::get('project/{projectId}/event/{eventId}', ['as' => 'project.event.show', 'uses' => 'EventController@show']);
    Route::delete('project/{projectId}/event/{eventId}/comment/{commentId}', ['as' => 'project.event.ajax_delete_comment', 'uses' => 'EventController@ajaxDestroyComment']);
    Route::delete('project/{projectId}/event/{eventId}/comment/{commentId}/attachment/{attachmentId}', ['as' => 'project.event.ajax_delete_comment_attachment', 'uses' => 'EventController@ajaxDestroyCommentAttachment']);

    // To-do list
    Route::get('project/{projectId}/todo_lists', ['as' => 'project.todo_list.index', 'uses' => 'TodoListController@index']);
    Route::get('project/{projectId}/todo_lists/archive', ['as' => 'project.todo_list.archive', 'uses' => 'TodoListController@archive']);
    Route::get('project/{projectId}/todo_list/{todoListId}/archive', ['as' => 'project.todo_list.archive_todo_list', 'uses' => 'TodoListController@archiveTodoList']);
    Route::get('project/{projectId}/todo_list/{todoListId}/restore', ['as' => 'project.todo_list.restore_todo_list', 'uses' => 'TodoListController@restoreTodoList']);
    Route::get('project/{projectId}/todo_list/create', ['as' => 'project.todo_list.create', 'uses' => 'TodoListController@create']);
    Route::get('project/{projectId}/todo_list/{todoListId}/edit', ['as' => 'project.todo_list.edit', 'uses' => 'TodoListController@edit']);
    Route::post('project/{projectId}/todo_list', ['as' => 'project.todo_list.store', 'uses' => 'TodoListController@store']);
    Route::patch('project/{projectId}/todo_list/{todoListId}', ['as' => 'project.todo_list.update', 'uses' => 'TodoListController@update']);
    Route::get('project/{projectId}/todo_list/{todoListId}/delete', ['as' => 'project.todo_list.delete', 'uses' => 'TodoListController@destroy']);
    Route::get('project/{projectId}/todo_list/{todoListId}', ['as' => 'project.todo_list.show', 'uses' => 'TodoListController@show']);

    // To-do
    Route::get('project/{projectId}/todo_list/{todoListId}/todo/{todoId}', ['as' => 'project.todo_list.todo.show', 'uses' => 'TodoController@show']);
    Route::post('project/{projectId}/todo_list/{todoListId}/todo/{todoId}/comment', ['as' => 'todoItem.store_comment', 'uses' => 'TodoItemController@store_comment']);
    Route::get('project/{projectId}/todo_list/{todoListId}/todo/{todoId}/comment/{commentId}/edit', ['as' => 'todoItem.edit_comment', 'uses' => 'TodoItemController@edit_comment']);
    Route::patch('project/{projectId}/todo_list/{todoListId}/todo/{todoId}/comment/{commentId}', ['as' => 'todoItem.update_comment', 'uses' => 'TodoItemController@update_comment']);
    Route::get('project/{projectId}/todo_list/{todoListId}/todo/{todoId}/delete', ['as' => 'todoItem.delete', 'uses' => 'TodoItemController@destroy']);
    Route::post('ajax_add_todo_item/{projectId}/{todoListId}', 'TodoController@ajaxAddTodo');
    Route::post('ajax_edit_todo_item/{projectId}/{todoListId}/{todoId}', 'TodoController@ajaxEditTodo');
    Route::post('ajax_check_todo_item/{projectId}/{todoListId}/{todoId}', 'TodoController@ajaxCheckTodo');
    Route::post('ajax_sort_todo_item/{projectId}/{todoListId}/{todoId}', 'TodoController@ajaxReorderTodo');
    Route::post('ajax_delete_todo_item/{projectId}/{todoListId}/{todoId}', 'TodoController@ajaxDeleteTodo');


    // Attachment
    Route::post('ajax_file_upload', 'AttachmentController@ajaxUploadFile');
    Route::patch('ajax_file_upload', 'AttachmentController@ajaxUploadFile');
    Route::get('download/{attachmentId}/{name}', ['as' => 'project.attachment.download', 'uses' => 'AttachmentController@download']);
    Route::get('attachment/{attachmentId}/{name}', ['as' => 'project.attachment', 'uses' => 'AttachmentController@attachment']);

});
