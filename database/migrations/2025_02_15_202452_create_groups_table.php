<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('tm_group', function (Blueprint $table) {
      $table->string('v_group_code', 10)->unique()->primary()->comment('id group');
      $table->string('v_group_name', 50)->comment('nama grup');
      $table->tinyInteger('si_aktif')->default(1)->comment('status aktif group [1: aktif, 0: tidak aktif]');

      // log
      $table->string('v_input_by', 10)->nullable()->comment('user input');
      $table->timestamp('dt_input_date')->nullable()->comment('waktu input');
      $table->string('v_update_by', 10)->nullable()->comment('user update terkahir');
      $table->timestamp('dt_update_date')->nullable()->comment('waktu update');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tm_group');
  }
};
