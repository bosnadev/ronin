<?php

return [
    'users' => [
        'model' => App\User::class,
    ],

    'roles' => [
        'model' => Connectum\Ronin\Models\Role::class,
    ],

    'scopes' => [
        'model' => Connectum\Ronin\Models\Scope::class
    ]
];
