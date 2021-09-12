<?php

namespace App\Repositories\Cart;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class DatabaseRepository implements CartRepository {

    protected $key = 'cart';
    protected $items;

    public function __construct()
    {
        $this->items = collect([]);
    }

    public function all()
    {
        // To Get All Cart
        if($this->items->count() == 0){
            $items = Cart::where('cookie_id', $this->getCookieId())
            ->orWhere('user_id',Auth::id())
            ->get();
        }
        return $items;
    }

    public function add($item , $qty = 1)
    {
//        dd($item);
        // to add item to cart
        $cart = Cart::updateOrCreate([
                'cookie_id' => $this->getCookieId(),
                'product_id' => $item  instanceof Product ? $item->id : $item,//
            ], [
            'user_id' => Auth::id(),
            'quantity' => DB::raw('quantity + ' . $qty),
        ]);

        $this->items = collect([]);
        return $cart;
    }

    public function clear()
    {
        // to delete all Cart
        Cart::where('cookie_id', $this->getCookieId())
            ->orWhere('user_id',Auth::id())
            ->delete();
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

    public function getCookieId()
    {
        $id = Cookie::get('cart_cookie_id');
        if (!$id){
            $id = Str::uuid();
            Cookie::queue('cart_cookie_id',$id, 60 * 24 * 30);
        }
        return $id;
    }

    public function total()
    {
        $items = $this->all();

        return $items->sum(function($item){
            return $item->quantity * $item->product->price;
        });
    }

    public function quantity()
    {
        $items = $this->all();

        return $items->sum('quantity');
    }
}
