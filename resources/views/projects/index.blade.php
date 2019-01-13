@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{'/projects/create'}}" class="float-right">Create project</a>
                    Projects
                </div>

                <div class="card-body">
                    <project-index></project-index>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
