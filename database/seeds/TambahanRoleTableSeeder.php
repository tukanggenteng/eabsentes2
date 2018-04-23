<?php

use Illuminate\Database\Seeder;

class TambahanRoleTableSeeder extends Seeder
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
                'id' => '7',
                'namaRole' => 'karu'
            ]
        );
    }
}
