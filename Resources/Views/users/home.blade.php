@extends('users::layouts.home')
@section('content')
        <div class="col-md-12">
            @if (session('flash.message') != null)
                <div class="flash alert {{ Session::has('flash.class') ? session('flash.class') : 'alert-success' }}">
                    {!! session('flash.message') !!}
                </div>
            @endif
            <h3 class="text-center">
                Welcome
                @role('user')
                    User
                @else
                    @role('administrator')
                        Admin
                    @else
                        Superadmin
                    @endrole
                @endrole
            </h3>
        </div>
        <div>

        </div>
@stop
