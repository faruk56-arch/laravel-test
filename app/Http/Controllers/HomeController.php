<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Research;

class HomeController extends Controller
{
    public function __invoke()
    {
        $products = Product::where('public', 1)->where('sent', 1)->inRandomOrder()->take(30)->get();
        $researches = Research::where('public', 1)
            ->whereHas('part')
            ->whereHas('modele')
            ->whereNotNull('status')
            ->where('status', '!=', 'archived')
            ->where('status', '!=', 'finished')
            ->inRandomOrder()
            ->take(18)
            ->get()
            ->load('modele');
        return view('home', ['products' => $products, 'researches' => $researches]);
        //return redirect('/'.config('app.locale').'/finder/dashboard');
    }


    public function research()
    {
        return Research::where('public', 1)
            ->whereHas('part')
            ->whereHas('modele')
            ->whereNotNull('status')
            ->where('status', '!=', 'archived')
            ->where('status', '!=', 'finished')
            ->without('user')
            ->with(['user' => function ($query) {
                $query->select('id', 'country_id');
                $query->without('region', 'merchant');
                $query->with('country:id,code,name');
            }])
            ->inRandomOrder()
            ->take(18)
            ->get()
            ->load('modele');
    }

    public function product()
    {
        $product = Product::where('public', 1)
            ->where('sent', 1)
            ->without('merchant')
            ->inRandomOrder()
            ->take(30)
            ->get();
        return response()->json($product);
    }

    public function en()
    {
        $products = Product::where('public', 1)->where('sent', 1)->inRandomOrder()->take(30)->get();
        $researches = Research::where('public', 1)
            ->whereHas('part')
            ->whereHas('modele')
            ->whereNotNull('status')
            ->where('status', '!=', 'archived')
            ->where('status', '!=', 'finished')
            ->inRandomOrder()
            ->take(18)
            ->get()
            ->load('modele');

        return view('home-en', ['products' => $products, 'researches' => $researches]);
    }

}
