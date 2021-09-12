<?php

namespace App\Repositories\Cart;
use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class CookieRepository implements CartRepository {

    protected $name = 'cart';

    public function all()
    {
        // To Get All Cart
        $all = Cookie::get($this->name);
        if ($all) {
            return unserialize($all);
        }
        return [];
    }

    public function add($item, $qty = 1)
    {
        // to add item to cart
        $items = $this->all();
        $items[] = $item;

        Cookie::queue($this->name, serialize($items) , 60 * 24 * 30);
    }

    public function clear()
    {
        // to delete all Cart
        Cookie::queue($this->name ,'', -60);
    }

    public function delete($item)
    {
        // to delete a one item of Cart
        $all = $this->all();

        foreach ($all as $index => $value){

//            $key = array_search($item['id'] , $value['id']);

            if($item['id'] == $value['id'])
            {
                unset($all[$index]);
            }
        }

        // Reset the index
        $cookie_items = array_values($all);

        // Set the cookie
        setcookie($this->name , serialize($all));
    }

    public function getCookieId()
    {
        $id = Cookie::get('get_cookie_id');
        if ($id)
        {
            $id = Str::uuid();
            Cookie::queue('cart_cookie_id',$id, 60 * 24 * 30);
        }
        return $id;
    }
}
