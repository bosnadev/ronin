<?php

namespace Bosnadev\Tests\Ronin;

use Bosnadev\Ronin\Exceptions\NoRoleProvidedException;
use Bosnadev\Ronin\Models\Role;
use Bosnadev\Tests\Ronin\RoninTestCase as TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RolableTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();
    }

    public function testUserRoleRelationship()
    {
        $this->assertInstanceOf(BelongsToMany::class, $this->user->roles());
        $this->assertInstanceOf(Collection::class, $this->user->roles()->get());
    }

    public function testUserHasRole()
    {
        $this->user->assignRole(1);

        $this->refreshUserInstance();

        $role = Role::find(1);
        $this->assertTrue($this->user->hasRole('artisan'));
        $this->assertTrue($this->user->hasRole($role));
        $this->assertTrue($this->user->hasRole(1));
        $this->assertFalse($this->user->hasRole(3));
        $this->assertTrue($this->user->hasAnyRole(['artisan', 'artisans']));
        $this->assertTrue($this->user->hasAnyRole([$role, 'artisans']));
        $this->assertFalse($this->user->hasAnyRole(['artisans', 'editor']));
        $this->assertCount(1, $this->user->getRoles());
        $this->assertEquals('artisan', $this->user->roles->first()->getSlug());
    }

    public function testIfUserHaveRoleWithAGivenSlug()
    {
        $this->user->assignRole(1);

        $this->refreshUserInstance();

        $role = Role::find(1);
        $this->assertTrue($this->user->userRoleSlug($role->slug));
    }

    public function testAssigningRoleWhenNoRoleProvided()
    {
        $this->expectException(NoRoleProvidedException::class);
        $role = $this->user->assignRole();

        $this->refreshUserInstance();

        $this->assertFalse($role);
    }

    public function testAssigningRoleWithAnId()
    {
        $this->user->assignRole(1);

        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasRole('artisan'));
    }
}