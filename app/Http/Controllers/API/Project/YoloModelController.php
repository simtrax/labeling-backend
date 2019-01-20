<?php

namespace App\Http\Controllers\API\Project;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Project;
use App\YoloModel;

class YoloModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\YoloModel  $yoloModel
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        return $project->models;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\YoloModel  $yoloModel
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Project $project, Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:5|max:255',
            'file' => 'file'
        ]);
        
        $fileName = (string) \Uuid::generate(4) . '.weights';
        $path = $request->file->storeAs($project->path . '/models', $fileName, 'projects');

        $model = $project->models()->create([
            'title' => $request->title,
            'path' => $path
        ]);

        return $model;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\YoloModel  $yoloModel
     * @return \Illuminate\Http\Response
     */
    public function show(YoloModel $yoloModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\YoloModel  $yoloModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, YoloModel $yoloModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\YoloModel  $yoloModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, YoloModel $model)
    {
        Storage::disk('projects')->delete($model->path);
        $model->delete();
    }
}
