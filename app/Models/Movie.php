<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model {
    protected $fillable = ['studio_id', 'title', 'synopsis', 'release_year', 'duration_minutes', 'poster_image', 'status'];

    public function studio() { return $this->belongsTo(Studio::class); }
    public function genres() { return $this->belongsToMany(Genre::class); }
    // ดึงฟิลด์พิเศษ character_name จาก Pivot Table
    public function actors() { return $this->belongsToMany(Actor::class)->withPivot('character_name'); }
    public function directors() { return $this->belongsToMany(Director::class); }
    public function reviews() { return $this->hasMany(Review::class)->where('is_approved', true); }
}