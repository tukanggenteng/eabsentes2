<?php

use Illuminate\Database\Seeder;

class InstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('instansis')->insert([
        'id'=>'1',
        'kode'=>'1',
        'namaInstansi'=>'Admin'
      ]);
    }
}
