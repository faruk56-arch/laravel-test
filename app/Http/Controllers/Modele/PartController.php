<?php

namespace App\Http\Controllers\Modele;

use App\Filters\PartFilter;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Modele;
use App\Models\Part;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartController extends Controller
{
    /**
     * Display a listing of the cars resource.
     *
     * @param  Modele  $modele
     * @param  Category  $category
     * @param  PartFilter  $filters
     * @return \Illuminate\Database\Eloquent\Collection|JsonResponse
     */
    public function index(Request $request, Modele $modele, Partfilter $filters)
    {
        if ($request->has('name')) {
            $parts = Part::search($request->name)->get();

            return $parts->reject(function ($part) use ($modele) {
                return $part->modele_id !== null && $part->modele_id !== $modele->id;
            });
        }

        return Part::where(function ($query) use ($modele) {
            $query->where('modele_id', $modele->id)
                    ->orWhereNull('modele_id');
        })
            ->filter($filters)->get()->toArray();
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
     * @param  \App\Models\Part  $part
     * @return Part
     */
    public function show(Modele $modele, Category $category, Part $part)
    {
        return $part;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Part $part)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function destroy(Part $part)
    {
        //
    }
}
