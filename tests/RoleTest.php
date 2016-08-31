<?php

namespace Bosnadev\Tests\Ronin;

use Bosnadev\Ronin\Models\Permission;
use Bosnadev\Ronin\Models\Role;
use Bosnadev\Tests\Ronin\RoninTestCase as RTS;
use Illuminate\Support\Str;
use Mockery as m;

class RoleTest extends RTS
{
    public function tearDown()
    {
        m::close();
    }

    public function testCreatingANewRole()
    {
        Role::create([
            'name' => 'Editor',
            'slug'  => 'editor'
        ]);

        $role = Role::find(2);

        $this->assertEquals('Editor', $role->name);
        $this->assertEquals('editor', $role->slug);
    }

    public function testPermissionRelationship()
    {
        $role = new Role();

        $this->addMockConnection($role);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class, $role->permissions());
    }

    public function testUserModelGetterAndSetter()
    {
        Role::setUserModel(User::class);

        $this->assertEquals(User::class, Role::getUserModel());
    }

    public function testPermissionModelGetterAndSetter()
    {
        Role::setPermissionModel(Permission::class);

        $this->assertEquals(Permission::class, Role::getPermissionModel());
    }

    public function testAddingNewRoleAndPermissions()
    {
        $role = $this->mockRole(Role::class);

        $permissions = [
            'can_view'      => true,
            'can_edit'      => true,
            'can_delete'    => false
        ];

        $role->permissions = $permissions;
        $this->assertEquals($permissions, $role->permissions);
    }

    protected function mockRole($role)
    {
        $roleMock = m::mock(Role::class)
            ->shouldReceive('setAttribute')
            ->with([
                'name'  => $role,
                'slug'  => Str::slug($role),
                'description'   => 'Default role'
            ]);

        return $roleMock;
    }

    protected function addMockConnection($model)
    {
        $model->setConnectionResolver($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));
        $resolver->shouldReceive('connection')->andReturn(m::mock('Illuminate\Database\Connection'));
        $model->getConnection()->shouldReceive('getQueryGrammar')->andReturn(m::mock('Illuminate\Database\Query\Grammars\Grammar'));
        $model->getConnection()->shouldReceive('getPostProcessor')->andReturn(m::mock('Illuminate\Database\Query\Processors\Processor'));
    }
}