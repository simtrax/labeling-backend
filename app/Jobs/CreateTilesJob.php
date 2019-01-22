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

class CreateTilesJob implements ShouldQueue
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
        $this->jobPath = 'tiling/running_jobs/' . (string) \Uuid::generate(4);
        $filePath = $this->jobPath . '/orthophoto.tif';
        
        Storage::makeDirectory($this->jobPath);

        $stream = Storage::disk('projects')->getDriver()->readStream($this->project->geotif);
        Storage::put($filePath, $stream);

        $filePath = Storage::path($filePath);
        $outputFolderPath = Storage::path($this->jobPath . "/tiles");

        $this->project->update(['status' => 'processing']);

        $numCores = env('NUM_PROCESSING_CORES', '2');
        
        exec("gdal2tiles.py {$filePath} {$outputFolderPath} -z {$this->project->minZoom}-{$this->project->maxZoom} --processes={$numCores} -s EPSG:32633", $out);
        
        if($out) {
            Storage::delete([
                $this->jobPath . "/tiles/googlemaps.html",
                $this->jobPath . "/tiles/leaflet.html",
                $this->jobPath . "/tiles/openlayers.html",
                $this->jobPath . "/tiles/tilemapresource.xml",
            ]);
            
            $this->copyTiles();
        }

        Storage::deleteDirectory($this->jobPath);
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
        $this->project->update(['status' => 'failed-tile-creation']);
        
        Storage::deleteDirectory($this->jobPath . "/tiles");

        Log::debug("Creating tiles for the project with id {$this->project->id} failed.");
        Log::error($exception->getMessage());
    }

    /**
     * Copy all the newly created tiles to the projects folder
     */
    private function copyTiles()
    {
        $files = Storage::allFiles($this->jobPath . "/tiles");

        foreach ($files as $file) {
            $stream = Storage::getDriver()->readStream($file);
            $fileName = str_replace($this->jobPath . "/tiles/", "", $file);

            Storage::disk('projects')->put($this->project->path . '/tiles/' . $fileName, $stream);
        }
    }
}
