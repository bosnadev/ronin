<?php

namespace Bosnadev\Tests\Ronin;

use Mockery as m;
use Illuminate\Support\Str;
use Bosnadev\Ronin\Models\Role;
use Bosnadev\Ronin\Models\Permission;
use Bosnadev\Ronin\Traits\RolableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Bosnadev\Tests\Ronin\RoninTestCase as TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoleTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = User::first();
    }

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

    public function testUserRoleRelationship()
    {
        $this->assertInstanceOf(BelongsToMany::class, $this->user->roles());
    }

    public function testUserHasRole()
    {
        $this->user->assignRole(1);

        $this->refreshUserInstance();

        $role = Role::find(1);
        $this->assertTrue($this->user->hasRole('artisan'));
        $this->assertTrue($this->user->hasRole($role));
        $this->assertFalse($this->user->hasRole(1));
        $this->assertTrue($this->user->hasAnyRole(['artisan', 'artisans']));
        $this->assertTrue($this->user->hasAnyRole([$role, 'artisans']));
        $this->assertFalse($this->user->hasAnyRole(['artisans', 'editor']));
        $this->assertCount(1, $this->user->getRoles());
    }

    public function testIfUserHaveRoleWithAGivenSlug()
    {
        $this->user->assignRole(1);

        $this->refreshUserInstance();

        $role = Role::find(1);
        $this->assertTrue($this->user->userRoleSlug($role->slug));
    }

    public function testAssigningRoleWhenNoRoleProvided()
    {
        $role = $this->user->assignRole();
        $this->refreshUserInstance();

        $this->assertFalse($role);
    }

    public function testPermissionRelationship()
    {
        $role = new Role();

        $this->addMockConnection($role);

        $this->assertInstanceOf(BelongsToMany::class, $role->permissions());
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

    protected function createUserDatabaseSchema()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
}

class User extends Model
{
    use RolableTrait;
}