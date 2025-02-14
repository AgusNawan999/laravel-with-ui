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
        Schema::create('tm_users', function (Blueprint $table) {
          $table->id('i_id')->comment('Increment ID pengguna');
          $table->string('v_userid', 10)->comment('Username/userid pengguna');
          $table->string('v_username', 255)->comment('Nama lengkap pengguna');
          $table->string('v_userpass')->comment('Password pengguna');
          $table->timestamp('dt_last_change_pass')->nullable()->comment('Waktu input terakhir ganti password');
          $table->tinyInteger('si_user_enable')->default(1)->comment('Flagging penguna aktif. [0: tidak aktif, 1: aktif]');
          $table->string('v_created_by', 10)->nullable()->comment('User input');
          $table->timestamp('dt_created_at')->nullable()->comment('Waktu input');
          $table->string('v_updated_by', 10)->nullable()->comment('User update terkahir');
          $table->timestamp('dt_updated_at')->nullable()->comment('Waktu update');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tm_users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
