<?php

namespace Bosnadev\Tests\Ronin;

use Mockery as m;
use PHPUnit_Framework_TestCase as TestCase;

class RoninTestCase extends TestCase
{
    public function tearDown()
    {
        m::close();
    }
}