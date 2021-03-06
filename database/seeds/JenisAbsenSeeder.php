<?php

use Illuminate\Database\Seeder;

class JenisAbsenSeeder extends Seeder
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
            'id' => '1',
            'jenis_absen' => 'Hadir'
        ]);
        DB::table('jenisabsens')->insert(
        [
            'id' => '2',
            'jenis_absen' => 'Tanpa Kabar'
        ]);
        DB::table('jenisabsens')->insert(
        [
            'id' => '3',
            'jenis_absen' => 'Ijin'
        ]);
        DB::table('jenisabsens')->insert(
        [
            'id' => '4',
            'jenis_absen' => 'Cuti'
        ]);
        DB::table('jenisabsens')->insert(
        [
            'id' => '5',
            'jenis_absen' => 'Sakit'
        ]);
        DB::table('jenisabsens')->insert(
        [
            'id' => '6',
            'jenis_absen' => 'Tugas Belajar'
        ]);
        DB::table('jenisabsens')->insert(
        [
            'id' => '7',
            'jenis_absen' => 'Tugas Luar'
        ]);
        DB::table('jenisabsens')->insert(
        [
            'id' => '8',
            'jenis_absen' => 'Ijin Kepentingan Lain'
        ]);
        DB::table('jenisabsens')->insert(
        [
            'id' => '9',
            'jenis_absen' => 'Cuti Bersama/ Libur Nasional'
        ]);
        DB::table('jenisabsens')->insert(
        [
            'id' => '10',
            'jenis_absen' => 'Ijin Terlambat'
        ]
        );
        DB::table('jenisabsens')->insert(
        [
            'id' => '11',
            'jenis_absen' => 'Ganti Shift'
        ]
        );
        DB::table('jenisabsens')->insert(
        [
            'id' => '12',
            'jenis_absen' => 'Ijin Pulang Cepat'
        ]
        );
    }
}
