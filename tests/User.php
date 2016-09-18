<?php

namespace Connectum\Tests\Ronin;


use Connectum\Ronin\Traits\Rolable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Rolable;
}
