<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'role_id' => '1',
            'nama_user' => 'Admin',
            'nomor_telepon' => '098765431',
            'email' => 'Admin@gmail.com',
            'password' => bcrypt('Admin123'),
        ]);
        // DB::table('users')->insert([
        //     'role_id' => '2',
        //     'nama_user' => 'praja',
        //     'nomor_telepon' => '082313945524',
        //     'email' => 'praja@gmail.com',
        //     'password' => bcrypt('praja123'),
        // ]);
        // DB::table('users')->insert([
        //     'role_id' => '3',
        //     'nama_user' => 'nizar',
        //     'nomor_telepon' => '08123456790',
        //     'email' => 'nizar@gmail.com',
        //     'password' => bcrypt('nizar123'),
        // ]);
    }
}
