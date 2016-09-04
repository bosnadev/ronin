<?php

namespace Bosnadev\Tests\Ronin;

use Mockery as m;
use Illuminate\Support\Str;
use Bosnadev\Ronin\Models\Role;
use Bosnadev\Ronin\Models\Permission;
use Bosnadev\Tests\Ronin\RoninTestCase as TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoleTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testCreatingANewRole()
    {
        Role::create([
            'name' => 'Publisher',
            'slug'  => 'publisher'
        ]);

        $role = Role::find(3);

        $this->assertEquals('Publisher', $role->name);
        $this->assertEquals('publisher', $role->slug);
        $this->seeInDatabase('roles', ['slug' => 'publisher']);
        $this->dontSeeInDatabase('roles', ['slug' => 'publishers']);
    }

    public function testRoleUserRelationship()
    {
        $role = Role::first();

        $this->assertInstanceOf(BelongsToMany::class, $role->users());
    }

    public function testPermissionRelationship()
    {
        $role = Role::first();

        $this->assertInstanceOf(BelongsToMany::class, $role->permissions());
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
