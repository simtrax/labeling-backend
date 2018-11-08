<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    protected $fillable = ['title', 'description', 'path'];

    protected $hidden = ['path'];

    /**
     * Get the images that belongs to this project.
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

}
