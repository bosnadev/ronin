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
        $this->permissionSeeder();
        $this->usersSeeder();
    }

    /**
     * Let's seed some roles
     */
    protected function rolesSeeder()
    {
        \DB::table('roles')->insert([
            'name'  => 'Artisan',
            'slug'  => 'artisan',
            'description'   => 'Only for the Laravel Artisans'
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

    protected function permissionSeeder()
    {
        \DB::table('permissions')->insert([
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
