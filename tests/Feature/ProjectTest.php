<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

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
					'description'
				],
			]);
    }

    /** @test */
    public function can_see_a_project()
    {
        $project = factory(Project::class)->create();

        $response = $this->json('GET', '/api/projects/' . $project->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'description'
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

        $project = Project::first();

        Storage::assertExists('/projects/' . $project->path);

        Storage::deleteDirectory('/projects/' . $project->path);
    }

    /** @test */
    public function a_project_must_contain_a_title()
    {
		$project = factory(Project::class)->make();
        
        $response = $this->json(
			'POST',
			'/api/projects',
			[
                'description' => $project->description
            ]
        );
		
		$response->assertStatus(422);
    }

    /** @test */
    public function can_update_a_project()
	{
		$project = factory(Project::class)->create();
		$newProjectInfo = factory(Project::class)->make();

		$response = $this->json(
			'PATCH',
			'/api/projects/' . $project->id,
			$newProjectInfo->toArray()
		);

		$response
			->assertStatus(200)
			->assertJsonFragment([
				'title'		    => $newProjectInfo->title,
				'description' 	=> $newProjectInfo->description,
			]);
	}

    /** @test */
    public function cant_update_a_project_without_a_title()
	{
		$project = factory(Project::class)->create();

		$response = $this->json(
			'PATCH',
			'/api/projects/' . $project->id,
			[
                'description' => $project->description
            ]
		);

		$response->assertStatus(422);
	}

    /** @test */
    public function can_delete_a_project()
	{
		$project = factory(Project::class)->create();

		$response = $this->json(
			'DELETE',
			'/api/projects/' . $project->id
		);
		
		$response->assertStatus(200);
		
		$this->assertDatabaseMissing('projects', $project->toArray());
	}
}
