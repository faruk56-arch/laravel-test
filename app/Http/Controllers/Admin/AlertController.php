<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlertSubscription;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AlertSubscription[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return AlertSubscription::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\AlertSubscription  $alertSubscription
     * @return \Illuminate\Http\Response
     */
    public function show(AlertSubscription $alertSubscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AlertSubscription  $alertSubscription
     * @return \Illuminate\Http\Response
     */
    public function edit(AlertSubscription $alertSubscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AlertSubscription  $alertSubscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlertSubscription $alertSubscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AlertSubscription  $alertSubscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(AlertSubscription $alertSubscription)
    {
        //
    }
}
