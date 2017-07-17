@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <h2>Edit Status</h2>
        </div>
        <div class="row">
            {!! Form::model($status,['class' => 'form-horizontal']) !!}
                <div class="form-group">
                    {!! Form::label('name','Name',[])!!}
                    {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Enter Status Name'])!!}
                </div>
                <div class="form-group">
                    {!! Form::submit('Edit',['class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop