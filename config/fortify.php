<?php

use Laravel\Fortify\Features;

return [

    'guard' => 'web',

    'middleware' => ['web'],

    'auth_middleware' => 'auth',

    'passwords' => 'users',

    'username' => 'email',

    'email' => 'email',

    'lowercase_usernames' => true,

    'home' => '/admin',

    'prefix' => '',

    'domain' => null,

    'views' => true,

    'limiters' => [
        'login'              => 'login',
        'two-factor'         => 'two-factor',
    ],

    'login_after_register' => false, // Prevent auto-login after "registration" (not used publicly)

    'features' => [
        // Features::registration(),        // ← DISABLED: No public registration
        Features::resetPasswords(),
        // Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        // Features::twoFactorAuthentication([
        //     'confirm' => true,
        //     'confirmPassword' => true,
        // ]),
    ],

];
