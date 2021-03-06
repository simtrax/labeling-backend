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

        /**
         * 
         
            SELECT 'FeatureCollection' AS type, array_to_json(array_agg(f)) AS features
            FROM (SELECT 'Feature' AS type
            , ST_AsGeoJSON(ST_Transform(d.geom,4326))::json AS geometry
            , row_to_json((SELECT l FROM (SELECT
                    d.id
                ) AS l
            )) AS properties
            FROM detections AS d WHERE project_id = 1) AS f

         * 
         */
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
            'geom'          => 'required|json',
        ]);

        \DB::insert('INSERT INTO detections (label_id, project_id, geom) values (?, ?, ST_SetSRID(ST_GeomFromGeoJSON(?), 4326) )', [
            $request->label_id, 
            $project->id,
            $request->geom
        ]);

        return Detection::latest()->first();
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
