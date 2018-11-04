<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description'];

    /**
     * Get the images that belongs to this project.
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

}
