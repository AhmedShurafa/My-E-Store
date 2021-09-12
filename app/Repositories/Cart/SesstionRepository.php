<?php

namespace App\Repositories\Cart;
use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Facades\Session;

class SesstionRepository implements CartRepository {

    protected $key = 'cart';

    public function all()
    {
        // To Get All Cart
        return Session::get($this->key);
    }

    public function add($item, $qty =1)
    {
        // to add item to cart
        Session::push($this->key, $item);
    }

    public function clear()
    {
        // to delete all Cart
        Session::forget($this->key);
    }

    public function delete($item)
    {
        // to delete a one item of Cart
        $all = Session::get($this->key);

        foreach ($all as $value){
            if($value == $item){
                dd($value);
            }
        }
    }
}
