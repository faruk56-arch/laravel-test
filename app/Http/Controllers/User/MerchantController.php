<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(User $user)
    {
        return response()->json(
            [
                'status' => 'success',
                'data' => $user->merchant(),
            ],
            201
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, Request $request)
    {
        $merchant = new Merchant();
        $merchant->merchant_type = $request->merchantType;
        $merchant->country_id = $user->country_id;
        $merchant->merchant_address = $user->address;
        $merchant->merchant_city = $user->city;
        $merchant->merchant_zip = $user->zip;
        $merchant->region = $user->region;
        if ($request->merchantType == 'Pro') {
            $merchant->merchant_siret = $request->merchantSiret;
            $merchant->merchant_name = $request->merchantName;
        } else {
            $merchant->merchant_name = $user->firstname.' '.$user->lastname;
        }
        $merchant->save();

        return $merchant->user()->save($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Merchant $merchant
     * @param \Illuminate\Http\Request $request
     * @return Merchant
     */
    public function update(Merchant $merchant, Request $request)
    {
        $merchant->update($request->all());

        return $merchant;
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
}
