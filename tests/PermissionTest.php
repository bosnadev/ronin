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
        $this->assertInstanceOf(BelongsToMany::class, $this->permissions->users());
        $this->assertInstanceOf(EloquentCollection::class, $this->permissions->users()->get());
    }

    public function testPermissionRoleRelationship()
    {
        $this->assertInstanceof(BelongsToMany::class, $this->permissions->roles());
        $this->assertInstanceOf(EloquentCollection::class, $this->permissions->roles()->get());
    }
}