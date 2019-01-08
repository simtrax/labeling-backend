<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detection extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['geom', 'project_id'];

    /**
     * Get the project that this label belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
