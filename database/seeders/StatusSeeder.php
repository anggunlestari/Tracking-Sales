<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status')->insert([
            'nama_status' => 'Prospek'
        ]);
        DB::table('status')->insert([
            'nama_status' => 'Demo/Presentasi'
        ]);
        DB::table('status')->insert([
            'nama_status' => 'Closing Paid'
        ]);
        DB::table('status')->insert([
            'nama_status' => 'Pending'
        ]);
        DB::table('status')->insert([
            'nama_status' => 'Maintenance'
        ]);
    }
}
