<?php

namespace App\Http\Controllers;

use App\Filters\BrandFilter;
use App\Http\Middleware\CheckIfUserIsAdmin;
use App\Models\Brand;
use App\Services\MergeModels\MergeBrands;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware(CheckIfUserIsAdmin::class)->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  BrandFilter  $filter
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(request $request, BrandFilter $filter)
    {
        if ($request->has('name')) {
            return Brand::search($request->name)->get();
        }

        return Brand::filter($filter)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return Brand
     */
    public function store(Request $request)
    {
        $request->validate(Brand::$createRules);

        return Brand::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     *
     * @return Brand
     */
    public function show(Brand $brand)
    {
        return $brand;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     *
     * @return Brand
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate(Brand::$updateRules);
        $brand->update($request->all());
        $brand->save();

        return $brand;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Brand $brand, Brand $new_brand)
    {
        $merger = new MergeBrands($brand, $new_brand);
        $merger->merge();
    }
}
