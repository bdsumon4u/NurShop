<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'phone' => '017878745',
            'email' => 'admin@devshawon.com',
            'password' => bcrypt('shawon'),
            'role_id' => '1'
        ]);
        DB::table('users')->insert([
            'name' => 'Manager',
            'phone' => '017878745',
            'email' => 'manager@devshawon.com',
            'password' => bcrypt('shawon'),
            'role_id' => '2'
        ]);

        DB::table('users')->insert([
            'name' => 'User',
            'phone' => '017878745',
            'email' => 'user@devshawon.com',
            'password' => bcrypt('shawon'),
            'role_id' => '3'
        ]);
    }
}
