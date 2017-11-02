@extends('btybug::layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <h2>Edit Admin</h2>
        </div>
        <div class="row">
            {!! Form::model($admin,['class' => 'form-horizontal']) !!}
            <div class="form-group">
                {!! Form::label('username','Username',[])!!}
                {!! Form::text('username',null,['class'=>'form-control','placeholder'=>'Enter Username'])!!}
            </div>
            <div class="form-group">
                {!! Form::label('email','Email',[])!!}
                {!! Form::text('email',null,['class'=>'form-control','placeholder'=>'Enter Email'])!!}
            </div>
            <div class="form-group">
                {!! Form::label('role_id','Roles',[])!!}
                {!! Form::select('role_id',['' => 'Select Role'] + $rolesList,null,['class'=>'form-control'])!!}
            </div>
            <div class="form-group">
                {!! Form::label('status','Status',[])!!}
                {!! Form::select('status',['inactive' => 'Inactive', 'active' => 'Active'],null,['class'=>'form-control'])!!}
            </div>
            <div class="form-group">
                {!! Form::label('password','Password',[])!!}
                {!! Form::password('password',['class'=>'form-control','placeholder'=>'Enter Password'])!!}
            </div>
            <div class="form-group">
                {!! Form::label('password_confirmation ','Confirm Password',[])!!}
                {!! Form::password('password_confirmation',['class'=>'form-control','placeholder'=>'Enter Confirm Password'])!!}
            </div>
            {{--<div class="form-group">--}}
            {{--@include('users::_partials.form_settings')--}}
            {{--</div>--}}
            <div class="form-group">
                {!! Form::submit('Edit',['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
@stop

