<?php
/**
 * Created by PhpStorm.
 * User: mirzap
 * Date: 02/09/2016
 * Time: 15:36
 */

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

        $this->assertFalse($this->role->can(['delete']));
        $this->assertTrue($this->role->can('create'));
        $this->assertTrue($this->role->can('search'));
        $this->expectException(PermissionNotFound::class);
        $this->assertFalse($this->role->can('delete'));
        $this->assertFalse($this->role->can('insert'));
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