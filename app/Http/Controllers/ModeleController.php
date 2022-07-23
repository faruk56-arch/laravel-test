<?php

namespace App\Http\Controllers;

use App\Filters\ModeleFilter;
use App\Http\Middleware\CheckIfUserIsAdmin;
use App\Models\Modele;
use App\Services\MergeModels\MergeModeles;
use Illuminate\Http\Request;

class ModeleController extends Controller
{
    public function __construct()
    {
        $this->middleware(CheckIfUserIsAdmin::class)->except('index', 'show');
    }

    /**
     * Display a listing of the cars resource.
     *
     * @param  Request  $request
     * @param  ModeleFilter  $filters
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request, ModeleFilter $filters)
    {
        if ($request->has('name') && $request->name !== '') {
            $modeles = Modele::search($request->name)->get();
            if (sizeof($modeles) !== 0) {
                $first = array_slice($modeles->toArray(), 0, 4);
                usort($first, function ($a, $b) {
                    $brand = strcmp($a['year_start'], $b['year_start']);
                    if ($brand === 0) {
                        return strcmp($a['year_end'], $b['year_end']);
                    }

                    return $brand;
                });
                $modeles = $modeles->slice(4)->values()->toArray();
                usort($modeles, function ($a, $b) {
                    $brand = strcmp($a['brand']['name'], $b['brand']['name']);
                    if ($brand === 0) {
                        return strcmp($a['name'], $b['name']);
                    }

                    return $brand;
                });

                return array_merge($first, $modeles);
            } else {
                return;
            }
        }

        return Modele::filter($filters)->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modele  $modele
     *
     * @return Modele
     */
    public function show(Modele $modele)
    {
        return $modele;
    }

    public function store(Request $request)
    {
        $request->validate(Modele::$createRules);
        $modele = Modele::create($request->all());
        $modele->compatible_modeles = $request->compatible_modeles;
        $modele->save();

        return Modele::find($modele->id);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modele  $modele
     *
     * @return \App\Models\Modele|\App\Models\Modele[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(Request $request, Modele $modele)
    {
        $modele->update($request->all());
        $modele->compatible_modeles = $request->compatible_modeles;
        $modele->save();

        return Modele::find($modele->id);
    }

    public function destroy(Modele $modele, Modele $new_modele)
    {
        $merger = new MergeModeles($modele, $new_modele);
        $merger->merge();
    }
}
