@extends('layouts.admin')
@section('content')

<div class="container-fluid">
    <div class="row m-t-40">
        <div class="col-sm-12">
            {!! \Eventy::filter('toggle.tabs', ['user'=>$user,'page'=>'account']) !!}
        </div>
    </div>
</div>

@stop
@section('JS')
    <script>
        $(document).ready(function(){
            var upl =  $("input[name='avatar']").val()
            if(upl != ''){
                $('.imagepreview.filehtml').append('<img src="'+upl.trim()+'" >');
            }
        });
    </script>
@stop
