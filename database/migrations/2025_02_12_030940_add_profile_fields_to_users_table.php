<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('password'); // รูปโปรไฟล์
            $table->text('bio')->nullable()->after('avatar'); // ข้อมูลเกี่ยวกับตัวเอง
            $table->string('phone')->nullable()->after('bio'); // เบอร์โทรศัพท์
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'bio', 'phone']);
        });
    }
};