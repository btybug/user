@extends('layouts.admin')
@section('content')

    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-user"></i> Admin Role</li>
    </ol>
    <div class="row">
        <div class="col-md-12 p-10">
            <div class="text-right">
                <button class="btn btn-warning btnadd" data-action="addnew"><i class="fa fa-plus"></i> New Role
                </button>
            </div>

            <div id="edit_class_id">

            </div>
            <div id="addnewform" class="addnewitems collapse" aria-expanded="true">
                <div class="panel panel-default" style="margin-top: 10px;">
                    <div class="panel-heading  bg-black-darker text-white">Role</div>
                    <div class="panel-body roles-form">
                        @include('users::forms._new_role_form')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container col-md-12"> {{--<a href="{!! url('/admin/themes/classes/create-text-class') !!}" class="btn btn-success" style="float: right;">+new--}}
        {{--text class</a>--}}
        <table class="table table-bordered m-0">
            <thead>
            <tr class="bg-black-darker text-white">
                <th width="15%">Icon</th>
                <th width="15%">Name</th>
                <th width="15%">Slug</th>
                <th width="200">Action</th>
            </tr>
            </thead>
            <tbody>

            @foreach($roles as $role)
                <tr>
                    <td>
                        <div style="max-width: 100px;overflow: hidden;height: 50px;">
                            @if($role->icon)
                                <img src="{!! $role->icon !!}" width="50" />
                            @else
                                No Icon
                            @endif
                        </div>
                    </td>
                    <td>{!! $role->name !!}</td>
                    <td>{!! $role->slug !!}</td>
                    <td>
                        @if(!in_array($role->id,\App\Modules\Users\User::$defaultRoles))
                            <div style="display:inline-block;">
                                {!! Form::open(['id'=> 'deleteRole'.$role->id,'method' => 'DELETE','route' => 'admin.users.role.destroy','class' => 'form-inline', 'style'=>'margin-bottom:-19px; padding:0' ]) !!}
                                <input type="hidden" value="{{ $role->id }}" name="id" class="del-row-id"/>
                                <span class="btn btn-danger del-role-icon" style=""><i class="fa fa-trash-o"></i></span>
                                {!! Form::close() !!}
                            </div>
                            <button data-id="{!! $role->id !!}"
                                    class="btn btn-info edit-class"
                                    data-action="addnew">
                                <i class="fa fa-edit"></i>
                            </button>
                            <a href="{!! url('/admin/users/configuration/access',$role->id)!!}"
                               class="btn btn-success">
                                <i class="fa fa-crop">Access</i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>

    <div class="col-md-12">
        <div class="box-info col-md-6">
            <div class="tabs-left row">
                <div id="myTabContent" class="tab-content col-md-12">
                    <div class="tab animated" id="permission">
                        <div class="user-profile-content">
                            <h3>Manage Permissions</h3>

                            {!! Form::open(['route' => 'admin.users.assignPermissions', 'role' => 'form', 'class'=>'permission-form ']) !!}
                            <div class="col-md-12 permissions-list">
                                @include('users::forms._permissions_form')
                            </div>
                            {!! Form::submit(isset($buttonText) ? $buttonText : 'Save Permission',['class' => 'btn btn-primary col-sm-offset-5 m-t-10']) !!}
                            {!! Form::close() !!}
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 p-b-15">
            @include('users::_partials.menu')
        </div>
    </div>


    <div class="modal fade" id="DelRoleModal" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Role</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="hidden-input-del" value=""/>
                    <div class="box">Deleting this entirty will result in deleting all linked data with it. It cannot be
                        undone. Are you sure want to proceed?
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-success submitRoleDel pull-right" value="OK"/>
                    <input type="button" data-dismiss="modal" aria-label="Close" class="btn pull-right" value="Cancel"/>
                </div>
            </div>
        </div>
    </div>
    @stop
    @section('CSS')

            <!--Attach CSS Files-->
    {!! HTML::style('/public/libs/bootstrap-editable/css/bootstrap-editable.css') !!}

    <link rel="stylesheet" href="/public/css/bootstrap.css?v=1.1" />
    <link rel="stylesheet" href="/public/libs/jqueryui/css/jquery-ui.min.css" />
    <link rel="stylesheet" href="/public/libs/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/public/libs/bootstrap-select/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="/public/libs/jquery.mCustomScrollbar/css/jquery.mCustomScrollbar.css" />
    <link rel="stylesheet" href="/public/libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" />
    <link rel="stylesheet" href="/appdata/resources/ganaral_layouts/menubuilder/css/menu.css">
    <link rel="stylesheet" href="/appdata/resources/ganaral_layouts/menubuilder/css/styles.css?v=3.8">
            <!--Attach CSS  Files-->
    <style>
        .menu-preview{

        }

        .arrowicon, .nowicon{
            margin-left:0;
        }

        .download-btn{
            margin-right: 10px;
        }

        .formrow{
            padding: 10px 0;
            border:0;
        }

        .form-horizontal .control-label{
            text-align: left;
        }
        .menuBuilder {
            min-height: 700px;
            margin-top: 65px;
        }
        .permission-form .perm-header {
            background-image: linear-gradient(to bottom, #e8e8e8 0px, #f5f5f5 100%);
            background-repeat: repeat-x;
            border-color: #dcdcdc;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) inset, 0 1px 0 rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            padding: 9px;
            margin: 10px -15px 15px -15px;
        }

        .permission-form .perm-header-name {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        .permission-form .perm-role {
            margin: 0 5px;
            border: 1px solid;
            padding: 5px 10px;
            min-width: 70px;
            max-width: 110px;
            min-height: 32px;
        }
    </style>
    @stop

            <!--JS-->
    @section('JS')

            <!--Attach JS Files-->
    {!! HTML::script('/public/libs/bootstrap-editable/js/bootstrap-editable.min.js') !!}

    <script src="/public/js/jquery-2.1.4.min.js" type="text/javascript"></script>
    <script src="/public/libs/jqueryui/js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/public/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/public/libs/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="/public/libs/jquery.mCustomScrollbar/js/jquery.mCustomScrollbar.min.js" type="text/javascript"></script>
    <script src="/public/libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js" type="text/javascript"></script>
    <script src="/public/libs/bootbox/js/bootbox.min.js" type="text/javascript"></script>
    <script src="/appdata/resources/ganaral_layouts/menubuilder/js/menumaker/nestedSortable/jquery.mjs.nestedSortable.js" type="text/javascript"></script>
    <script src="/appdata/resources/ganaral_layouts/menubuilder/js/menumaker/menu.js" type="text/javascript"></script>
    <script src="/appdata/resources/ganaral_layouts/menubuilder/js/main.js?v=2.5" type="text/javascript"></script>
            <!--Attach JS  Files-->
    <script>
        $(document).ready(function () {

            $('body').on('click', '.edit-class', function () {
                var id = $(this).attr('data-id');
                var token = $('#token').val();

                $.ajax({
                    url: "/admin/users/role/show-edit",
                    data: {id: id, _token: token},
                    dataType: 'json',
                    type: 'POST',
                    success: function (data) {
                        $('.roles-form').html(data.data);
                        $("#addnewform").collapse();
                    }
                });
            });


            $('.btnadd').click(function(){
                var token = $('#token').val();

                $.ajax({
                    url: "/admin/users/role/show-edit",
                    data: {_token: token},
                    dataType: 'json',
                    type: 'POST',
                    success: function (data) {
                        $('.roles-form').html(data.data);
                        $("#addnewform").collapse();
                    }
                });
            });

            $.fn.editable.defaults.mode = 'inline';

            $('.editable').editable({
                // url: '/admin/users/role/edit',
                params: function (params) {
                    params._token = $('#token').val();
                    return params;
                }
            });


            $('body').on('click', '.show-child-perm', function () {
                var permID = $(this).data('permid');
                var roleID = $(this).data('roleid');
                var currentID = $(this).data('current');
                var isChecked = 'no';
                var token = $('#token').val();
                var list = JSON.parse($('#permList').val());
                if ($(this).is(":checked")) {
                    isChecked = 'yes';
                }

                $.ajax({
                    url: "{!! url('admin/users/toggleChild') !!}",
                    data: {
                        permID: permID,
                        roleID: roleID,
                        currentID: currentID,
                        isChecked: isChecked,
                        permList: list,
                        _token: token
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 200) {
                            $('.permissions-list').html(data.data);
                        }
                    },
                    type: 'POST'
                });
            });
        });
        $('.del-role-icon').click(function () {
            var hiddenInput = $(this).parent().find('.del-row-id');
            var id = hiddenInput.val();

            $('#DelRoleModal').find('.hidden-input-del').val(id);
            $('#DelRoleModal').modal();
        });

        $('.submitRoleDel').click(function () {
            var id = $('#DelRoleModal').find('.hidden-input-del').val();
            $('#deleteRole' + id).submit();
        });

        function editRole(event) {
            $('.editRole').attr('action', $(event).attr('data-url'));
            $('.editRole').find('#edit-name').val($(event).attr('data-name'));
            $('#EditRoleModal').modal();
        }
    </script>
@stop

