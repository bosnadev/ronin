<?php

namespace Connectum\Tests\Ronin;

use Connectum\Ronin\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Connectum\Tests\Ronin\RoninTestCase as TestCase;
use Connectum\Ronin\Exceptions\RoleNotFoundException;
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
        $role = Role::find(1);

        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasRole($role));
        $this->assertTrue($this->user->hasRole(1));
        $this->assertTrue($this->user->hasRole('artisan'));
        $this->assertEquals('artisan', $this->user->roles->first()->getSlug());
    }

    public function testUserHasAnyOfTheGivenRoles()
    {
        $this->user->assignRole(1);
        $role = Role::find(1);

        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasAnyRole(['artisan', 'artisans']));
        $this->assertTrue($this->user->hasAnyRole([$role, 'artisans']));
    }

    public function testUserBelongsToOneRole()
    {
        $this->user->assignRole(1);

        $this->refreshUserInstance();

        $this->assertCount(1, $this->user->getRoles());
    }

    public function testUserDoesNotHaveARole()
    {
        $role = Role::find(1);

        $this->assertFalse($this->user->hasRole($role));
        $this->assertFalse($this->user->hasRole(1));
        $this->assertFalse($this->user->hasRole('artisan'));
    }

    public function testUserDosNotHaveAnyOfTheGiveRoles()
    {
        $role1 = Role::find(1);
        $role2 = Role::find(2);

        $this->assertFalse($this->user->hasAnyRole([$role1, $role2]));
        $this->assertFalse($this->user->hasAnyRole([1, 2]));
        $this->assertFalse($this->user->hasAnyRole(['artisans', 'editor']));
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

    public function testSyncUserRoles()
    {
        $this->user->assignRole($this->role);
        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasRole($this->role));

        //$this->user->syncRoles($this->role, $this->role2);
        $this->user->syncRoles('artisan', 2);

        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasRole('artisan'));
        $this->assertTrue($this->user->hasRole('editor'));
    }
}