<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

use App\Project;

class CopyDetectionFilesJob implements ShouldQueue
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
        Storage::disk('projects')->makeDirectory($this->project->path . '/cfg');
        Storage::disk('projects')->makeDirectory($this->project->path . '/data');

        $cfg = Storage::get('detection/cfg/yolo.cfg');
        $data = Storage::get('detection/data/obj.data');
        $names = Storage::get('detection/data/obj.names');
        Storage::disk('projects')->put($this->project->path . '/cfg/yolo.cfg', $cfg); 
        Storage::disk('projects')->put($this->project->path . '/data/obj.data', $data); 
        Storage::disk('projects')->put($this->project->path . '/data/obj.names', $names); 
    }
}
