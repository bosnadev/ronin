<?php

namespace Bosnadev\Tests\Ronin;


use Bosnadev\Ronin\Traits\Rolable;
use Bosnadev\Ronin\Traits\Scopable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Rolable, Scopable;
}
