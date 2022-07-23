<?php

namespace App\Http\Controllers\Brand;

use App\Filters\ModeleFilter;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Modele;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModeleController extends Controller
{
    /**
     * Display a listing of the cars resource.
     *
     * @param  ModeleFilter $filters
     * @return JsonResponse
     */
    public function index(Request $request, Brand $brand, ModeleFilter $filters)
    {
        if ($request->has('name')) {
            return Modele::search($request->name)->where('brand_id', $brand->id)->get();
        }

        return $brand->models()->filter($filters)->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Modele
     */
    public function store(Request $request, Brand $brand)
    {
        $request->validate(Modele::$createRules);
        $modele = Modele::create($request->all());
        $brand->models()->save($modele);

        return $modele;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modele  $modele
     * @return Modele
     */
    public function show(Brand $brand, Modele $modele)
    {
        return $modele;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modele  $modele
     */
    public function update(Request $request, Brand $brand, Modele $modele)
    {
        $request->validate(Modele::$updateRules);
        $modele->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modele  $modele
     */
    public function destroy(Brand $brand, Modele $modele)
    {
        $brand->models()->where('id', $modele->id)->delete();
    }
}
