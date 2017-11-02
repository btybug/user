@extends('btybug::layouts.admin')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/">Dashboard</a></li>
        <li class="active">All Users</li>
    </ol>
    <div class="row">
        <div class="p-10 bg-silver  overflow-y-hidden col-md-6">
            <div class="tab-content m-10 overflow-y-hidden">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading bg-black-darker text-white">{!! $role->name !!} Permissions for
                            Backend
                        </div>
                        <div class="panel-body original-menu perm_list_box">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 right">
                                <article>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div id="pages_back" class="panel_bd_styles tree-styles">
                                            @include("users::roles._partials.perm_list")
                                        </div>
                                    </div>
                                </article>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 p-10 bg-silver  overflow-y-hidden">
            <div class="tab-content m-10 overflow-y-hidden">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading bg-black-darker text-white">{!! $role->name !!} Permissions for
                            Frontend
                        </div>
                        <div class="panel-body original-menu perm_list_box">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 right">
                                <article>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div id="pages_front" class="panel_bd_styles tree-styles">
                                            @include("users::roles._partials.front_perm_list")
                                        </div>
                                    </div>
                                </article>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
@section("JS")
    <script>
        $('body').on('click', '.show-child-perm', function () {
            var esi = $(this).data("pageid");
            var active = "#collapseOne" + esi;

            var permID = $(this).data('pageid');
            var roleID = $(this).data('roleid');
            var module = $(this).data('module');
            var isChecked = 'no';
            var token = $('[name=_token]').val();
            var pageType = $(this).data('page-type');
            if ($(this).is(":checked")) {
                isChecked = 'yes';
            }

            $.ajax({
                url: "{!! url('admin/users/roles/permissions/'.$slug) !!}",
                data: {
                    pageID: permID,
                    roleID: roleID,
                    isChecked: isChecked,
                    slug: module,
                    role_slug: "{!! $slug !!}",
                    _token: token,
                    page_type: pageType
                },
                dataType: 'json',
                success: function (data) {
                    $('#pages_' + pageType).html(data.html);
                },
                type: 'POST'
            });
        });
    </script>
@stop
@push('css')
    {!! HTML::style('css/create_pages.css') !!}
    {!! HTML::style('css/page.css?v=0.15') !!}
    {!! HTML::style('css/admin_pages.css') !!}
@endpush
