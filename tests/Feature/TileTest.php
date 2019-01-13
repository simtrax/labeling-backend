<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use App\Project;

class TileTest extends TestCase
{

    /** @test */
    public function can_respond_with_tiles()
    {
        $project = factory(Project::class)->create();
        $project->path = \Uuid::generate(4);

        $zoom = 22;
        $x = 2251007;
        $y = 2881620;
        
        Storage::disk('projects')->makeDirectory($project->path . '/tiles');

        $image = UploadedFile::fake($y)->image('.png');

        Storage::disk('projects')->move(Storage::get('old/file1.jpg'), $project->path . "/tiles/{$zoom}/{$x}/{$y}.png");
        
        $response = $this->json('GET', "/projects/{$project->id}/tiles/{$zoom}/{$x}/{$y}");
        
        $response->assertStatus(200);

        Storage::disk('projects')->deleteDirectory($project->path);


        // /22/2251007/2881620.png
    }

}
