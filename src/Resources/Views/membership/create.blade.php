@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <h2>Create Membership</h2>
        </div>
        <div class="row">
            {!! Form::open(['class' => 'form-horizontal']) !!}
            <div class="form-group">
                {!! Form::label('name','Name',[])!!}
                {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Enter Membership Name'])!!}
            </div>
            <div class="form-group">
                {!! Form::label('slug','Slug',[])!!}
                {!! Form::text('slug',null,['class'=>'form-control','placeholder'=>'Enter Membership slug'])!!}
            </div>
            <div class="form-group">
                {!! Form::label('description','Description',[])!!}
                {!! Form::textarea('description',null,['class'=>'form-control','placeholder'=>'Enter Membership description'])!!}
            </div>
            <div class="form-group">
                {!! Form::label('default','Make Default',[])!!}
                <input type="checkbox" checked data-toggle="toggle" name="default" data-on="Yes" data-off="No">
            </div>

            <div class="form-group">
                {!! Form::label('default','Approve',[])!!}
                <input type="checkbox" checked data-toggle="toggle" name="approve" data-on="Yes" data-off="No">
            </div>

            <div class="form-group">
                {!! Form::submit('Add Membership',['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@stop
@section("JS")
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
@stop
@push('css')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endpush