<?php

namespace Bosnadev\Tests\Ronin;

use Bosnadev\Tests\Ronin\RoninTestCase as TestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PermissionTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testPermissionUserRelationship()
    {
        $this->assertInstanceOf(BelongsToMany::class, $this->permission->users());
        $this->assertInstanceOf(EloquentCollection::class, $this->permission->users()->get());
    }

    public function testPermissionRoleRelationship()
    {
        $this->assertInstanceof(BelongsToMany::class, $this->permission->roles());
        $this->assertInstanceOf(EloquentCollection::class, $this->permission->roles()->get());
    }
}