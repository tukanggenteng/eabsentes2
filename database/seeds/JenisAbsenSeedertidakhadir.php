<?php

use Illuminate\Database\Seeder;

class JenisAbsenSeedertidakhadir extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
            DB::table('jenisabsens')->insert(
            [
                'id' => '13',
                'jenis_absen' => 'Tidak Hadir'
            ]
            );
    }
}
