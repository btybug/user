@extends('layouts.mTabs',['index'=>'role_membership'])
@section('tab')
    <div class="row">
        <div class="col-sm-12">
            <a class="btn btn-primary pull-right" href="{!! url('admin/users/conditions/create') !!}">Create New Condition</a>
        </div>

        <div class="col-sm-12">
            <div class="box-info full">
                <h2><strong>List All</strong> Conditions</h2>
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Confirm Email</td>
                            <td>
                                <a data-href="{!! url('/admin/users/conditions/delete') !!}"
                                    class="delete-button btn btn-danger"><i
                                            class="fa fa-trash-o f-s-14 "></i></a>
                                <a href="{!! url("admin/users/conditions/edit") !!}" class="btn btn-info edit-class">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Make Payment</td>
                            <td>
                                <a data-href="{!! url('/admin/users/conditions/delete') !!}"
                                   class="delete-button btn btn-danger"><i
                                            class="fa fa-trash-o f-s-14 "></i></a>
                                <a href="{!! url("admin/users/conditions/edit") !!}" class="btn btn-info edit-class">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('_partials.delete_modal')
@stop
