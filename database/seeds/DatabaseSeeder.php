<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

        $user = new \App\User();
        $user->email = 'admin@pm.com';
        $user->name = 'Super';
        $user->password = \Illuminate\Support\Facades\Hash::make('admin123');
        $user->role_id = 1;
        $user->status = 'approved';
        $user->save();

        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            ['role' => 'admin'],
            ['role' => 'user']
        ]);
	}

}
