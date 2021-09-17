<?php

namespace App\Models;

use App\Models\Admin\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    use HasApiTokens;
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

        $roles = Role::whereRaw('roles.id IN (SELECT role_id FROM role_user WHERE user_id = ?)', [
            $this->id,
        ])->get();
        // SELECT * FROM roles WHERE id IN (SELECT role_id FROM role_user WHERE user_id = ?)
        // SELECT * FROM roles INNER JOIN role_user ON roles.id = role_user.role_id WHERE role_user.user_id = ?

        foreach ($roles as $role) {
            if (in_array($ability, $role->abilities)) {
                return true;
            }
        }
        return false;
    }

    public function profile()
    {
        return $this->hasOne(Profile::class,'user_id','id');
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

    // Notification

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification = null)
    {
        // Return email address only...
        return $this->email;//email_address
    }

    public function routeNotificationForNexmo($notification = null)
    {
        // Return email address only...
        return $this->profile->phone;//if name colume database phone
    }

    public function routeNotificationForTwilio($notification = null)
    {
        // Return email address only...
        return $this->profile->phone;//if name colume database phone
    }

    public function routeNotificationForTweetSms()
    {
        return $this->mobile;
    }

    // to channge name channel
    public function receivesBroadcastNotificationsOn()
    {
        return "Notification.".$this->id;
    }

    // sanctum Laravel
    // Three Solution
    // public function createToken(string $name, array $abilities = ['*'] , $ip = null)
    // {
    //     $token = new PersonalAccessToken();

    //     $token->forceFill([
    //         'tokenable_type' => static::class,
    //         'tokenable_id' => $this->id,
    //         'name' => $name,
    //         'token' => hash('sha256', $plainTextToken = Str::random(40)),
    //         'abilities' => $abilities,
    //         'ip' => $ip,
    //     ])->save();

    //     return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
    // }
}
