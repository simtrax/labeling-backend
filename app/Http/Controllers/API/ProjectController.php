<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\CreateProjectRequest;
use App\Jobs\CreateTilesJob;
use App\Jobs\CopyDetectionFilesJob;
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
        return Project::withCount('detections')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProjectRequest $request)
    {
        $fileName = (string) \Uuid::generate(4) . '.tif';

        $project = Project::create([
            'title'         => $request->title,
            'description'   => $request->description,
            'minZoom'       => $request->minZoom,
            'maxZoom'       => $request->maxZoom,
            'geotif'        => $fileName,
            'status'        => 'queue'
        ]);

        Storage::disk('projects')->makeDirectory($project->path);
        Storage::disk('projects')->makeDirectory($project->path . '/models');
        $path = $request->file->storeAs($project->path, $fileName, 'projects');

        CreateTilesJob::dispatch($project);
        CopyDetectionFilesJob::dispatch($project);
            
        return $project;
    }

    /**
     * Display the specified resource.
     *
     * @param  Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return $project;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Project $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title'         => 'required|string|min:4|max:255',
            'description'   => 'sometimes|nullable|string',
            'darknetCfg'    => 'sometimes|nullable|string',
            'darknetNames'  => 'sometimes|nullable|string',
            'darknetData'   => 'sometimes|nullable|string',
        ]);

        $project->update($request->only([
            'title',
            'description'
        ]));

        if($request->has('darknetCfg')) {
            Storage::disk('projects')->put($project->path . '/cfg/yolo.cfg', $request->darknetCfg); 
        }
        if($request->has('darknetNames')) {
            Storage::disk('projects')->put($project->path . '/data/obj.data', $request->darknetNames); 
        }
        if($request->has('darknetData')) {
            Storage::disk('projects')->put($project->path . '/data/obj.names', $request->darknetData); 
        }
        
        return $project->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Project $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        Storage::disk('projects')->deleteDirectory($project->path);
    }
}
