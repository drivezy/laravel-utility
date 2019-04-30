<?php

Route::group(['namespace' => 'Drivezy\LaravelUtility\Controllers',
              'prefix'    => 'api/record'], function () {

    Route::resource('userPreference', 'UserPreferenceController');
});

?>
