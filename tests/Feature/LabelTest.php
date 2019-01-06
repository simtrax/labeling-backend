<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Project;
use App\Label;

class LabelTest extends TestCase
{
    /** @test */
    public function can_create_label()
    {
        $project = factory(Project::class)->create();

        $labelData = [
            'number'		=> 1,
            'title'		    => 'label-title',
        ];
        
        $response = $this->json(
			'POST',
			"/api/projects/{$project->id}/labels",
			$labelData
        );
        
		$response
			->assertStatus(201)
			->assertJsonFragment($labelData);
    }
}
