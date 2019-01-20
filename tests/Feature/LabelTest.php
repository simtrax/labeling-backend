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
    public function can_see_a_projects_labels()
    {
        $project = factory(Project::class)->create();
        $project->labels()->create([
            'number' => 1,
            'title' => 'test'
        ]);

        $response = $this->json('GET', '/api/projects/' . $project->id . '/labels');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
				[
					'id',
					'title',
					'number'
				],
			]);
    }

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

    /** @test */
    public function can_delete_a_label()
	{
        $project = factory(Project::class)->create();
        $label = $project->labels()->create([
            'number' => 1,
            'title' => 'test'
        ]);

		$response = $this->json(
			'DELETE',
			'/api/projects/' . $project->id . '/labels/' . $label->id
		);
		
		$response->assertStatus(200);
        $this->assertDatabaseMissing('labels', $label->toArray());
	}
}
