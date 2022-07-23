<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Notifications\RejectedProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Models\Product[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(User $user)
    {
        return Product::withTrashed()->orderBy('created_at', 'desc')->get()->load('modele', 'merchant.user');
    }

    public function productsToEdit()
    {
        return Product::orderBy('created_at', 'desc')->where('status', 1)->where('sent', 0)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \App\Models\Product|\Illuminate\Database\Eloquent\Model
     */
    public function store(User $user, Request $request)
    {
        return Product::create(array_merge([
            'sender' => $user->id,
        ], $request->all()));
    }

    public function getIdMessage($id)
    {
        return DB::table('research_product')->find($id)->research_id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function reject(Request $request, User $admin, Product $product)
    {
        $request->validate(['message' => 'required|filled']);
        try {
            $product->merchant->user->each(function (User $user) use ($request, $product) {
                $user->notify(new RejectedProduct(
                $request->message,
                $product
            ));
            });
            $product->delete();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
