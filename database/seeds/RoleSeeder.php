<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
	DB::table('roles')->insert([
		'id'=>'1',
		'namaRole'=>'user'
	]);
	DB::table('roles')->insert([
		'id'=>'2',
		'namaRole'=>'admin'
	]);
	DB::table('roles')->insert([
		'id'=>'3',
		'namaRole'=>'sekda'
	]);
	DB::table('roles')->insert([
		'id'=>'4',
		'namaRole'=>'kadis'
	]);
  DB::table('roles')->insert([
		'id'=>'5',
		'namaRole'=>'pegawai'
	]);
    }
}
