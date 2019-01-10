<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'projects'      => 'API\ProjectController',
    'images'        => 'API\ImageController',
    'labels'        => 'API\LabelController',
]);

Route::post('/projects/{project}/labels', 'API\Project\LabelController@store');
Route::post('/projects/{project}/detections', 'API\Project\DetectionController@store');

Route::post('/projects/{project}/tiles', 'API\Project\TileController@store');