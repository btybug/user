@extends('layouts.admin')
@section('page_heading','Dashboard')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"> <i class="fa fa-search"></i> User View</li>
    </ol>
    <div class="row">
    
        <div class="col-md-6 col-md-offset-3 text-center">
        
        <div class="panel panel-default">
  <div class="panel-body">
    <p><label>Name: </label> {{ $user->name }}</p>
                <p><label>Username: </label> {{ $user->username }}</p>
                <p><label>Email: </label> {{ $user->email }}</p>
                <p><label>Active: </label> {{ ($user->active)? "Active" : "Inactive" }}</p>
                <p><label>Role: </label> {{ $user->roles->first()->slug }}</p>
                <p><label>Join Date: </label> {{ $user->created_at }}</p>
                <a href="{!! url('/admin/users')!!}" class="btn btn-primary"> Back </a>
  </div>
</div>
        
        
               

        </div>
        
        
    </div>
@stop
