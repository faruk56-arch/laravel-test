<?php

namespace App\Http\Controllers\User\Research;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Research;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * On POST, does a status change to add product to the merchant sales list.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Research  $research
     * @param  \App\Models\Product  $product
     *
     * @return void
     */
    public function store(
        Request $request,
        User $user,
        Research $research,
        Product $product
    ) {
        $sale = Sale::where('product_id', $product->id)
            ->where('research_id', $research->id)->first();
        if ($sale->status === null) {
            $sale->status = 1;
            $sale->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @param  \App\Models\Research  $research
     * @param  \App\Models\Sale  $sale
     *
     * @return void
     */
    public function update(
        Request $request,
        User $user,
        Research $research,
        Product $product
    ) {
        $sale = Sale::where('product_id', $product->id)
            ->where('research_id', $research->id)->first();
        $sale->update($request->all());
        if ($request->has('status') && ! $sale->status) {
            $sale->status = 4;
            $sale->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
