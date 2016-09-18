<?php

namespace Connectum\Tests\Ronin;

use Mockery as m;
use Connectum\Ronin\Models\Role;
use Connectum\Ronin\Models\Scope;
use Orchestra\Testbench\TestCase;
use Illuminate\Database\Schema\Blueprint;
use Connectum\Ronin\Providers\RoninServiceProvider;

abstract class RoninTestCase extends TestCase
{
    protected $user;
    protected $role;
    protected $role2;
    protected $scope;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__.'/../database/migrations'),
        ]);

        $this->createUserDatabaseSchema();

        // Seeders
        $this->seed(\RoninSeeder::class);

        // Set the User model for this Test Case
        config(['ronin.users.model' => User::class]);

        // Test data
        $this->user = User::first();
        $this->role = Role::first();
        $this->role2 = Role::find(2);
        $this->scope = Scope::first();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            RoninServiceProvider::class
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => $this->getDatabaseName(),
            'prefix'   => '',
        ]);
    }

    /**
     * We can use in memory database or standard SQLite DB
     *
     * @return mixed|string
     */
    protected function getDatabaseName()
    {
        if(env('RONIN_MEMORY_DB', true))
            return ':memory:';

        return $this->createDatabase(env('RONIN_TEST_DB', './database/db.sqlite'));
    }

    /**
     * Create test database
     *
     * @param $db
     * @return mixed
     */
    protected function createDatabase($db)
    {
        file_put_contents($db, null);

        return $db;
    }

    /**
     * Refresh test user
     */
    protected function refreshUserInstance()
    {
        $this->user = User::find($this->user->id);
    }

    protected function refreshRoleInstance()
    {
        $this->role = Role::first();
        $this->role2 = Role::where('slug', 'editor')->first();
    }

    protected function refreshScopeInstance()
    {
        $this->scope = Scope::first();
    }

    /**
     * Create Laravel's default users table
     */
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