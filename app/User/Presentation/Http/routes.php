<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'my', 'namespace' => 'User\Presentation\Http\Controller'], function() {

    // For testing purposes only
    Route::get('', function () {
        return '<a href="/my/profile">Profile</a> | <a href="/logout">Logout</a>';
    });

    Route::get('profile', [
        'uses' => 'ProfileController@profile',
        'as' => 'profile.profile'
    ]);

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
