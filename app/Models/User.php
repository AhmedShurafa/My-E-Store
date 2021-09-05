<?php

namespace App\Models;

use App\Models\Admin\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function hasAbility($ability){


    }

    public function profile()
    {
        return $this->hasOne(User::class,'user_id','id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,
            'role_user',
            'user_id',
            'role_id',
            'id',
            'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'user_id','id')->withDefault();
    }

    public function product()
    {
        return $this->hasMany(Product::class,'user_id','id');
    }

}
