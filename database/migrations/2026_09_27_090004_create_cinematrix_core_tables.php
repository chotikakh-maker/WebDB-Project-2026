<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // 1. roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Super Admin, Moderator, User
            $table->timestamps();
        });

        // 2. users (เชื่อมกับ roles)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        // 3. user_profiles (1-to-1)
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        // 4. studios (1-to-M)
        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country');
            $table->timestamps();
        });

        // 5. movies
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('synopsis')->nullable();
            $table->integer('release_year');
            $table->integer('duration_minutes');
            $table->string('poster_image')->nullable();
            $table->enum('status', ['Released', 'Upcoming'])->default('Released');
            $table->timestamps();
        });

        // 6. genres, 7. actors, 8. directors
        Schema::create('genres', function (Blueprint $table) { $table->id(); $table->string('name'); $table->timestamps(); });
        Schema::create('actors', function (Blueprint $table) { $table->id(); $table->string('name'); $table->date('birthdate')->nullable(); $table->timestamps(); });
        Schema::create('directors', function (Blueprint $table) { $table->id(); $table->string('name'); $table->timestamps(); });

        // 9. movie_genre (M-to-M)
        Schema::create('movie_genre', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->primary(['movie_id', 'genre_id']);
        });

        // 10. movie_actor (M-to-M แบบมี Pivot Data ซับซ้อน)
        Schema::create('movie_actor', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actor_id')->constrained()->cascadeOnDelete();
            $table->string('character_name')->nullable(); // ฟิลด์พิเศษใน Pivot
            $table->primary(['movie_id', 'actor_id']);
        });

        // 11. movie_director (M-to-M)
        Schema::create('movie_director', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('director_id')->constrained()->cascadeOnDelete();
            $table->primary(['movie_id', 'director_id']);
        });

        // 12. reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->decimal('rating', 3, 1);
            $table->text('comment');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        // 13. review_likes (ผู้ใช้กดไลก์รีวิว)
        Schema::create('review_likes', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('review_id')->constrained()->cascadeOnDelete();
            $table->primary(['user_id', 'review_id']);
        });

        // 14. watchlists
        Schema::create('watchlists', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->primary(['user_id', 'movie_id']);
            $table->timestamps();
        });

        // 15. activity_logs (ระบบ Audit Log)
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action');
            $table->text('details');
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('watchlists');
        Schema::dropIfExists('review_likes');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('movie_director');
        Schema::dropIfExists('movie_actor');
        Schema::dropIfExists('movie_genre');
        Schema::dropIfExists('directors');
        Schema::dropIfExists('actors');
        Schema::dropIfExists('genres');
        Schema::dropIfExists('movies');
        Schema::dropIfExists('studios');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};