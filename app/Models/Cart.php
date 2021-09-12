<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = ['_token'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected $with =[
        'product'
    ];

    protected static function booted(){
        /*
        Events: before:after
            creating, created, updating, updated, saving, saved
            deleting, deleted, restoring, restored
        */
        static::creating(function (Cart $cart){
            $cart->id = Str::uuid();
        });

    }
}
