<?php
return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],
    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class
        ]
    ],
    'drivers' => [
        'email' => [
            'provider' => 'users',
            'resolver' => null,
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],
];
