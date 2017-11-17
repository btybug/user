@extends('btybug::layouts.mTabs',['index'=>'role_membership'])
@section('tab')
    <div class="row">
        <div class="col-sm-12">
            <a class="btn btn-primary pull-right" href="{!! url('admin/users/statuses/create') !!}">Create New
                Status</a>
        </div>

        <div class="col-sm-12">
            <div class="box-info full">
                <h2><strong>List All</strong> Statuses</h2>
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($statuses)
                            @foreach($statuses as $status)
                                <tr>
                                    <td>{!! $status->name !!}</td>
                                    <td>
                                        <a href="{!! url("admin/users/statuses/edit", [$status->id]) !!}"
                                           class="btn btn-info edit-class">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if(!$status->is_core)
                                            <a data-href="{!! url('/admin/users/statuses/delete') !!}"
                                               data-key="{!! $status->id !!}" data-type="Status"
                                               class="delete-button btn btn-danger"><i
                                                        class="fa fa-trash-o f-s-14 "></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('btybug::_partials.delete_modal')
@stop
