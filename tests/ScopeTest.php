<?php

namespace Connectum\Tests\Ronin;

use Connectum\Tests\Ronin\RoninTestCase as TestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ScopeTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testScopeUserRelationship()
    {
        $this->assertInstanceOf(BelongsToMany::class, $this->scope->users());
        $this->assertInstanceOf(EloquentCollection::class, $this->scope->users()->get());
    }

    public function testPermissionRoleRelationship()
    {
        $this->assertInstanceof(BelongsToMany::class, $this->scope->roles());
        $this->assertInstanceOf(EloquentCollection::class, $this->scope->roles()->get());
    }
}