@extends('users::layouts.home')
@section('content')
<div class="col-md-12">
    @if (session('flash.message') != null)
        <div class="flash alert {{ Session::has('flash.class') ? session('flash.class') : 'alert-success' }}">
            {!! session('flash.message') !!}
        </div>
    @endif
    <h3 class="text-center">Users list</h3>
    <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Active</th>
                    <th>Joined date</th>
                </tr>
            </thead>
            <tbody>
                @if($users)
                    @foreach($users as $user)
                        <tr>
                            <td>
                                {{ $user->id }}
                            </td>
                            <td>
                                {{ $user->name }}
                            </td>
                            <td>
                                {{ $user->email }}
                            </td>
                            <td>
                                {{ $user->username }}
                            </td>
                            <td>
                                @if($user->active == 1)
                                    <label class="alert alert-success">Active</label>
                                @else
                                    <label class="alert alert-danger">In Active</label>
                                @endif
                            </td>
                            <td>
                                {{ $user->created_at }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center warning" colspan="6">
                            No Users
                        </td>
                    </tr>
                @endif
            </tbody>

        </table>
    </div>
</div>
@stop
