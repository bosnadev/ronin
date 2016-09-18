<?php

namespace Connectum\Tests\Ronin;

use Connectum\Ronin\Contracts\Scope;
use Connectum\Ronin\Exceptions\ScopeNotFound;
use Connectum\Ronin\Contracts\Role as RoleContract;

class ScopableTest extends RoninTestCase
{
    public function testUserHasRoleScope()
    {
        $this->role->addScope('create');
        $this->role->addScope('search');
        $this->user->assignRole(1);

        $this->assertTrue($this->user->inScope('search'));
        $this->seeInDatabase('role_scope', ['scope_id' => app(Scope::class)->where('slug', 'search')->first()->id]);
    }

    public function testUserDoesNotHaveRoleScope()
    {
        $this->assertFalse($this->user->inScope('edit'));
        $this->dontSeeInDatabase('role_scope', ['scope_id' => app(Scope::class)->where('slug', 'edit')->first()->id]);
    }

    public function testUserHasRoleScopeInMultipleRoles()
    {
        $this->role->addScope('create');
        $this->role2->addScope('search');
        $this->user->assignRole(1);
        $this->user->assignRole(2);

        $role3 = app(RoleContract::class)->create(['name' => 'Moderator', 'slug' => 'slug']);
        $role3->addScope('delete');

        $this->refreshRoleInstance();

        $this->assertTrue($this->user->inScope('search'));
        $this->assertTrue($this->user->inScope('create'));
    }

    public function testUserDoesNotHaveRoleScopeInAnyRole()
    {
        $this->assertFalse($this->user->inScope('delete'));
    }

    public function testGrantingScopeToRole()
    {
        $this->role->addScope('create');
        $this->role->addScope('search');

        $this->refreshRoleInstance();
        $this->refreshScopeInstance();

        $this->assertFalse($this->role->inScope(['delete']));
        $this->assertTrue($this->role->inScope('create'));
        $this->assertTrue($this->role->inScope('search'));
    }

    public function testWhenScopeNotFound()
    {
        $this->expectException(ScopeNotFound::class);
        $this->assertFalse($this->role->inScope('delete'));
        $this->assertFalse($this->role->inScope('insert'));
    }

    public function testIfUserHasDirectScope()
    {
        $this->user->addScope('edit');

        $this->refreshUserInstance();

        $this->assertTrue($this->user->inScope('edit'));
        $this->assertFalse($this->user->inScope('delete'));
    }

    public function testFindScopeById()
    {
        $scope = app(Scope::class)->findById(1);

        $this->seeInDatabase('scopes', ['id' => $scope->id]);
    }

    public function testFindScopeBySlug()
    {
        $scope = app(Scope::class)->findBySlug('edit');

        $this->seeInDatabase('scopes', ['slug' => $scope->slug]);
    }

    public function testFindScopeByName()
    {
        $scope = app(Scope::class)->findByName('Edit');

        $this->seeInDatabase('scopes', ['name' => $scope->name]);
    }
}