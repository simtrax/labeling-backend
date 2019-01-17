@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Edit project: <strong>{{$project->title}}</strong>
                </div>

                <div class="card-body">
                <project-edit :project="{{$project}}"></project-edit>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
