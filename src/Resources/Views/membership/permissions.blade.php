@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="p-10 bg-silver  overflow-y-hidden">
            <div class="tab-content m-10 overflow-y-hidden">
                <div class="row">
                    <!-- END Item template -->
                    <div class="col-md-7">
                        <div class="panel panel-default">
                            <div class="panel-heading bg-black-darker text-white">{!! $role->name !!} Permissions</div>
                            <div class="panel-body original-menu perm_list_box">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 right">
                                    <article>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div id="pages_front" class="panel_bd_styles tree-styles">
                                                @include('users::membership._partials.perm_list')
                                            </div>
                                        </div>
                                    </article>
                                </div>

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
@section("JS")
    <script>
        $('body').on('click', '.show-child-perm', function () {
            var permID = $(this).data('pageid');
            var roleID = $(this).data('roleid');
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
    {!! HTML::style('public/css/create_pages.css') !!}
    {!! HTML::style('public/css/page.css?v=0.15') !!}
    {!! HTML::style('public/css/admin_pages.css') !!}
@endpush
