<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Project;
use App\Label;
use App\Detection;

class DetectionTest extends TestCase
{
    /** @test */
    public function can_create_detection()
    {
        $project = factory(Project::class)->create();

        $label = Label::create([
            'number'		=> 1,
            'title'		    => 'My label',
            'project_id'	=> $project->id,
        ]);
        
        $response = $this->json(
			'POST',
			"/api/projects/{$project->id}/detections",
			[
                'label_id' => $label->id,
                'geojson' => '{"test":"test"}'
            ]
        );
        
		$response
			->assertStatus(201)
			->assertJsonFragment([
                
            ]);
    }
}
