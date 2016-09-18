<?php

namespace Connectum\Ronin;

use Illuminate\Contracts\Auth\Guard;

/**
 * Class Ronin
 * @package Connectum\Ronin
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
}
