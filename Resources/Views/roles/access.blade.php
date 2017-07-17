@extends('layouts.admin')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/">Dashboard</a></li>
        <li class="active">All Users</li>
    </ol>
    <div class="row">
        <div class="p-10 bg-silver  overflow-y-hidden">
            <div class="tab-content m-10 overflow-y-hidden">
                <div class="row">

                    <!-- Item template used by JS -->
                    <script type="template" id="item-template">
                        <li data-details='[serialized_data]'>
                            <div class="drag-handle">
                                [title]
                                <div class="item-actions">
                                    <a href="javascript:;" data-action="addChild">
                                        <i class="fa fa-plus"></i> Add Child
                                    </a>
                                    <a href="{{URL()}}/admin/tools/page/update/[id]">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <a href="javascript:;" data-action="delete"><i class="fa fa-trash-o"></i> Remove</a>
                                    <a href="{{URL()}}/preview/[view_url]" target="_blank" class="view-url">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </div>
                            </div>
                            <ol></ol>
                        </li>
                    </script>
                    <!-- END Item template -->

                    <input type="hidden" id="baseUrl" value="{{URL()}}" />
                    <div class="col-md-7">
                        <div class="panel panel-default">
                            <div class="panel-heading bg-black-darker text-white">{!! $role->name !!} Permissions</div>
                            <div class="panel-body original-menu">
                                @include('backend::roles._partials.original_menu')
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 hide" id="page-details">
                        <div class="panel panel-default">
                            <div class="panel-heading bg-black-darker text-white">Additional Permissions</div>
                            <div class="panel-body form-horizontal">
                                <ul class="quick-view-list">

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('CSS')
    {!! HTML::style('/public/css/menu.css?v=0.9') !!}
    {!! HTML::style('/public/css/page.css?v=0.13') !!}
    <style>
        .item-actions{
            display: block;
        }
    </style>
@stop
@section('JS')
    {!! HTML::script('public/libs/bootbox/js/bootbox.min.js') !!}
    {!! HTML::script('public/libs/jquery.nestable/js/jquery.nestable.js') !!}

    {!! HTML::script('public/libs/jqueryui/js/jquery-ui.min.js') !!}
    {!! HTML::script('public/libs/nestedSortable/jquery.mjs.nestedSortable.js') !!}

    {!! HTML::script('public/js/page.js?v=0.62') !!}
    <script>
        $(function() {
            $('body').on('click','.edit-btn',function(){
                var permID = $(this).attr('data-perm');
                var roleID = $(this).attr('data-role');
                var token = $('#token').val();

                $.ajax({
                    url: "{!! url('admin/backend/roles-configuration/show-edit') !!}",
                    data: {
                        permID: permID,
                        roleID: roleID,
                        _token: token
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 200) {
                            $('.quick-view-list').html(data.data);
                            $('#page-details').removeClass('hide');
                            $('#page-details').show();
                        }
                    },
                    type: 'POST'
                });
            });


            $('body').on('click','.activate-item',function(){
                var permID = $(this).attr('data-perm');
                var roleID = $(this).attr('data-role');
                var child = $(this).attr('data-child');
                var token = $('#token').val();

                $.ajax({
                    url: "{!! url('admin/backend/roles-configuration/add-access') !!}",
                    data: {
                        permID: permID,
                        roleID: roleID,
                        child: child,
                        _token: token
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 200) {
                            $('.original-menu').html(data.data);
                            if(data.right_html){
                                $('.quick-view-list').html(data.right_html);
                                $('#page-details').removeClass('hide');
                                $('#page-details').show();
                            }else{
                                $('#page-details').removeClass('show');
                                $('#page-details').hide();
                            }
                        }
                    },
                    type: 'POST'
                });
            });

            $('body').on('click','.deactivate-item',function(){
                var permID = $(this).attr('data-perm');
                var roleID = $(this).attr('data-role');
                var child = $(this).attr('data-child');
                var token = $('#token').val();

                $.ajax({
                    url: "{!! url('admin/backend/roles-configuration/remove-access') !!}",
                    data: {
                        permID: permID,
                        roleID: roleID,
                        child: child,
                        _token: token
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 200) {
                            $('.original-menu').html(data.data);
                            if(data.right_html){
                                $('.quick-view-list').html(data.right_html);
                                $('#page-details').removeClass('hide');
                                $('#page-details').show();
                            }else{
                                $('#page-details').removeClass('show');
                                $('#page-details').hide();
                            }
                        }
                    },
                    type: 'POST'
                });
            });

            if (localStorage.activetoolspages) {
                $('[data-tab-action="tabs"]').find('a[href="' + localStorage.activetoolspages + '"]').click();

            } else {
                $('[data-tab-action="tabs"] li:first-child').find('a').click();
            }

            $('[data-tab-action="tabs"] a').click(function() {
                localStorage.activetoolspages = $(this).attr('href');
                thisname = $(this).data('name')
            });

        });
    </script>
@stop
