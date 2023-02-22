<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropietarioRequest;
use App\Http\Requests\UpdatePropietarioRequest;
use App\Models\Propietario;

class PropietarioController extends Controller
{
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
     * @param  \App\Http\Requests\StorePropietarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePropietarioRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Propietario  $propietario
     * @return \Illuminate\Http\Response
     */
    public function show(Propietario $propietario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Propietario  $propietario
     * @return \Illuminate\Http\Response
     */
    public function edit(Propietario $propietario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePropietarioRequest  $request
     * @param  \App\Models\Propietario  $propietario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePropietarioRequest $request, Propietario $propietario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Propietario  $propietario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Propietario $propietario)
    {
        //
    }
}
