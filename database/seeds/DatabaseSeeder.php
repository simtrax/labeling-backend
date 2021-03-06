<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);

        \File::cleanDirectory(storage_path() . '/app/projects/');
        \File::cleanDirectory(storage_path() . '/app/tmp/');
    }
}
