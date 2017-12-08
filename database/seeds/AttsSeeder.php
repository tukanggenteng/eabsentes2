<?php

use Illuminate\Database\Seeder;
use App\atts_tran;
use Faker\Factory as Faker;
class AttsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker=Faker::create('App\atts_tran');

        for ($i=0; $i < 60; $i++) {
          DB::table('atts_trans')->insert([
              'pegawai_id'=>'2',
              'tanggal'=>'2017-11-21',
              'jam'=>'10:00:00',
              'lokasi_alat'=>'19',
              'status_kedatangan'=>'0',
          ]);
        }


    }
}
