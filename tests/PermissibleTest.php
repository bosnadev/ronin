<?php

namespace Bosnadev\Tests\Ronin;

use Bosnadev\Ronin\Contracts\Permission;
use Bosnadev\Ronin\Exceptions\PermissionNotFound;

class PermissibleTest extends RoninTestCase
{
    public function testGivePermissionToRole()
    {
        $this->role->givePermission('create', 'search');

        $this->refreshRoleInstance();
        $this->refreshPermissionInstance();

        $this->assertFalse($this->role->hasPermission(['delete']));
        $this->assertTrue($this->role->hasPermission('create'));
        $this->assertTrue($this->role->hasPermission('search'));
        $this->expectException(PermissionNotFound::class);
        $this->assertFalse($this->role->hasPermission('delete'));
        $this->assertFalse($this->role->hasPermission('insert'));
    }

    public function testIfUserHasDirectPermission()
    {
        $this->user->givePermission('edit');

        $this->refreshUserInstance();

        $this->assertTrue($this->user->hasPermission('edit'));
        $this->assertFalse($this->user->hasPermission('delete'));
    }




    public function testFindPermissionById()
    {
        $permission = app(Permission::class)->findById(1);

        $this->seeInDatabase('permissions', ['id' => $permission->id]);
    }

    public function testFindPermissionByName()
    {
        $permission = app(Permission::class)->findByName('Edit');

        $this->seeInDatabase('permissions', ['name' => $permission->name]);
    }
}