<?php

namespace Bosnadev\Tests\Ronin;


use Illuminate\Database\Eloquent\Model;
use Bosnadev\Ronin\Traits\RolableTrait;

class User extends Model
{
    use RolableTrait;
}