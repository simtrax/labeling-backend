<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Project;

class TileController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @param  int $zoom
     * @param  int $x
     * @param  int $y
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, int $zoom, int $x, int $y)
    {
        $tilePath = $project->path . "/tiles/{$zoom}/{$x}/{$y}.png";
        if(Storage::disk('projects')->exists($tilePath)) {
            $file = Storage::disk('projects')->get($tilePath);
            
            return \Image::make($file)->response('png');
        } else {
            return \Image::make(public_path('transparent.png'))->response('png');
        }
    }

}
