<?php

namespace App\Http\Controllers\API\Project;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Project;
use App\Label;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        return $project->labels;
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
            'number'        => 'required|integer|min:1',
            'title'         => 'required|string|min:1|max:100',
        ]);

        return Label::create($request->only([
            'number',
            'title',
        ]) + ['project_id' => $project->id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Label $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Label $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, Label $label)
    {
        $label->delete();
    }
}
