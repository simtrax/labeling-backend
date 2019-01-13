<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $tmpName = \Uuid::generate(4) . '.tif';

		Storage::put(
			'tmp/' . $tmpName, 
			Storage::disk('projects')->get($project->path . "/" . $project->geotif)
		);

		$filePath = storage_path() . '/app/tmp/' . $tmpName;
		exec("gdalinfo " . $filePath . " -json", $out);

		$geojson = implode("\n",$out);
        $geojson = json_encode(json_decode($geojson, true)['wgs84Extent']);
        
		$detections = \DB::select(\DB::raw(
			"SELECT 'FeatureCollection' AS type, array_to_json(array_agg(f)) AS features
            FROM (SELECT 'Feature' AS type
            , ST_AsGeoJSON(ST_Transform(d.geom,4326))::json AS geometry
            , row_to_json((SELECT l FROM (SELECT
                    d.id
                ) AS l
              )) AS properties
            FROM detections AS d WHERE project_id = :project_id) AS f;"),
			[
				'project_id' => $project->id,
			]
        );
        
        $detections = json_encode($detections[0], true);

        return view('projects.show', compact('project', 'geojson', 'detections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
    }

}
