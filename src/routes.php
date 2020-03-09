<?php

Route::group(['namespace' => 'Drivezy\LaravelUtility\Controllers',
              'prefix'    => 'api/record'], function () {

    Route::resource('userPreference', 'UserPreferenceController');

    //routes related to the event management
    Route::resource('event', 'EventDetailController');
    Route::resource('eventQueue', 'EventQueueController');
    Route::resource('eventTrigger', 'EventTriggerController');
    Route::resource('scheduledJob', 'ScheduledJobController');

    Route::resource('comment', 'CommentController');
});

?>
