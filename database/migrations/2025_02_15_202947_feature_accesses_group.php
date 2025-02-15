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
    Schema::create('tm_feature_access_group', function (Blueprint $table) {
      $table->id('i_id')->comment('id, autoincrement');
      $table->string('v_group_code', 10)->comment('id group');
      $table->unsignedBigInteger('v_id_feature_access')->comment('id group');

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
    Schema::dropIfExists('tm_feature_access_group');
  }
};
