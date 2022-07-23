<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->negotiableCategory = $this->negotiableCategory($product);
            if ($product->status != 3) {
                $product->regionObj = $product->merchant->region()->first();

                return view('product', ['product'=>$product]);
            } else {
                return view('product');
            }
        } catch (\Exception $e) {
            return view('product');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Product|Product[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        return Product::find($product->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function negotiableCategory(Product $product)
    {
        $delivery = [
            0 => 'remote_only',
            1 => 'hand_remote',
            2 => 'hand_only'
        ];
        $deliveryOption = $product->delivery_option;
        $negotiable = $product->negotiable;
        if ($delivery[$deliveryOption] === 'remote_only') {
            return $negotiable ? 'part_negotiable' : 'part';
        }
        if ($delivery[$deliveryOption] === 'hand_remote') {
            return $negotiable ? 'part_negotiable_hand' : 'part_hand';
        }
        if ($delivery[$deliveryOption] === 'hand_only') {
            return $negotiable ? 'part_negotiable_hand_only' : 'part_hand_only';
        }
    }
}
