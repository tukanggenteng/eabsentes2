<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
        'name'=>'dodo',
        'email'=>'dodo@gmail.com',
        'username'=>'dodo',
        'password'=>bcrypt('anangbesanamat'),
        'instansi_id'=>'1',
        'role_id'=>'2',
      ]);
    }
}
