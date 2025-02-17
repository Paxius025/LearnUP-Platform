<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('is_read');
            
            // Add is_user_read and is_admin_read columns 
            $table->boolean('is_user_read')->default(false);
            $table->boolean('is_admin_read')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // ลบคอลัมน์ที่เพิ่มเข้าไป
            $table->dropColumn('is_user_read');
            $table->dropColumn('is_admin_read');
            
            // เพิ่มคอลัมน์ is_read กลับมา
            $table->boolean('is_read')->default(false);
        });
    }
};