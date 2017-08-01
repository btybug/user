@extends('cms::layouts.mTabs',['index'=>'role_membership'])
@section('tab')
    <div class="row">
        <div class="col-sm-12">
            <a class="btn btn-primary pull-right" href="{!! url('admin/users/roles/create') !!}">Create New Role</a>
        </div>

        <div class="col-sm-12">
            <div class="box-info full">
                <h2><strong>List All</strong> Roles</h2>
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Access to</th>
                            <th>Options</th>
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
                                <td>{!! $role->getAccessName() !!}</td>
                                <td>
                                    @if(!in_array($role->id, $defaultRoles))
                                        <a data-href="{!! url('/admin/users/roles/delete') !!}"
                                            data-key="{!! $role->id !!}" data-type="Role {{ $role->name }}" class="delete-button btn btn-danger"><i
                                                    class="fa fa-trash-o f-s-14 "></i></a>

                                        <a href="{!! url("admin/users/roles/edit",$role->id) !!}" class="btn btn-info edit-class">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{!! url('/admin/users/roles/permissions',$role->slug)!!}"
                                           class="btn btn-warning">
                                            <i class="fa fa-crop">Permissions</i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('cms::_partials.delete_modal')
@stop
