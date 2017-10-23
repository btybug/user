@extends('cms::layouts.mTabs',['index'=>'admins_users'])

@section('tab')
    <div class="row">
        <div class="col-md-12">
            <a href="{!! url("admin/users/admins/create") !!}" class="btn btn-info pull-right"><i
                        class="fa fa-plus"></i> Create Admin</a>
        </div>
        <div class="col-md-12 table-responsive p-0">
            <table class="table table-bordered">
                <thead>
                <tr class="bg-black text-white">
                    <th width="63" align="center">#</th>
                    <th width="178">Email</th>
                    <th width="194">Username</th>
                    <th width="162">Status</th>
                    <th width="125">Role</th>
                    <th width="125">Access</th>
                    <th width="204">Joined date</th>
                    <th width="133">Options</th>
                </tr>
                </thead>
                <tbody>
                @if(count($admins))
                    @foreach($admins as $admin)
                        <tr>
                            <td>
                                {{ $admin->id }}
                            </td>
                            <td>
                                {{ $admin->email }}
                            </td>
                            <td>{{ $admin->username }}

                            </td>
                            <td>
                                {{ $admin->status }}
                            </td>
                            <td>
                                {{ $admin->role->name }}
                            </td>

                            <td>
                                {{ $admin->role->getAccessName() }}
                            </td>

                            <td>
                                {{ \Sahakavatar\Cms\Helpers\helpers::formatDate($admin->created_at) }}
                                <p>{{ \Sahakavatar\Cms\Helpers\helpers::formatTime($admin->created_at) }}</p>
                            </td>
                            <td>
                            <span class="pull-left m-r-5">
                                @if (\Auth::user()->can("users.admins.delete") && $userService->ranking($admin->id))
                                    <a data-href="{!! url('/admin/users/admins/delete') !!}"
                                       data-key="{!! $admin->id !!}" data-type="Admin {{ $admin->username }}"
                                       class="delete-button btn btn-danger btn-xs"><i
                                                class="fa fa-trash-o f-s-14 "></i></a>
                                @endif
                                </span>
                                @if(Auth::user()->can('users.admins.edit'))
                                    <a href="{!! url('/admin/users/admins/edit',$admin->id)!!}"
                                       class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>
                                @endif

                                @if(Auth::user()->can('users.admins.view'))
                                    <a href="{!! url('/admin/profile',$admin->id)!!}" class="btn btn-primary btn-xs"><i
                                                class="fa fa-eye"></i></a>
                                @endif

                                {!! $userService->getOptions($admin) !!}
                            </td>
                        </tr>
                    @endforeach

                @else
                    <tr>
                        <td class="text-center warning" colspan="9">
                            No Admins
                        </td>
                    </tr>
                @endif
                </tbody>

            </table>
            {!! $admins->render() !!}
        </div>
    </div>
    @include('cms::_partials.delete_modal')
@stop
@section('CSS')
    {!! HTML::style('js/datatable/css/jquery.dataTables.min.css') !!}
@stop
@section('JS')
    {!! HTML::script('js/datatable/js/jquery.dataTables.min.js') !!}

@stop


