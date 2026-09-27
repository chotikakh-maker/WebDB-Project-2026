<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    protected $fillable = ['role_id', 'name', 'email', 'password'];
    protected $hidden = ['password'];

    public function role() { return $this->belongsTo(Role::class); }
    public function profile() { return $this->hasOne(UserProfile::class); }
    public function watchlists() { return $this->belongsToMany(Movie::class, 'watchlists')->withTimestamps(); }
    
    // Helper function สำหรับเช็คสิทธิ์
    public function isAdmin() { return $this->role->name === 'Super Admin'; }
}