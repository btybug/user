@extends('btybug::layouts.mTabs',['index'=>'users'])
@section('parag')

    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-user"></i> Admin Role</li>
    </ol>
@stop
@section('tab')
    <div class="row">
        <div class="col-md-12 p-10">
            <div class="text-right">
                <button class="btn btn-warning btnadd" data-action="addnew" data-toggle="collapse"
                        data-target="#addnewform"><i class="fa fa-plus"></i> New Group
                </button>
            </div>

            <div id="edit_class_id">

            </div>
            <div id="addnewform" class="addnewitems collapse" aria-expanded="true">
                <div class="panel panel-default" style="margin-top: 10px;">
                    <div class="panel-heading  bg-black-darker text-white">Group</div>
                    <div class="panel-body">
                        @include('users::forms._new_group_form')
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
                <th width="15%">Title</th>
                <th width="15%">Slug</th>
                <th>Description</th>
                <th width="200">Action</th>
            </tr>
            </thead>
            <tbody>

            @foreach($groups as $group)
                <tr>
                    <td>
                        <div style="max-width: 100px;overflow: hidden;height: 50px;">
                            @if($group->icon)
                                <img src="{!! $group->icon !!}" width="50"/>
                            @else
                                No Icon
                            @endif
                        </div>
                    </td>
                    <td><a data-pk="{{$group->id}}" style="cursor: pointer;"
                           class="editable editable-click">{!! $group->title !!}</a>
                    </td>
                    <td>{!! $group->slug !!}</td>
                    <td>{!! $group->description !!}</td>
                    <td>
                        <a href="{!! url('/admin/themes/classes/delete',$group->id)!!}" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                        <button data-id="{!! $group->id !!}"
                                data-title="{!! $group->title !!}"
                                data-slug="{!! $group->slug !!}"
                                data-icon="{!! $group->icon !!}"
                                data-description="{!! $group->description !!}"
                                class="btn btn-info edit-class"
                                data-action="addnew" data-toggle="collapse"
                                data-target="#addnewform"
                        >
                            <i class="fa fa-edit"></i>
                        </button>

                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>

@stop
@section('CSS')
    {!! HTML::style('/public/libs/bootstrap-editable/css/bootstrap-editable.css') !!}
@stop
@section('JS')
    {!! HTML::script('/public/libs/bootstrap-editable/js/bootstrap-editable.min.js') !!}
    <script>

        $('document').ready(function () {
            $.fn.editable.defaults.mode = 'inline';
            $('.editable').editable({
                url: '/admin/themes/classes/edit',
                params: function (params) {
                    params._token = $('#token').val();
                    return params;
                },
                send: 'always',
                ajaxOptions: {
                    dataType: 'html'
                }, success: function (response, newValue) {
                    response = JSON.parse(response);
                    if (response.result == false) return response.msg; //msg will be shown in editable form
                }
            });

//    $('.new-text-style').click(function(){
//        $('#AddNewClass').modal();
//    });
            $('.edit-class').on('click', function () {
                var id = $(this).attr('data-id');
                var title = $(this).attr('data-title');
                var slug = $(this).attr('data-slug');
                var description = $(this).attr('data-description');
                var icon = $(this).attr('data-icon');

                $('#edit_class_id').empty();
                $('#title').val(title);
                $('#slug').val(slug);
                $('#description').text(description);

                if (icon != '') {
                    $('<img>').attr({
                        src: icon,
                        id: 'role-icon'
                    }).appendTo('.imagepreview')
                } else {
                    $('#role-icon').remove();
                }

                $('form').append($('<input/>', {
                    type: 'hidden',
                    name: 'id',
                    value: id
                }));

            });

            $('.btnadd').click(function () {
                $('#hidden-id').remove();
                $('#role-icon').remove();
                $('#description').text('');
                $('form')[0].reset();
            });
        });
    </script>
@stop
