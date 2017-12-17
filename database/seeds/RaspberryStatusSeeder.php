<?php

use App\instansi;
use Illuminate\Database\Seeder;

class RaspberryStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tableinstansis=instansi::all();

        foreach ($tableinstansis as $key => $tableinstansi) {
            DB::table('raspberrystatus')->insert([
                'instansi_id'=>$tableinstansi->id,
                'status'=>'Offline',
                'jamstatus_raspberry'=>'',
                'tanggal_raspberry'=>''
            ]);
        }

        
    }
}
