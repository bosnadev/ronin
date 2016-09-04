<?php

use Illuminate\Database\Seeder;

class RoninSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->rolesSeeder();
        $this->permissionsSeeder();
        $this->usersSeeder();
    }

    /**
     * Let's seed some roles
     */
    protected function rolesSeeder()
    {
        \DB::table('roles')->insert([
            [
                'name'          => 'Artisan',
                'slug'          => 'artisan',
                'description'   => 'Only for the Laravel Artisans'
            ],
            [
                'name'          => 'Editor',
                'slug'          => 'editor',
                'description'   => 'This is an Editor\'s role'
            ]
        ]);
    }

    protected function usersSeeder()
    {
        \DB::table('users')->insert([
            'name'      => 'Laravel Artisan',
            'email'     => 'laravel@artisan.email',
            'password'  => bcrypt('secret')
        ]);
    }

    protected function permissionsSeeder()
    {
        \DB::table('scopes')->insert([
            [
                'name' => 'Edit',
                'slug' => 'edit'
            ],
            [
                'name' => 'Create',
                'slug' => 'create'
            ],
            [
                'name' => 'Delete',
                'slug' => 'delete'
            ],
            [
                'name' => 'Search',
                'slug' => 'search'
            ]
        ]);
    }
}
