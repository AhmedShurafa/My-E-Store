<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::active()->paginate();
        return view('front.product.index',compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug',$slug)->firstOrFail();
        return view('front.product.show',compact('product'));
    }
}
