<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

use App\Project;

class CreateTilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $project;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filePath = Storage::disk('projects')->path($this->project->geotif);
        $outputFolderPath = Storage::disk('projects')->path($this->project->path . "/tiles");

        $this->project->update(['status' => 'processing']);

        $numCores = env('NUM_PROCESSING_CORES', '2');
        
        exec("gdal2tiles.py {$filePath} {$outputFolderPath} -z {$this->project->minZoom}-{$this->project->maxZoom} --processes={$numCores} -s EPSG:32633", $out);
        
        if($out) {
            Storage::disk('projects')->delete([
                $this->project->path . "/tiles/googlemaps.html",
                $this->project->path . "/tiles/leaflet.html",
                $this->project->path . "/tiles/openlayers.html",
                $this->project->path . "/tiles/tilemapresource.xml",
            ]);
        }

        $this->project->update(['status' => 'finished']);
    }
}
