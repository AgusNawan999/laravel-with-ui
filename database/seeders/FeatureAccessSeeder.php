<?php

namespace Database\Seeders;

use App\Models\Setting\FeatureAccess;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FeatureAccess::craete(
          [
              "v_id" => 1,
              "v_name" => "Akses Halaman Admin",
              "v_alias" => "access-admin-page",
              "v_type" => "Filter",
              "v_parent_id" => null,
              "tx_description" => "Fitur Untuk Dapat Akses Halaman Admin",
              "v_route" => null,
              "v_icon" => null,
              "si_order" => 999,
              "v_created_by" => "seeder",
              "dt_created_at" => Carbon::now(),
              "v_updated_by" => null,
              "dt_updated_at" => null
          ],
            [
              "v_id" => 2,
              "v_name" => "Managemen Pengguna",
              "v_alias" => "settings",
              "v_type" => "menu",
              "v_parent_id" => null,
              "tx_description" => "Fitur Untuk Managemen",
              "v_route" => "",
              "v_icon" => null,
              "si_order" => 999,
              "v_created_by" => "seeder",
              "dt_created_at" => Carbon::now(),
              "v_updated_by" => null,
              "dt_updated_at" => null
            ],
             [ "v_id" => 3,
              "v_name" => "Managemen Fitur Akses",
              "v_alias" => "manage-feature",
              "v_type" => "menu",
              "v_parent_id" => 2,
              "tx_description" => "Modul untuk mengatur akses",
              "v_route" => "settings.feature",
              "v_icon" => null,
              "si_order" => 1,
              "v_created_by" => "seed",
              "dt_created_at" => Carbon::now(),
              "v_updated_by" => null,
              "dt_updated_at" => null
          ]);
    }
}
