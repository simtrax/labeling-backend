<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path', 'project_id'];

    /**
     * Get the project that this image belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
