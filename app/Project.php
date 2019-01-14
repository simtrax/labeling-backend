<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->path = (string) \Uuid::generate(4);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'minZoom', 'maxZoom', 'path', 'geotif', 'status'];

    protected $hidden = ['path'];

    /**
     * Get the images that belongs to this project.
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Get the labels that belongs to this project.
     */
    public function labels()
    {
        return $this->hasMany(Label::class);
    }

    /**
     * Get the detections that belongs to this project.
     */
    public function detections()
    {
        return $this->hasMany(Detection::class);
    }

}
