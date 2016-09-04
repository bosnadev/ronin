<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    |
    | Please provide the user model used in Sentinel.
    |
    */
    'users' => [
        'model' => App\User::class,
    ],

    'roles' => [
        'model' => Bosnadev\Ronin\Models\Role::class,
    ],

    'scopes' => [
        'model' => Bosnadev\Ronin\Models\Scope::class
    ]
];
