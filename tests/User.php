<?php

namespace Bosnadev\Tests\Ronin;


use Illuminate\Database\Eloquent\Model;
use Bosnadev\Ronin\Traits\Rolable;

class User extends Model
{
    use Rolable;
}
