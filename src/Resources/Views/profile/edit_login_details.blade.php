@extends('layouts.mTabs',['index'=>'edit_profile'])
@section('tab')
    <div class="container-fluid">
        <div class="row">
            <h2>Edit Login details</h2>
        </div>
        <div class="row">
            {!! Form::model($model,['class' => 'form-horizontal']) !!}
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
                {!! Form::select('role_id',['' => 'Select Role'] + \App\Modules\Users\Models\Roles::pluck('name','id')->toArray(),null,['class'=>'form-control'])!!}
            </div>
            <div class="form-group">
                {!! Form::label('membership_id','Select Membership',[])!!}
                {!! Form::select('membership_id',['' => 'Select Membership'] + \App\Modules\Users\Models\Membership::pluck('name','id')->toArray(),null,['class'=>'form-control'])!!}
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
            <div class="form-group">
                @include('users::_partials.form_settings')
            </div>
            <div class="form-group">
                {!! Form::submit('Edit',['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
@stop

@section("CSS")
    {!! HTML::style('/resources/assets/libs/animate/css/animate.css') !!}
@stop
