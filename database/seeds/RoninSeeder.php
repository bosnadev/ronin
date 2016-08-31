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
        $this->roleSeeder();
    }

    /**
     * Let's seed some roles
     */
    protected function roleSeeder()
    {
        \DB::table('roles')->insert([
            'name'  => 'Artisan',
            'slug'  => 'artisan',
            'description'   => 'Only for the Laravel Artisans'
        ]);
    }
}
