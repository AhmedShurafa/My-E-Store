<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class,'user_id');
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            User::class,
            'county_id',
            'user_id',
            'id');
    }
}
