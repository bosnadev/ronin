<?php

namespace Bosnadev\Tests\Ronin;


use Bosnadev\Ronin\Traits\Rolable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Rolable;
}
