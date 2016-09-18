<?php

namespace Connectum\Tests\Ronin;

use Mockery as m;
use Illuminate\Support\Str;
use Connectum\Ronin\Models\Role;
use Connectum\Ronin\Models\Scope;
use Connectum\Tests\Ronin\RoninTestCase as TestCase;
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

    public function testScopeRelationship()
    {
        $role = Role::first();

        $this->assertInstanceOf(BelongsToMany::class, $role->scopes());
    }

    public function testAddingNewRoleAndScopes()
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
