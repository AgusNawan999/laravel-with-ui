<?php

namespace Database\Seeders;

use App\Models\Setting\UserGroup;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserGroup::create([
                "i_id" => 1,
                "username" => "adm_3",
                "v_group_code" => "ADM",
                "v_input_by" => "seed",
                "v_deleted_by" => null,
                "dt_created_at" => Carbon::now(),
                "dt_deleted_date" => null
            ]);
    }
}
