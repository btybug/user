@extends('layouts.admin')
@section('content')

    <ol class="breadcrumb">
        <li><a href="/">Dashboard</a></li>
        <li class="active">Admin Role</li>
    </ol>
    <div class="modal fade" id="EditRoleModal" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Role</h4>
                </div>
                <div class="modal-body">

                        {!! Form::open(array('method' => 'GET','class' => 'editRole form')) !!}
                            <input type="hidden" value="" name="id" class="edit-row-id" />
                            <input required class="form-control" type="text" name="name" id="edit-name" value="" />
                            <input type="submit" class="btn btn-success" value="Update" />
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="DelRoleModal" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Role</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="hidden-input-del" value=""/>
                    <div class="box">Deleting this entirty will result in deleting all linked data with it. It cannot be undone. Are you sure want to proceed?</div>

                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-success submitRoleDel pull-right" value="OK" />
                    <input type="button" data-dismiss="modal" aria-label="Close" class="btn pull-right" value="Cancel" />
                </div>
            </div>
        </div>
    </div>
    @if (session('flash.message') != null)
        <div class="flash alert {{ Session::has('flash.class') ? session('flash.class') : 'alert-success' }}">
            {!! session('flash.message') !!}
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12">
            <div class="box-info">

                <div class="tabs-left row">
                    <div id="myTabContent" class="tab-content col-md-9">
                        <div class="tab animated" id="permission">
                            <div class="user-profile-content">

                                <div class="col-sm-4">
                                    <div class="box-info">
                                        <h2><strong>Create New Role</strong> </h2>
                                        @include('users::forms._new_role_form')
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="box-info">
                                        <h2><strong>List All Role</strong> </h2>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Slug</th>
                                                    <th>Option</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($roles as $role)
                                                    <tr>
                                                        <td>{!! $role->name !!}</td>
                                                        <td>{!! $role->slug !!}</td>
                                                        <td>
                                                            <span data-url="{!! URL::to('/admin/users/role/'.$role->id.'/edit') !!}" class='btn btn-xs btn-default' data-name="{{ $role->name }}" onclick="editRole(this)" > <i class='fa fa-edit'></i> Edit</span>

                                                            @if($role->id != \App\Modules\Users\User::ROLE_STUFF && $role->id != \App\Modules\Users\User::ROLE_ADMIN)
                                                                {!! Form::open(['id'=> 'deleteRole'.$role->id,'method' => 'DELETE','route' => 'admin.users.role.destroy','class' => 'form-inline']) !!}
                                                                    <input type="hidden" value="{{ $role->id }}" name="id" class="del-row-id" />
                                                                    <span class="btn btn-danger btn-xs del-role-icon"><i class="fa fa-trash-o"></i></span>
                                                                {!! Form::close() !!}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>

                                <h2><strong>Manage</strong> Permissions</h2>
                                {!! Form::open(['route' => 'admin.users.assignPermissions', 'role' => 'form', 'class'=>'permission-form']) !!}
                                    <div class="col-md-12 permissions-list">
                                        @include('users::forms._permissions_form')
                                    </div>
                                {!! Form::submit(isset($buttonText) ? $buttonText : 'Save Permission',['class' => 'btn btn-primary pull-right']) !!}
                                {!! Form::close() !!}
                                <div class="clear"></div>
                            </div>
                        </div>
                        {{--<div class="tab-pane animated fadeInRight" id="social_login">--}}
                        {{--<div class="user-profile-content">--}}
                        {{--<div class="row">--}}
                        {{--<div class="col-sm-12">--}}
                        {{--{!! Form::open(['route' => 'configuration.socialLoginStore','role' => 'form', 'class'=>'social-login-form form-horizontal']) !!}--}}
                        {{--@include('configuration._social_login')--}}
                        {{--{!! Form::hidden('config_type','social_login')!!}--}}
                        {{--{!! Form::close() !!}--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('CSS')
    <style>
        .permission-form .perm-header{
            background: #B9B3B3;
            padding: 5px;
            border: 2px solid;
            border-radius: 5px;
        }

        .permission-form .perm-header-name{
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        .permission-form .perm-role{
            margin:0 5px;
            border: 1px solid;
            padding: 5px 10px;
            min-width: 70px;
            max-width: 110px;
            min-height: 32px;
        }
    </style>
@stop
@section('JS')
    <script>
        $(document).ready(function(){
            $('body').on('click','.show-child-perm',function(){
                var permID = $(this).data('permid');
                var roleID = $(this).data('roleid');
                var isChecked = 'no';
                var token = $('#token').val();
                var list = JSON.parse($('#permList').val());
                console.log(list);
//                list = list.serializeArray();
//                console.log(list);
                if($(this).is(":checked")) {
                    isChecked = 'yes';
                }

                $.ajax({
                    url: "{!! url('admin/users/toggleChild') !!}",
                    data: {
                        permID: permID,
                        roleID: roleID,
                        isChecked: isChecked,
                        permList: list,
                        _token: token
                    },
                    dataType: 'json',
                    success: function(data) {
                        if(data.code == 200){
                            $('.permissions-list').html(data.data);
                        }
                    },
                    type: 'POST'
                });

                console.log(permID + ' ' + roleID + ' checked:'+isChecked);
            });
        });
        $('.del-role-icon').click(function() {
            var hiddenInput = $(this).parent().find('.del-row-id');
            var id = hiddenInput.val();

            $('#DelRoleModal').find('.hidden-input-del').val(id);
            $('#DelRoleModal').modal();
        });

        $('.submitRoleDel').click(function(){
            var id = $('#DelRoleModal').find('.hidden-input-del').val();
            $('#deleteRole'+id).submit();
        });

        function editRole(event){
            $('.editRole').attr('action',$(event).attr('data-url'));
            $('.editRole').find('#edit-name').val($(event).attr('data-name'));
            $('#EditRoleModal').modal();
        }
    </script>
@stop
@stop
