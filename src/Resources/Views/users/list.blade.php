@extends('cms::layouts.mTabs',['index'=>'admins_users'])
@section('tab')
    <div class="row">
        <div class="col-md-12">
            <a href="{!! route("admin.users.getCreate") !!}" class="btn btn-info pull-right"><i class="fa fa-plus"></i>
                Add New User</a>
        </div>
        <div class="col-md-12 table-responsive p-0">
            <table class="table table-btybug">
                <thead>
                <tr class="bg-black text-white">
                    <th width="63" align="center">#</th>
                    <th width="178">Email</th>
                    <th width="194">Username</th>
                    <th width="162">Status</th>

                    <th width="125">Role</th>
                    <th width="204">Joined date</th>
                    <th width="133">Options</th>
                </tr>
                </thead>
                <tbody>
                @if(count($users))
                    @foreach($users as $user)
                        <tr>
                            <td>
                                {{ $user->id }}
                            </td>
                            <td>
                                {{ $user->email }}
                            </td>
                            <td>{{ $user->username }}

                            </td>
                            <td>
                                {{--<span class="td-active">active</span>--}}
                                {{--<span class="td-inactive">inactive</span>--}}
                                {{ $user->status }}
                            </td>


                            <td>
                                {!! $user->role ? $user->role->name : 'N/A' !!}
                            </td>

                            <td>
                                {{ \Btybug\Cms\Helpers\helpers::formatDate($user->created_at) }}
                                <p>{{ \Btybug\Cms\Helpers\helpers::formatTime($user->created_at) }}</p>
                            </td>
                            <td>
                                @if(Auth::user()->can('users.admins.edit'))
                                    <a href="{!! route('admin.users.getEdit',$user->id)!!}"
                                       class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>
                                @endif
                                <span class="m-r-5">
                                @if (\Auth::user()->can("users.admins.delete") && $userService->ranking($user->id))
                                        <a data-href="{!! route('admin.users.delete') !!}"
                                           data-key="{!! $user->id !!}" data-type="User {{ $user->username }}"
                                           class="delete-button btn btn-danger btn-xs"><i
                                                    class="fa fa-trash-o f-s-14 "></i></a>
                                    @endif
                                </span>


                                @if(Auth::user()->can('users.admins.view'))
                                    <a href="{!! url('/admin/profile',$user->id)!!}" class="btn btn-primary btn-xs"><i
                                                class="fa fa-eye"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                {!! $users->render() !!}
                @else
                    <tr>
                        <td class="text-center warning" colspan="9">
                            No users
                        </td>
                    </tr>
                    </tbody>
                @endif


            </table>

        </div>
    </div>
    @include('cms::_partials.delete_modal')
@stop
