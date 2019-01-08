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
                'geom' => '{"type":"Polygon","coordinates":[[[13.2289021165253,55.6769444709128],[13.2271680621241,55.6770181670021],[13.2269125662969,55.6770296103676],[13.2265677143539,55.6770640179868],[13.2264827021033,55.677493904253],[13.2259023690171,55.6807869754299],[13.2259711968325,55.6807774195208],[13.2260968817539,55.6806018150367],[13.2281976505767,55.6775914224694],[13.2284792829072,55.6772942441158],[13.2286601123629,55.6771285997283],[13.2289021165253,55.6769444709128]]]}'
            ]
        );
        
		$response
			->assertStatus(200)
			->assertJsonFragment([
                'label_id' => $label->id,
                'label_id' => $project->id,
                // 'geometry' => '{"type":"Polygon","coordinates":[[[13.2289021165253,55.6769444709128],[13.2271680621241,55.6770181670021],[13.2269125662969,55.6770296103676],[13.2265677143539,55.6770640179868],[13.2264827021033,55.677493904253],[13.2259023690171,55.6807869754299],[13.2259711968325,55.6807774195208],[13.2260968817539,55.6806018150367],[13.2281976505767,55.6775914224694],[13.2284792829072,55.6772942441158],[13.2286601123629,55.6771285997283],[13.2289021165253,55.6769444709128]]]}'
            ]);
    }
}
