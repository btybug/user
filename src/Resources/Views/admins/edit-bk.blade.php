@extends('layouts.admin')
@section('page_heading','Dashboard')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/">Dashboard</a></li>
        <li class="active">Edit Admins</li>
    </ol>
    @if (session('flash.message') != null)
        <div class="flash alert {{ Session::has('flash.class') ? session('flash.class') : 'alert-success' }}">
            {!! session('flash.message') !!}
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <a href="{!! url('/admin/users/sendPassword',$user->id)!!}" class="btn btn-primary pull-right">Send new
                password</a>
        </div>
        <div class="col-md-12">
            {!! Form::open(array('route' => ['admin.users.editAdmins', $user->id], 'method' => 'POST','class' => 'form')) !!}
            @include('users::forms._user_form')
            {!! Form::close() !!}
        </div>
    </div>
@stop
