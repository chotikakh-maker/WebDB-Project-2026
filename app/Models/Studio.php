<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $fillable = ['name', 'country'];

    public function movies() {
        return $this->hasMany(Movie::class);
    }
}