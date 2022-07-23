<?php

namespace App\Http\Controllers;

use App\Actions\Translations\CreateTranslation;
use App\Actions\Translations\DeepLTranslation;
use App\Filters\PartFilter;
use App\Http\Middleware\CheckIfUserIsAdmin;
use App\Models\Part;
use App\Repositories\PartRepository;
use App\Services\MergeModels\MergeParts;
use Illuminate\Http\Request;

class PartController extends Controller
{
    private $part;

    public function __construct(
        Part $part,
        PartRepository $partRepository
    ) {
        $this->middleware(CheckIfUserIsAdmin::class)->except('index', 'show');
        $this->partRepository = $partRepository;
        $this->part = $part;
    }

    /**
     * Display a listing of the cars resource.
     *
     * @param  PartFilter  $filters
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request, Partfilter $filters)
    {
        if ($request->has('name')) {
            return Part::search($request->name)->get();
        }

        return $this->part::filter($filters)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return Part|\Illuminate\Database\Eloquent\Model
     */
    public function store(Request $request)
    {
        $part = Part::create($request->all());
        if(isset($request->translation)){
            if(isset($request->translation['name']))CreateTranslation::create('name', $request->translation['name']['fr_FR'], $part);
            else CreateTranslation::create('name', $request->translation['fr_FR'], $part);
        }
        else CreateTranslation::create('name', $request->fr_FR, $part);
        DeepLTranslation::execute($part->refresh(), 'name');
        $part->bywords = $request->bywords;
        $part->save();

        return Part::find($part->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Part  $part
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Part $part)
    {
        return $part;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Part  $part
     *
     * @return \App\Models\Part|\App\Models\Part[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response
     */
    public function update(Request $request, Part $part)
    {
        $part->update($request->all());
        $part->bywords = $request->bywords;
        $translation = CreateTranslation::findTranslation('name', $part);
        $translation->update(['fr_FR' => $request->fr_FR, 'en_EN' => $request->en_EN, 'de_DE' => $request->de_DE]);
        $part->save();

        return Part::find($part->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Part  $part
     * @param  \App\Models\Part  $new_part
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Part $part, Part $new_part)
    {
        $merger = new MergeParts($part, $new_part);
        $merger->merge();
    }
}
