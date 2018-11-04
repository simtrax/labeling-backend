<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Project;

class ProjectTest extends TestCase
{
    /** @test */
    public function can_see_projects()
    {
        factory(Project::class)->create();

        $response = $this->json('GET', '/api/projects');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
				[
					'id',
					'title',
					'description',
				],
			]);
    }

    /** @test */
    public function can_create_project()
    {
		$project = factory(Project::class)->make();
        
        $response = $this->json(
			'POST',
			'/api/projects',
			$project->toArray()
		);
		
		$response
			->assertStatus(201)
			->assertJsonFragment([
				'title'		    => $project->title,
				'description' 	=> $project->description,
            ]);
    }
}
