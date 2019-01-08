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

        Storage::disk('projects')->assertExists($project->path);

        Storage::disk('projects')->deleteDirectory($project->path);
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
        $project = factory(Project::class)->make();
        
        $response = $this->json(
			'POST',
			'/api/projects',
			$project->toArray()
        );

        $project = Project::first();

        Storage::disk('projects')->assertExists($project->path);

        $label = $project->labels()->create([
            'number' => 1,
            'title' => 'test'
        ]);

        \DB::insert('INSERT INTO detections (label_id, project_id, geom) values (?, ?, ST_SetSRID(ST_GeomFromGeoJSON(?), 4326) )', [
            $label->id, 
            $project->id,
            '{"type":"Polygon","coordinates":[[[13.2289021165253,55.6769444709128],[13.2271680621241,55.6770181670021],[13.2269125662969,55.6770296103676],[13.2265677143539,55.6770640179868],[13.2264827021033,55.677493904253],[13.2259023690171,55.6807869754299],[13.2259711968325,55.6807774195208],[13.2260968817539,55.6806018150367],[13.2281976505767,55.6775914224694],[13.2284792829072,55.6772942441158],[13.2286601123629,55.6771285997283],[13.2289021165253,55.6769444709128]]]}'
        ]);

        $detection = \App\Detection::latest()->first();

		$response = $this->json(
			'DELETE',
			'/api/projects/' . $project->id
		);
		
		$response->assertStatus(200);
		
		$this->assertDatabaseMissing('projects', $project->toArray());
        $this->assertDatabaseMissing('labels', $label->toArray());
        $this->assertDatabaseMissing('detections', $detection->toArray());
        
        Storage::disk('projects')->assertMissing($project->path);
	}
}
