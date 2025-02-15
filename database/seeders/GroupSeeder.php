<?php

namespace Database\Seeders;

use App\Models\Setting\Group;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::create([
                "v_group_code" => "ADM",
                "v_group_name" => "Admin Sistem",
                "si_aktif" => 1,
                "v_input_by" => "seed",
                "dt_input_date" => Carbon::now(),
                "v_update_by" => null,
                "dt_update_date" => null
        ]);
    }
}
