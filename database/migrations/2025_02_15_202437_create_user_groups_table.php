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
        Schema::create('tm_user_groups', function (Blueprint $table) {
            $table->id('i_id')->comment('Increment ID User Group');
            $table->string('username')->comment('username (tm_users)');
            $table->string('v_group_code')->comment('group code dari v_group_code (tm_group)');
            $table->string('v_input_by', 10)->nullable()->comment('User input');
            $table->string('v_deleted_by', 10)->nullable()->comment('User Hapus terakhir');
            $table->timestamp('dt_created_at')->nullable()->comment('Waktu input');
            $table->timestamp('dt_deleted_date')->nullable()->comment('Waktu hapus');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tm_user_groups');
    }
};

