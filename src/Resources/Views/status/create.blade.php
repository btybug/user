@extends('cms::layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <h2>Create Status</h2>
        </div>
        <div class="row">
            {!! Form::open(['class' => 'form-horizontal']) !!}
            <div class="form-group">
                {!! Form::label('name','Name',[])!!}
                {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Enter Status Name'])!!}
            </div>
            <div class="form-group">
                {!! Form::submit('Add Status',['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@stop