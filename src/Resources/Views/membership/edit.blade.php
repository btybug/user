@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <h2>Edit Membership</h2>
        </div>
        <div class="row">
            {!! Form::model($membership,['class' => 'form-horizontal']) !!}
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
            <div class="panel panel-default custompanel2">
                <div class="panel-heading">Active</div>
                <div class="panel-body">
                    <div class="col-md-6">
                        <div class="col-md-3">
                            <label>
                                if
                            </label>
                        </div>
                        <div class="col-md-9">
                            {!! Form::select('condition',['' => 'Select Condition'],null,['class' => 'form-control']) !!}
                        </div>

                    </div>
                    <div class="col-md-6">
                       <button class="btn">Add</button>
                    </div>
                </div>
            </div>
            <div class="panel panel-default custompanel2">
                <div class="panel-heading">Pending</div>
                <div class="panel-body">
                 
                </div>
            </div>
            <div class="panel panel-default custompanel2">
                <div class="panel-heading">Suspended</div>
                <div class="panel-body">
                </div>
            </div>
            <div class="form-group">
                {!! Form::submit('Edit',['class' => 'btn btn-primary']) !!}
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