<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YoloModel extends Model
{

    protected $table = 'models';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'path', 'project_id'];

    protected $hidden = ['path'];

    /**
     * Get the project that this image belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
