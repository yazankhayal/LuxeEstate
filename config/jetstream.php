<?php

use Laravel\Jetstream\Features;

return [

    'stack' => 'inertia',

    'auth_session' => \Illuminate\Session\Middleware\AuthenticateSession::class,

    'guard' => 'web',

    'features' => [
        // Features::termsAndPrivacyPolicy(),
        Features::profilePhotos(),
        // Features::api(),
        // Features::teams(['invitations' => true]),
        Features::accountDeletion(),
    ],

    'middleware' => ['web'],

    'profile_photo_disk' => 'public',

];
