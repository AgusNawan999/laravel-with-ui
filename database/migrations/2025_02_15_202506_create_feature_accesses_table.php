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
    Schema::create('tm_feature_access', function (Blueprint $table) {
      $table->id('v_id')->comment('id unik');
      $table->string('v_name', 255)->comment('nama feature access');
      $table->string('v_alias', 255)->comment('nama alias');
      $table->string('v_type', 50)->comment('tipe feature access [menu|crud|filter]');
      $table->unsignedBigInteger('v_parent_id')->nullable()->comment('id parent, link to tm_feature access.v_id');
      $table->string('tx_description', 2000)->nullable()->comment('keterangan feature access');
      $table->string('v_route', 2000)->nullable()->comment('route menu, hanya diisi untuk fitur menu');
      $table->string('v_icon', 100)->nullable()->comment('nama icon');
      $table->integer('si_order')->default(0)->comment('urutan fitur menu');
      $table->string('v_created_by', 10)->nullable()->comment('user input');
      $table->timestamp('dt_created_at')->nullable()->comment('waktu input');
      $table->string('v_updated_by', 10)->nullable()->comment('user update terkahir');
      $table->timestamp('dt_updated_at')->nullable()->comment('waktu update');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tm_feature_access');
  }
};
