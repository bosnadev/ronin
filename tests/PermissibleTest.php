<?php
/**
 * Created by PhpStorm.
 * User: mirzap
 * Date: 02/09/2016
 * Time: 15:36
 */

namespace Bosnadev\Tests\Ronin;


class PermissibleTest extends RoninTestCase
{
    public function testGivePermissionToRole()
    {
        $this->role->givePermission('create', 'search');

        $this->refreshRoleInstance();
        $this->refreshPermissionInstance();

        $this->assertTrue($this->role->can('create'));
        $this->assertTrue($this->role->can('search'));
        $this->assertFalse($this->role->can('delete'));
        $this->assertFalse($this->role->can('insert'));
    }
}