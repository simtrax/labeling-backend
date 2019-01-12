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
        if($file = Storage::disk('projects')->get("{$project->path}/tiles/{$zoom}/{$x}/{$y}.png")) {
            return \Image::make($file)->response('png');
        } else {
            return abort(404, 'Tile not found');
        }
    }

}
