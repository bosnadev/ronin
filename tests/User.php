<?php

namespace Bosnadev\Tests\Ronin;


use Bosnadev\Ronin\Traits\Rolable;
use Bosnadev\Ronin\Traits\Permissible;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Rolable, Permissible;
}
