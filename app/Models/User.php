<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['role_id', 'name', 'email', 'password'];
    protected $hidden = ['password'];

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function watchlists() {
        return $this->belongsToMany(Movie::class, 'watchlists')->withTimestamps();
    }
}