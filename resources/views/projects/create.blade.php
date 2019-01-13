@extends('layouts.app')

@section('content')
<div class="content">
    <div class="row justify-content-center">
        <div class="col-md-8 px-4">
            <div class="card">
                <div class="card-header">Create a new project</div>
                
                <div class="card-body">
                    <project-create></project-create>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
