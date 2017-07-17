@extends('layouts.mTabs',['index'=>'role_membership'])
@section('tab')

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
                        {{--@include('backend::roles.forms._new_role_form')--}}
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
                                <img src="{!! $role->icon !!}" width="50"/>
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
                                {{--'route' => 'admin.backend.role.destroy',needfix --}}
                                {!! Form::open(['id'=> 'deleteRole'.$role->id,'method' => 'DELETE',
                                'class' => 'form-inline', 'style'=>'margin-bottom:-19px; padding:0' ]) !!}
                                <input type="hidden" value="{{ $role->id }}" name="id" class="del-row-id"/>
                                <span class="btn btn-danger del-role-icon" style=""><i class="fa fa-trash-o"></i></span>
                                {!! Form::close() !!}
                            </div>
                            <button data-id="{!! $role->id !!}"
                                    class="btn btn-info edit-class"
                                    data-action="addnew">
                                <i class="fa fa-edit"></i>
                            </button>
                            <a href="{!! url('/admin/users/roles-configuration/access',$role->id)!!}"
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
@stop
<!--JS-->
@section('JS')
    <!--Attach JS Files-->
    {!! HTML::script('/public/libs/bootstrap-editable/js/bootstrap-editable.min.js') !!}
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


            $('.btnadd').click(function () {
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

