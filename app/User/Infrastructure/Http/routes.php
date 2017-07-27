<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'my', 'namespace' => 'User\Infrastructure\Http\Controller'], function() {

    // Profile
    Route::get('profile', ['as' => 'user.profile.profile', 'uses' => 'ProfileController@profile']);
    Route::patch('profile', ['as' => 'user.profile.update', 'uses' => 'ProfileController@update']);
    Route::get('profile/email', ['as' => 'user.profile.email', 'uses' => 'ProfileController@editEmail']);
    Route::patch('profile/email', ['uses' => 'ProfileController@updateEmail']);
    Route::get('profile/password', ['as' => 'user.profile.password', 'uses' => 'ProfileController@editPassword']);
    Route::patch('profile/password', ['uses' => 'ProfileController@updatePassword']);
    Route::get('profile/avatar', ['as' => 'user.profile.avatar', 'uses' => 'ProfileController@editAvatar']);
    Route::post('profile/avatar', ['uses' => 'ProfileController@updateAvatar']);
    Route::get('profile/notifications', ['as' => 'user.profile.notifications', 'uses' => 'ProfileController@editNotifications']);
    Route::patch('profile/notifications', ['uses' => 'ProfileController@updateNotifications']);
    Route::get('profile/delete_avatar', ['as' => 'user.profile.delete_avatar', 'uses' => 'ProfileController@deleteAvatar']);

});

Route::group(['middleware' => ['web'], 'namespace' => 'User\Infrastructure\Http\Controller'], function() {

    Route::get('register', ['as' => 'registration.registration', 'uses' => 'RegistrationController@registration']);
    Route::post('register', ['as' => 'registration.register', 'uses' => 'RegistrationController@register']);
    Route::get('login', ['as' => 'login', 'uses' => 'LoginController@showLoginForm']);
    Route::post('login', ['as' => 'login.authenticate', 'uses' => 'LoginController@login']);
    Route::get('logout', ['as' => 'login.logout', 'uses' => 'LoginController@logout']);

});
