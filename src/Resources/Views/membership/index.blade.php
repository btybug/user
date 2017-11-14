@extends('cms::layouts.mTabs',['index'=>'role_membership'])
@section('tab')
    <div class="row">
        <div class="col-sm-12">
            <a class="btn btn-primary pull-right" href="{!! route('admin.users.membership.getCreate') !!}">Create New
                Memberships</a>
        </div>
        <div class="col-sm-12">
            <div class="box-info full">
                <h2><strong>List All</strong> Memberships</h2>
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Slug</th>
                            <th>Special</th>
                            <th>Default</th>
                            <th>Approval</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($memberships as $membership)
                            <tr>
                                <td>
                                    <div style="max-width: 100px;overflow: hidden;height: 50px;">
                                        @if($membership->icon)
                                            <img src="{!! $membership->icon !!}" width="50"/>
                                        @else
                                            No Icon
                                        @endif
                                    </div>
                                </td>
                                <td>{!! $membership->name !!}</td>
                                <td>{!! $membership->description !!}</td>
                                <td>{!! $membership->slug !!}</td>
                                <td>{!! $membership->special !!}</td>
                                <td>{!! 'Default' !!}</td>
                                <td>{!! 'Approval' !!}</td>
                                <td>
                                    <a href="{!! url("admin/users/memberships/edit", $membership->slug) !!}"
                                       class="btn btn-info edit-class">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a data-href="{!! url('/admin/users/memberships/delete') !!}"
                                       data-key="{!! $membership->id !!}" data-type="Membership {{ $membership->name }}"
                                       class="delete-button btn btn-danger"><i
                                                class="fa fa-trash-o f-s-14 "></i></a>

                                    <a href="{!! url('/admin/users/memberships/permissions',$membership->slug)!!}"
                                       class="btn btn-success">
                                        <i class="fa fa-crop">Permissions</i>
                                    </a>

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
