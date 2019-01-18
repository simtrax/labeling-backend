<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Project;
use App\YoloModel;

class YoloModelTest extends TestCase
{

    /** @test */
    public function can_see_a_projects_models()
    {
        $project = factory(Project::class)->create();
        $model = factory(YoloModel::class)->create(['project_id' => $project->id]);

        $response = $this->json('GET', "/api/projects/{$project->id}/yolomodels");

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
				[
					'id',
					'title',
					'project_id'
				],
			]);
    }

    /** @test */
    public function can_create_yolo_model()
    {
        $project = factory(Project::class)->create();
        $model = factory(YoloModel::class)->make(['project_id' => $project->id]);
        
        $response = $this->json(
			'POST',
            "/api/projects/{$project->id}/yolomodels",
            $model->toArray() +
			[
                'file' => UploadedFile::fake()->image('yolo.weights')
            ]
        );
        
		$response
			->assertStatus(201)
			->assertJsonFragment([
                'title' => $model->title,
                'project_id' => $project->id,
            ]);

        $model = YoloModel::first();

        Storage::disk('projects')->assertExists($model->path);

        Storage::disk('projects')->deleteDirectory($project->path);
    }

    /** @test */
    public function can_delete_a_model()
	{
        $project = factory(Project::class)->create();
        $model = factory(YoloModel::class)->make(['project_id' => $project->id]);
        
        $response = $this->json(
			'POST',
            "/api/projects/{$project->id}/yolomodels",
            $model->toArray() +
			[
                'file' => UploadedFile::fake()->image('yolo.weights')
            ]
        );

		$response->assertStatus(201);

        $model = YoloModel::first();

        Storage::disk('projects')->assertExists($model->path);

		$response = $this->json(
			'DELETE',
			'/api/projects/' . $project->id . '/yolomodels/' . $model->id
		);
		
		$response->assertStatus(200);
        $this->assertDatabaseMissing('models', $model->toArray());
        
        Storage::disk('projects')->assertMissing($model->path);
        Storage::disk('projects')->deleteDirectory($project->path);
	}
}
