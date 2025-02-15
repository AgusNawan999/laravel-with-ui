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
    Schema::create('th_users', function (Blueprint $table) {
      $table->id('i_id');
      $table->string('username', 10)->comment('Username/Userid Pengguna');
      $table->string('v_userip')->nullable()->comment('IP Address Pengguna');
      $table->string('v_useragent', 2000)->nullable()->comment('User Agent Pengguna');
      $table->timestamp('dt_last_loggedin')->comment('Waktu Terakhir Login');
      $table->string('v_created_by', 10)->nullable()->comment('User input');
      $table->timestamp('dt_created_at')->nullable()->comment('Waktu input');
      $table->string('v_updated_by', 10)->nullable()->comment('User update terkahir');
      $table->timestamp('dt_updated_at')->nullable()->comment('Waktu update');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('th_users');
  }
};
