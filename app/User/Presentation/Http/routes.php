<?php

Route::namespace('User\Presentation\Http\Controller')->group(function() {

    Route::get('register', [
        'uses' => 'RegistrationController@register',
        'as' => 'registration.register'
    ]);

});
