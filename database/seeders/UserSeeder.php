<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('tm_users')->insert([
        'v_userid' => 'adm_2',
        'v_username' => 'Admin 2',
        'v_userpass' => Hash::make('password'),
        'dt_created_at' => Carbon::now()
      ]);
    }
}
