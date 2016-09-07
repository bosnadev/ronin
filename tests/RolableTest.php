<?php

namespace Bosnadev\Tests\Ronin;

use Bosnadev\Ronin\Exceptions\RoleNotFoundException;
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

    public function testWhatHappensWhenWeTryToAssignNonExistingRoleSlug()
    {
        $this->expectException(RoleNotFoundException::class);
        $this->user->assignRole('role');

        $this->refreshUserInstance();

        $this->assertFalse($this->user->hasRole('role'));
    }

    public function testWhatHappensWhenWeTryToAssignNonExistingRoleId()
    {
        $this->expectException(RoleNotFoundException::class);
        $this->user->assignRole(15);

        $this->refreshUserInstance();

        $this->assertFalse($this->user->hasRole(15));
    }

    public function testAssigningRoleWithRoleId()
    {
        $this->user->assignRole(1);

        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasRole('artisan'));
    }

    public function testAssigningRoleWithRoleSlug()
    {
        $this->user->assignRole('editor');

        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasRole('editor'));
    }

    public function testAssigningMultipleRolesAtOnce()
    {
        $this->user->assignRole('editor', 'artisan');

        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasRole('editor'));
        $this->assertTrue($this->user->hasRole('artisan'));
        $this->assertFalse($this->user->hasRole('admin'));
    }

    public function testAssigningMultipleRolesAtOnceUsingIds()
    {
        $this->user->assignRole(1, 2);

        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasRole('editor'));
        $this->assertTrue($this->user->hasRole('artisan'));
        $this->assertFalse($this->user->hasRole('admin'));
    }

    public function testAssigningMultipleRoleInstancesAtOnce()
    {
        $this->user->assignRole($this->role, $this->role2);

        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasRole('editor'));
        $this->assertTrue($this->user->hasRole('artisan'));
        $this->assertFalse($this->user->hasRole('admin'));
    }

    public function testAssigningMultipleRolesAtOnceViaArray()
    {
        $this->user->assignRole(['editor', 'artisan']);

        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasRole('editor'));
        $this->assertTrue($this->user->hasRole('artisan'));
        $this->assertFalse($this->user->hasRole('admin'));
    }

    public function testAssigningMultipleRolesAtOnceUsingIdsViaArray()
    {
        $this->user->assignRole([1, 2]);

        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasRole('editor'));
        $this->assertTrue($this->user->hasRole('artisan'));
        $this->assertFalse($this->user->hasRole('admin'));
    }

    public function testCheckIfUserHasRoleAfterRevokingIt()
    {
        $this->user->assignRole('artisan');
        $this->refreshUserInstance();

        // make sure that user has a role
        $this->assertTrue($this->user->hasRole('artisan'));

        // Remove user from the role
        $this->user->revokeRole('artisan');
        $this->refreshUserInstance();

        // make sure we removed the role from the user
        $this->assertFalse($this->user->hasRole('artisan'));
    }
}