<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CartController extends Controller
{
    /**
     * @var \App\Repositories\Cart\CartRepository
     */
    protected $cart;

    public function __construct(CartRepository $cart)
    {
        $this->cart = $cart;

//      $cart = app(CartRepository::class);
//      $cart = app()->make(CartRepository::class);
//      $cart = App::make(CartRepository::class);

//      $this->cart->add(Product::find(1),2);// for Database Repository

//        $this->cart->delete([
//            'id'      => uniqid(),
//            'product' => 'T-shirt',
//            'price'   => '100$',
//        ]);// for cookies Repository
    }

    public function index()//CartRepository $cart
    {
        $carts = $this->cart->all();
        $total = $this->cart->total();

        return view('front.cart',compact('carts','total'));
    }

    public function store(Request $request)
    {
        $request->validate([
           'product_id' => 'required|exists:products,id',
           'quantity' => ['int','min:1',function($attr,$value,$fail){
                $id = request('product_id');
                $product = Product::find($id);
                if ($value > $product->quantity){
                    $fail(__('Quantity greater than quantity in stock.'));
                }
           }],
        ]);
        $cart = $this->cart->add($request->post('product_id') , $request->post('quantity',1));

        if($request->expectsJson())
        {
            return $this->cart->all();
        }

        return redirect()->back()->with('success',__('Item added to Cart.'));
    }

    public function clear()
    {
        $this->cart->clear();
        return redirect()->back()->with('success',__('All items have been deleted.'));
    }

    public function delete($item)
    {
        Cart::findOrFail($item)->delete();
        return redirect()->back()->with('success',__('item Deleted Successfully.'));
    }
}
