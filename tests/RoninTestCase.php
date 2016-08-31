<?php

namespace Bosnadev\Tests\Ronin;

use Bosnadev\Ronin\Providers\RoninServiceProvider;
use Mockery as m;
use Orchestra\Testbench\TestCase;

abstract class RoninTestCase extends TestCase
{
    protected $user;

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
     * WE can use in memory database or standard SQLite DB
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

    protected function refreshUserInstance()
    {
        $this->user = User::find($this->user->id);
    }
}