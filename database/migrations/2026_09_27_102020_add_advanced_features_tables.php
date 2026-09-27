<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // เพิ่มสถานะอนุมัติให้หนัง (User เพิ่มมาจะยังเป็น false)
        Schema::table('movies', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('status');
        });

        // เพิ่มแฟล็กซ่อนสปอยล์ให้รีวิว
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_spoiler')->default(false)->after('comment');
        });

        // สร้างตารางเก็บการรีพอร์ตคอมเมนต์
        Schema::create('reported_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // คนที่กดรีพอร์ต
            $table->string('reason'); // เหตุผลที่รีพอร์ต
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('reported_reviews');
        Schema::table('reviews', function (Blueprint $table) { $table->dropColumn('is_spoiler'); });
        Schema::table('movies', function (Blueprint $table) { $table->dropColumn('is_approved'); });
    }
};