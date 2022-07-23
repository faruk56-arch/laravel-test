<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;

class PayementController extends Controller
{
    public function index(Request $request)
    {
        dump($request->all());
        return view('payement');
    }

    public function charge(Request $request)
    {
        //dd($request->stripeToken);
        $charge = Stripe::charges()->create([
            'currency' => 'EUR',
            'source' => $request->stripeToken,
            'amount'   => $request->amount,
            'description' => ' Test from laravel new app'
        ]);

        $chargeId = $charge['id'];

        if ($chargeId) {

            dd($charge);
            // save order in orders table ...

            /*
            auth()->user()->orders()->create([
                'cart' => serialize(session()->get('cart'))
            ]);
            // clearn cart

            session()->forget('cart');
            return redirect()->route('store')->with('success', " Payment was done. Thanks");
            */
        } else {
            return redirect()->back();
        }
    }
}
