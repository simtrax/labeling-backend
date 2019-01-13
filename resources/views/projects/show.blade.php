@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 px-4">
            <div class="card">
                <div class="card-header">{{$project->title}}</div>

                <div class="card-body">
                    <project-show :project="{{$project}}" :bbox="{{$geojson}}" :detections="{{$detections}}"></project-show>
                </div>
            </div>
        </div>
    </div>
@endsection
