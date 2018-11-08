<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use App\Image;
use App\Project;

class ImageTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
		Storage::fake('fake');
	}

    /** @test */
    public function can_create_image()
    {
        $project = factory(Project::class)->create();
        
        $response = $this->json(
			'POST',
			'/api/images', [
            'project_id' => $project->id,
            'file' => UploadedFile::fake()->image('image.jpg')
        ]);

        $image = Image::first();

        Storage::assertExists($image->path);
    }

}
