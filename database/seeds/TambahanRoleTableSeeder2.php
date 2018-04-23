<?php

use Illuminate\Database\Seeder;

class TambahanRoleTableSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->insert(
            [
                'id' => '8',
                'namaRole' => 'rs'
            ]
        );
    }
}
