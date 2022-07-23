<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    private $country;

    public function __construct(
        Country $country
    ) {
        $this->country = $country;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Country[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $firstCountries = $this->country->with('regions')->orderBy('name', 'ASC')->whereIn('id', [18, 38, 51, 62, 69, 71, 100, 123, 153])->get();
        $separator = new Country();
        $separator->disabled = true;
        $separator->name = '-------------------------';
        $firstCountries->push($separator);

        return $firstCountries->mergeRecursive($this->country->with('regions')->orderBy('name', 'ASC')->whereNotIn('name', ['null'])->get());
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
