<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Models\Merchant;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class MerchantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store']]);
    }

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
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $merchant = new Merchant();
        $merchant->merchant_type = $request->merchantType;
        $merchant->country_id = $request->country_id;
        $merchant->merchant_address = $request->address;
        $merchant->merchant_city = $request->city;
        $merchant->merchant_zip = $request->zip;
        $merchant->phone = $request->phone;
        $merchant->region = $request->region;
        if ($request->merchantType == 'Pro') {
            $merchant->merchant_siret = $request->merchantSiret;
            $merchant->merchant_name = $request->merchantName;
        } else {
            $merchant->merchant_name = $request->firstname.' '
                .$request->lastname;
        }
        $merchant->save();
        if (Auth::user()) {
            $user = Auth::user();
            if($request->merchantType == 'Particulier'){
                $user->update($request->only(['address','city','zip','region','phone']));
            }
            $merchant->user()->save($user);
        }
        return $merchant;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Merchant  $merchant
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Merchant $merchant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Merchant  $merchant
     *
     * @return Merchant
     */
    public function update(Request $request, Merchant $merchant): Merchant
    {
        $request->validate([
            'merchant_name'    => Rule::requiredIf($merchant->type === 'Pro'),
            'merchant_city'    => 'required',
            'merchant_zip'     => 'required ',
            'merchant_siret'   => Rule::requiredIf($merchant->type === 'Pro' && $request->country_id === 69),
            'merchant_address' => 'required',
            'country_id'       => 'required',
            'region'           => 'required',
            'phone'            => 'required|numeric',
        ]);
        $merchant->update($request->all());

        return $merchant->refresh();
    }

    public function exportCatalog(Merchant $merchant)
    {
        return Excel::download(new ProductExport($merchant), 'catalog.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Merchant  $merchant
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Merchant $merchant)
    {
        //
    }
}
