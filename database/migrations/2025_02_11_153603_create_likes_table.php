<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ผู้ที่ไลค์
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade'); // โพสต์ที่ถูกไลค์
            $table->timestamps();

            // สร้าง Unique Constraint เพื่อให้ไม่สามารถไลค์ซ้ำได้
            $table->unique(['user_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}