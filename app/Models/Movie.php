<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'studio_id', 'title', 'synopsis', 'release_year', 'duration_minutes', 'poster_image', 'status'
    ];

    public function studio() {
        return $this->belongsTo(Studio::class);
    }

    public function genres() {
        // บังคับให้ใช้ตาราง movie_genre
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    public function actors() {
        // บังคับให้ใช้ตาราง movie_actor
        return $this->belongsToMany(Actor::class, 'movie_actor')->withPivot('character_name');
    }

    public function directors() {
        // บังคับให้ใช้ตาราง movie_director
        return $this->belongsToMany(Director::class, 'movie_director');
    }

    public function reviews() {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }
}