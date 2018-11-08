<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Image;
use App\Project;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Image::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id'    => 'required|integer|exists:projects,id',
            'file'          => 'required|file'
        ]);

        $project = Project::find($request->project_id);

        $path = $request->file('file')->store('projects/' . $project->path . '/images');

        $image = $project->images()
        ->create([
            'path' => $path
        ]);

        // Storage::makeDirectory('images/' . $image->path);

        // return $image;
    }

    /**
     * Display the specified resource.
     *
     * @param  Image $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        return $image;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Image $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        $request->validate([

        ]);

        $image->update($request->only([
            'title',
            'description'
        ]));

        return $image;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Image $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        $image->delete();
    }
}
