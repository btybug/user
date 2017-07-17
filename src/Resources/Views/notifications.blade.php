@extends('layouts.admin')
@section('content')
    {!! Breadcrumbs::render('admin-notifications') !!}

    <div>
        <button id="delete_bulk" class="btn btn-default btn-sm btn-danger m-b-5" type="button"><i
                    class="fa fa-plus"></i>&nbsp; Delete Selected
        </button>
        <button id="mark_bulk" class="btn btn-default btn-sm btn-warning m-b-5" type="button"><i class="fa fa-plus"></i>&nbsp;
            Mark Read Selected
        </button>
        </a>

    </div>
    <div class="row">
        <div class="col-md-12 p-0">

            <table class="table table-bordered" id="tpl-table">
                <thead>
                <tr class="bg-black-darker text-white">
                    @foreach($form_fields as $fld)
                        <th @if($fld=='Action') width="15%" @endif>{!! $fld !!}</th>
                    @endforeach
                </tr>
                </thead>
            </table>
        </div>

    </div>

    </div>
@stop

@include('tools::common_inc')

@push('javascript')
<script>
    $(function () {
        $('#tpl-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/admin/account/notifications/data',
            dom: 'frtip',
            pageLength: '50',
            columns: {!! $columns!!}
        });
    });

    $(document).ready(function () {

        $("#delete_bulk").click(function () {
            var r = confirm("Are you sure to delete selected?")
            if (r == true) {
                deleteSelected('/admin/account/notifications/delete-bulk', '/admin/account/notifications');
            }
        });

        $("#mark_bulk").click(function () {
            vals = '';
            $('.del_select').each(function () {
                if ($(this).is(":checked")) {
                    vals += ',' + $(this).val();
                }
            });
            var afterDone = function () {
               location.reload();
            }
            postAjax('/admin/account/notifications/mark-read-bulk', {'vals': vals}, afterDone);

        });


    });
</script>
@endpush

