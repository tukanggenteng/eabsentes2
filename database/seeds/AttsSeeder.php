<?php

use Illuminate\Database\Seeder;
use App\att;
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
        $faker=Faker::create('App\att');

        for ($i=0; $i < 60; $i++) {
          DB::table('atts')->insert([
              'pegawai_id'=>'2',
              'tanggal_att'=>'2017-11-21',
              'terlambat'=>'00:00:00',
              'akumulasi_sehari'=>'00:00:00',
              'jenisabsen_id'=>'2',
              'jadwalkerja_id'=>'1',
          ]);
        }


    }
}
