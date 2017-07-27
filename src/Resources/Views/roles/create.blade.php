@extends('cms::layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <h2>Create Role</h2>
        </div>
        <div class="row">
            {!! Form::open(['class' => 'form-horizontal']) !!}
            <div class="form-group">
                {!! Form::label('name','Name',[])!!}
                {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Enter Role Name'])!!}
            </div>
            <div class="form-group">
                {!! Form::label('slug','Slug',[])!!}
                {!! Form::text('slug',null,['class'=>'form-control','placeholder'=>'Enter Role slug'])!!}
            </div>
            <div class="form-group">
                {!! Form::label('description','Description',[])!!}
                {!! Form::textarea('description',null,['class'=>'form-control','placeholder'=>'Enter Role description'])!!}
            </div>
            <div class="form-group">
                {!! Form::label('access','Access to',[])!!}
                {!! Form::select('access', $accessList, null, ['class'=>'form-control'])!!}
            </div>
            <div class="form-group">
                {!! Form::submit('Add Role',['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@stop