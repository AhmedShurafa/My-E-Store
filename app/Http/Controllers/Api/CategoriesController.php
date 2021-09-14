<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CateogryResource;
use App\Models\Category;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response as FacadesResponse;

// use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::when($request->query(),function($query,$value){
            $query->where('name',$value);
        })->paginate(1);
        return CateogryResource::collection($categories);
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
            'name'      => 'required',
            'parent_id' => 'nullable|exists:categories,parent_id',
        ]);


        $category = Category::create($request->all());
        $category->refresh();
        // return response()->join($category,201);

        // return Response::json($category, 201,[
        //     'x-appliction-name' =>config('app.naem'),
        // ]);

        return new JsonResource($category,201,[
            'x-appliction-name' =>config('app.naem'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // when need some colume
        // return Category::select([
        //     'id','name','slug','description','status'
        // ])->findOrFail($id);

        // when use Relationship
        //return Category::with('children')->findOrFail($id);

        // when need channge colume name

        /*$category = Category::with('children')->findOrFail($id);
        return [
            'id' => $category->id,
            'title' => $category->name,
            'sub_categories' => $category->children,
        ];*/
        $category = Category::with('children')->findOrFail($id);
        return new CateogryResource($category);
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
            'name'      => 'sometimes|required',
            'parent_id' => 'nullable|exists:categories,parent_id',
        ]);
        $category = Category::findOrFail($id);
        $category->update($request->all());

        return FacadesResponse::json([
            'status' => 200,
            'message' => 'category Updated',
            'category' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return FacadesResponse::json([
            'status' => 200,
            'message' => 'category Deleted',
        ]);
    }
}
