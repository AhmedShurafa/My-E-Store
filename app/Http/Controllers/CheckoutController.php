<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Models\Order;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Countries;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{

    /**
     * @var App\Repositories\Cart\CartRepository;
     */
    protected $cart;

    public function __construct(CartRepository $cart)
    {
        return $this->cart = $cart;
    }

    public function create()
    {   //dd($this->cart->all());
        return view('front.checkout',[
            'cart' => $this->cart,
            'user' => Auth::user() ?? null,
            'countries' => Countries::getNames(App::currentLocale()),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'billing_name' => ['required', 'string'],
            'billing_phone' => 'required',
            'billing_email' => 'required|email',
            'billing_address' => 'required',
            'billing_city' => 'required',
            'billing_country' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $request->merge([
                'total' => $this->cart->total(),
            ]);

            $order = Order::create($request->all());
            $items = [];
            foreach ($this->cart->all() as $item) {
                $items[] = [
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ];

                // $order->items()->create([ //anther way
                //     //'order_id' => $order->id,
                //     'product_id' => $item->product_id,
                //     'quantity' => $item->quantity,
                //     'price' => $item->product->price,
                // ]);

                //anther way
                /*$order->products()->attach($item->product_id, [
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);*/
            }
            DB::table('order_items')->insert($items);

            DB::commit();

            // delete cart
            // send invoice
            // send notification to admin

            event('order.created',$order);

            // event(new OrderCreated($order)); // by class event

            return redirect()->route('orders');// with success

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
