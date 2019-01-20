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
]);

Route::get('/projects/{project}/labels', 'API\Project\LabelController@index');
Route::post('/projects/{project}/labels', 'API\Project\LabelController@store');
Route::delete('/projects/{project}/labels/{label}', 'API\Project\LabelController@destroy');
Route::post('/projects/{project}/detections', 'API\Project\DetectionController@store');

Route::get('/projects/{project}/yolomodels', 'API\Project\YoloModelController@index');
Route::post('/projects/{project}/yolomodels', 'API\Project\YoloModelController@store');
Route::delete('/projects/{project}/yolomodels/{yolomodel}', 'API\Project\YoloModelController@destroy');