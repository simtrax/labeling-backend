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
     * Boot method
     * 
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function($model) {

            // if(isset($model->geom)) {
                $model->setAttribute('geom',  \DB::raw('ST_GeomFromGeoJSON("{\'type\':\'Point\',\'coordinates\':[-48.23456,20.12345]}")') );
            // }

        });
    }

    /**
     * Get the project that this label belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
