<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(JenisAbsenSeeder::class);
        $this->call(InstansiSeeder::class);
	    $this->call(RoleSeeder::class);
        $this->call(UsersTableSeeder::class);
	      // $this->call(AttsSeeder::class);

    }
}
