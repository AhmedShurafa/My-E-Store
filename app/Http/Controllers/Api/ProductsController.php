<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ReturnData;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Throwable;

class ProductsController extends Controller
{
    use ReturnData;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products =  Product::paginate();
        return $this->returnData("data",$products,__("This is All Products"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|min:3',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
        ]);

        try{
            $product = Product::create($request->all());
            return $this->returnSuccessMessage(200,__("The Product Careted"));
        }catch(Throwable $e){
            // return report($e);
            return $this->returnError(404,$e);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if(!$product){
            return $this->returnError(404,__("This Product Not Exists"));
        }
        return $this->returnData("data",$product,__("This Product is Exists"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes',
            'user_id' => 'sometimes|exists:users,id',
            'category_id' => 'sometimes|exists:categories,id',
            'description' => 'nullable|min:3',
            'price' => 'sometimes|numeric|min:0',
            'quantity' => 'sometimes|numeric|min:0',
        ]);

        try{
            $product= Product::findOrFail($id);
            $product->update($request->all());
            return $this->returnSuccessMessage(200,__("The Product Updated"));
        }catch(Throwable $e){
            // return report($e);
            return $this->returnError(404,$e);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $product= Product::find($id);

            $product->delete();

            return $this->returnSuccessMessage(200,__("This Product is Deleted"));
        }catch(Throwable $e){
            // return report($e);
            return $this->returnError(500,__("This is some probleme"));
        }
    }
}
