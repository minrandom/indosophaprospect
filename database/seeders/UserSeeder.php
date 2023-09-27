<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'      => 'Jojo',
                'email'     => 'admin@gmail.com',
                'password'  => Hash::make('password'),
                'role'      => 'admin',
            ],
            [
                'name'      => 'Yolanda',
                'email'     => 'test@gmail.com',
                'password'  => Hash::make('password'),
                'role'      => 'admin',
            ],
            [
                'name'      => 'usertest',
                'email'     => 'chika@gmail.com',
                'password'  => Hash::make('password'),
                'role'      => 'user',
            ],
            [
                'name'      => 'Kotone',
                'email'     => 'kotone@gmail.com',
                'password'  => Hash::make('password'),
                'role'      => 'user',
            ],
            [
                'name'      => 'fstest',
                'email'     => 'fstest@gmail.com',
                'password'  => Hash::make('password'),
                'role'      => 'fs',
            ],
            [
                'name'      => 'nsmtest',
                'email'     => 'nsmtest@gmail.com',
                'password'  => Hash::make('password'),
                'role'      => 'nsm',
            ],
            [
                'name'      => 'butest',
                'email'     => 'butest@gmail.com',
                'password'  => Hash::make('password'),
                'role'      => 'fs',
            ],
            [
                'name'      => 'dbatest',
                'email'     => 'dbatest@gmail.com',
                'password'  => Hash::make('password'),
                'role'      => 'dba',
            ],
        ]);
    }
}
