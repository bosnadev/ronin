<?php

namespace Bosnadev\Ronin;

use Illuminate\Contracts\Auth\Guard;

/**
 * Class Ronin
 * @package Bosnadev\Ronin
 */
class Ronin
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Ronin constructor.
     * @param $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function getRoles()
    {
        return $this->auth->user();
        return collect(['admin', 'artisan', 'ronin']);
    }
}
