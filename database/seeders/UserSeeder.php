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
        'username' => 'adm_3',
        'v_full_name' => 'Admin 3',
        'v_email' => 'admin@mail.com',
        'password' => Hash::make('x'),
        'dt_created_at' => Carbon::now()
      ]);
    }
}
