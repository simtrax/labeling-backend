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

    protected $appends = ['darknetCfgFile', 'darknetNamesFile', 'darknetDataFile'];

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

    public function getDarknetCfgFileAttribute()
    {
        $path = $this->path . '/cfg/yolo.cfg';
        if(Storage::disk('projects')->exists($path)) {
            return Storage::disk('projects')->get($path); 
        }

        return '';
    }

    public function getDarknetNamesFileAttribute()
    {
        $path = $this->path . '/data/obj.names';
        if(Storage::disk('projects')->exists($path)) {
            return Storage::disk('projects')->get($path); 
        }

        return '';
    }

    public function getDarknetDataFileAttribute()
    {
        $path = $this->path . '/data/obj.data';
        if(Storage::disk('projects')->exists($path)) {
            return Storage::disk('projects')->get($path); 
        }

        return '';
    }

}
