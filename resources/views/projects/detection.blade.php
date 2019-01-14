@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Detect objects in project: <strong>{{$project->title}}</strong>
                </div>

                <div class="card-body">
                    <project-detection :project="{{$project}}"></project-detection>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
