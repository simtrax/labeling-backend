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
use App\YoloModel;

class RunDarknetDetectionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $project;
    public $model;
    public $jobPath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Project $project, YoloModel $model)
    {
        $this->project = $project;
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->jobPath = 'detection/running_jobs/' . (string) \Uuid::generate(4);
        
        Storage::makeDirectory($this->jobPath);
        
        $this->copyConfigFiles();
        $this->copyTifAndModel();
        
        $stream = Storage::getDriver()->readStream('detection/darknet_binary/darknet');
        Storage::put($this->jobPath . '/darknet', $stream);
        $darknetBinaryPath = storage_path() . '/app/' . $this->jobPath . '/darknet';
        chmod($darknetBinaryPath, 755);
        
        $this->copyPythonFiles();

        $this->project->update(['status' => 'processing']);

        exec('cd ' . storage_path() . '/app/' . $this->jobPath . ' && python2 darknet.py --image orthophoto.tif', $out);

        if($out) {
            dd($out);
        }

        $this->project->update(['status' => 'finished']);

        unlink($darknetBinaryPath);
        Storage::deleteDirectory($this->jobPath);
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

    /**
     * Move the needed config files for detection
     */
    public function copyConfigFiles()
    {
        $cfg = Storage::disk('projects')->get($this->project->path . '/cfg/yolo.cfg');
        $data = Storage::disk('projects')->get($this->project->path . '/data/obj.data');
        $names = Storage::disk('projects')->get($this->project->path . '/data/obj.names');

        Storage::put($this->jobPath . '/cfg/yolo.cfg', $cfg);
        Storage::put($this->jobPath . '/data/obj.data', $data);
        Storage::put($this->jobPath . '/data/obj.names', $names);
    }

    /**
     * Move the needed Orthophoto and the pre-trained yolo model
     */
    public function copyTifAndModel()
    {
        $stream = Storage::disk('projects')->getDriver()->readStream($this->model->path);
        Storage::put($this->jobPath . '/model.weights', $stream);

        $stream = Storage::disk('projects')->getDriver()->readStream($this->project->geotif);
        Storage::put($this->jobPath . '/orthophoto.tif', $stream);
    }

    /**
     * Find all the files in the sliding window project folder and copy them to the job folder
     */
    public function copyPythonFiles()
    {
        $files = array_diff(scandir(storage_path() . '/app/' . 'detection/sliding_window_darknet'), ['.', '..', '.gitignore', '.git', 'readme.md']);

        foreach ($files as $file) {
            Storage::put($this->jobPath . '/' . $file, Storage::get('detection/sliding_window_darknet/' . $file));
        }
    }
}
