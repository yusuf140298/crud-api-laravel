<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Products::select('id', 'title', 'description', 'image')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);

        try{
             $imageName = Str::random().'.'.$request->image->getClientOriginalExtension();
             Storage::disk('public')->putFileAs('product/image', $request->image, $imageName);
             Products::create($request->post()+['image' =>$imageName]);

             return response()->json([
                'message' => 'product Successfully',
             ]);
        }
        catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json([
                'message' => 'Something  goes wrong while creating a product!!!'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Products $products)
    {
        //
        return response()->json([
            'product' => $products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Products $products)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Products $products)
    {
        //
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);

        try{
            $products->fill($request->post())->update();
            if ($request->hasFile('image')){
                if($products->image){
                    $exists = Storage::disk('public')->exists("product/image/{$products->image}");
                    if($exists){
                        Storage::disk('public')->delete("product/image/{$products->image}");
                    }
                }

                $imageName = Str::random().'.'.$request->image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('product/image', $request->image,$imageName);
                $products->image = $imageName;
                $products->save();
            }

            return response()->json([
                'message'=>'Product Updated Successfully!!'
            ]);

            
        }catch(\Exception $e){

            \Log::error($e->getMessage());
            return  response()->json([
                'message' => 'Something goes wrong while updating a Product'
            ],500);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Products $products)
    {
        //
        try{

            if($products->image){
                $exists = Storage::disk('public')->exists("product/image/{$products->image}");
                if($exists){
                    Storage::disk('public')->exists("product/image/{$products->image}");
                }
            }

            $products->delete();

            return response()->json([
                'message' => 'Product Delete Successfully!!'
            ]);
            

        }catch(\Exception $e){
            \Log::error($e->getMessage());

            return response()->json([
                'message' => 'Something goes wrong while deleting a product!!'
            ]);
        }
    }
}
