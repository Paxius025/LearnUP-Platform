<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('favorite_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ผู้ที่บันทึก
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade'); // โพสต์ที่บันทึก
            $table->timestamps();

            // สร้าง Unique Constraint เพื่อป้องกันการบันทึกซ้ำ
            $table->unique(['user_id', 'post_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorite_posts');
    }
};