<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Project;

class RunDarknetDetectionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $project;
    public $jobPath;

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
        $jobFolderName = (string) \Uuid::generate(4);

        $this->jobPath = Storage::makeDirectory('detection/running_jobs/' . $jobFolderName);

        // Move needed files to local folder

        $filePath = Storage::disk('projects')->path($this->project->geotif);
        $outputFolderPath = Storage::disk('projects')->path($this->project->path . "/tiles");

        $this->project->update(['status' => 'processing']);

        // $numCores = env('NUM_PROCESSING_CORES', '2');
        
        // exec("gdal2tiles.py {$filePath} {$outputFolderPath} -z {$this->project->minZoom}-{$this->project->maxZoom} --processes={$numCores} -s EPSG:32633", $out);
        
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

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        $this->project->update(['status' => 'failed-detection']);
        
        Storage::delete($this->jobPath);

        Log::debug("Object detection for the project with id {$this->project->id} failed.");
        Log::error($exception->getMessage());
    }

}
