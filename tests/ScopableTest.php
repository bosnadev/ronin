<?php

namespace Bosnadev\Tests\Ronin;

use Bosnadev\Ronin\Contracts\Scope;
use Bosnadev\Ronin\Exceptions\ScopeNotFound;

class ScopableTest extends RoninTestCase
{
    public function testIfUserHasRoleScope()
    {
        $this->role->addScope('create', 'search');
        $this->user->assignRole(1);

        $this->refreshRoleInstance();
        $this->refreshUserInstance();
        $this->refreshScopeInstance();

        $this->assertTrue($this->user->inScope('search'));
        $this->assertFalse($this->user->inScope('edit'));
        $this->seeInDatabase('role_scope', ['scope_id' => app(Scope::class)->where('slug', 'search')->first()->id]);
        $this->dontSeeInDatabase('role_scope', ['scope_id' => app(Scope::class)->where('slug', 'edit')->first()->id]);

        // Assign another Role to the user
        $this->user->assignRole(2);
        $this->role2->addScope('edit');

        $this->refreshUserInstance();
        $this->refreshRoleInstance();

        $this->assertTrue($this->user->inScope('edit'));
    }

    public function testGivePermissionToRole()
    {
        $this->role->addScope('create', 'search');

        $this->refreshRoleInstance();
        $this->refreshScopeInstance();

        $this->assertFalse($this->role->inScope(['delete']));
        $this->assertTrue($this->role->inScope('create'));
        $this->assertTrue($this->role->inScope('search'));
        $this->expectException(ScopeNotFound::class);
        $this->assertFalse($this->role->inScope('delete'));
        $this->assertFalse($this->role->inScope('insert'));
    }

    /*public function testIfUserHasDirectPermission()
    {
        $this->user->addScope('edit');

        $this->refreshUserInstance();

        $this->assertTrue($this->user->inScope('edit'));
        $this->assertFalse($this->user->inScope('delete'));
    }*/

    public function testFindPermissionById()
    {
        $scope = app(Scope::class)->findById(1);

        $this->seeInDatabase('scopes', ['id' => $scope->id]);
    }

    public function testFindScopeByName()
    {
        $scope = app(Scope::class)->findByName('Edit');

        $this->seeInDatabase('scopes', ['name' => $scope->name]);
    }
}