<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureAccessGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tm_feature_access_group')->create(
          [

                "i_id" => 1,
                "v_group_code" => "ADM",
                "v_id_feature_access" => 1,
                "v_input_by" => "seed",
                "dt_input_date" => Carbon::now(),
                "v_update_by" => null,
                "dt_update_date" => null
              ],
              [
                "i_id" => 2,
                "v_group_code" => "ADM",
                "v_id_feature_access" => 2,
                "v_input_by" => "seed",
                "dt_input_date" => Carbon::now(),
                "v_update_by" => null,
                "dt_update_date" => null
              ],

              [  "i_id" => 3,
                "v_group_code" => "ADM",
                "v_id_feature_access" => 3,
                "v_input_by" => "seed",
                "dt_input_date" => Carbon::now(),
                "v_update_by" => null,
                "dt_update_date" => null
          ]);
    }
}
