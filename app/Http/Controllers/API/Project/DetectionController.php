<?php

namespace App\Http\Controllers\API\Project;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Project;
use App\Detection;

class DetectionController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Project $project, Request $request)
    {
        $request->validate([
            'label_id'      => 'required|integer|exists:labels,id',
            'geojson'       => 'required|json',
        ]);

        // Raw Query to create a detection, 
        // or override the model create method.

        // dd($request->all());

        return Detection::create($request->only([
            'label_id',
            'geojson',
        ]) + ['project_id' => $project->id]);
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
