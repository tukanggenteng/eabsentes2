<?php

use Illuminate\Database\Seeder;

class SourceapiconfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('sourceapiconfig')->insert(
      [
          'id' => '1',
          'nama_api' => '[Mantra Simpeg]',
          'path' => '/pegawai/apimantra/',
          'active' => '0',
      ]
      );
      DB::table('sourceapiconfig')->insert(
      [
          'id' => '2',
          'nama_api' => '[Mantra <- Simpeg]',
          'path' => '/pegawai/apimantrasimpeg/',
          'active' => '1',
      ]
      );
      DB::table('sourceapiconfig')->insert(
      [
          'id' => '3',
          'nama_api' => '[Simpeg]',
          'path' => '/pegawai/simpeg/',
          'active' => '0',
      ]
      );
    }
}
