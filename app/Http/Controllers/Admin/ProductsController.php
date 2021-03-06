<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use App\Scopes\ActiveStatusScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->authorizeResource(Product::class,'products');
    }

    public function index()
    {
//        $this->authorize('view-any',Product::class); // ot viewAny

        $products = Product::withoutGlobalScopes([ActiveStatusScope::class])
//            ->join('categories', 'categories.id', '=', 'products.category_id')
//            ->select([
//                'products.*',
//                'categories.name as category_name',
//            ])
            ->with('category.parent') //category.parent.parent
            ->paginate(15);


        return view('admin.products.index', [
            'products' => $products,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $this->authorize('create',Product::class);

        $categories = Category::pluck('name', 'id');

        return view('admin.products.create', [
            'categories' => $categories,
            'product' => new Product(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//       $r = $this->authorize('store',Product::class);


        /*$request->merge([
            'slug' => Str::slug($request->post('name')),
        ]);*/

        $image = [];
        if($request->hasfile('image')){
            foreach ($request->file('image') as $file){
                $filename = uniqid();
                $file->move(public_path().'/products/',$filename);
                $image[] = $filename;
            }
        }

        $product = Product::create( $request->all() );

        return redirect()->route('products.index')
            ->with('success', "Product ($product->name) created.");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $product = Product::withoutGlobalScope('active')->findOrFail($id);

//        $this->authorize('show',$product);

        return view('admin.products.show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::withoutGlobalScope('active')->findOrFail($id);

//        $this->authorize('edit',$product);

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::withTrashed()->pluck('name', 'id'),
        ]);
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
        $product = Product::withoutGlobalScope('active')->findOrFail($id);

//        $this->authorize('update',$product);


        $request->validate( Product::validateRules() );

        if ($request->hasFile('image')) {
            $file = $request->file('image'); // UplodedFile Object
            // $file->getClientOriginalName(); // Return file name
            // $file->getClientOriginalExtension();
            // $file->getClientMimeType(); // audio/mp3
            // $file->getType();
            // $file->getSize();

            // Filesystem - Disks
            // local: storage/app
            // public: storage/app/public
            // s3: Amazon Drive
            // custom: defined by us!
            $image_path = $file->store('/', [
                'disk' => 'uploads',
            ]);
            $request->merge([
                'image_path' => $image_path,
            ]);
        }

        $product->update( $request->all() );

        return redirect()->route('products.index')
            ->with('success', "Product ($product->name) updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::withoutGlobalScope('active')->findOrFail($id);

//        $this->authorize('destroy',$product);

        $product->delete();

        //Storage::disk('uploads')->delete($product->image_path);
        //unlink(public_path('uploads/' . $product->image_path));

        return redirect()->route('products.index')
            ->with('success', "Product ($product->name) deleted.");
    }

    public function trash()
    {
        $products = Product::withoutGlobalScope('active')->onlyTrashed()->paginate();

        return view('admin.products.trash', [
            'products' => $products,
        ]);
    }

    public function restore(Request $request, $id = null)
    {
        if ($id) {
            $product = Product::withoutGlobalScope('active')->onlyTrashed()->findOrFail($id);
            $product->restore();

            return redirect()->route('products.index')
                ->with('success', "Product ($product->name) restored.");
        }

        Product::withoutGlobalScope('active')->onlyTrashed()->restore();
        return redirect()->route('products.index')
                ->with('success', "All trashed products restored.");
    }

    public function forceDelete($id = null)
    {
        if ($id) {
            $product = Product::withoutGlobalScope('active')->onlyTrashed()->findOrFail($id);
            $product->forceDelete();

            return redirect()->route('products.index')
                ->with('success', "Product ($product->name) deleted forever.");
        }

        Product::withoutGlobalScope('active')->onlyTrashed()->forceDelete();
        return redirect()->route('products.index')
                ->with('success', "All trashed products deleted forever.");
    }
}
