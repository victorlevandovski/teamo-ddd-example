<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'my', 'namespace' => 'User\Presentation\Http\Controller'], function() {

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

Route::group(['middleware' => ['web'], 'namespace' => 'User\Presentation\Http\Controller'], function() {

    Route::get('register', [
        'uses' => 'RegistrationController@registration',
        'as' => 'registration.registration'
    ]);

    Route::post('register', [
        'uses' => 'RegistrationController@register',
        'as' => 'registration.register'
    ]);

    Route::get('login', [
        'uses' => 'LoginController@showLoginForm',
        'as' => 'login'
    ]);

    Route::post('login', [
        'uses' => 'LoginController@login',
        'as' => 'login.authenticate'
    ]);

    Route::get('logout', [
        'uses' => 'LoginController@logout',
        'as' => 'login.logout'
    ]);

});
