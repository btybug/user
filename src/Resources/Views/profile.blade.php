@extends('cms::layouts.admin')

@section('page_heading','Dashboard')
@section('content')

    <div class="container-fluid" style="background: url({!! BBGetUserCover($user->id) !!}) no-repeat center center;">
        <div class="row">
            <div class="col-sm-3 centr">

                <img src="{!! BBGetUserAvatar($user->id) !!}" class="img-circle">

            </div>
            <div class="col-sm-9">
                <div class="acc-dv">
                    <button class="btn btn-success btn-sm" type="button"><i class="fa fa-check"></i> Friends</button>
                    <button class="btn btn-primary btn-sm" type="button"><i class="fa fa-envelope"></i> Send Message</button>
                    <button class="btn btn-primary btn-sm" type="button"><i class="fa fa-ellipsis-v"></i></button>
                </div>
            </div>
        </div>

    </div>
    <div class="container-fluid">

        <div class="row m-t-40">
            <div class="col-sm-3 m-t-60 text-center">
                <h4>{!! BBGetUserName($user->id) !!}</h4>
                <p>{!! BBGetUserRole($user->id) !!}</p>
            </div>
            <div class="col-sm-9">
               {!! \Eventy::filter('toggle.tabs', ['user'=>$user,'page'=>'profile']) !!} 

                </div>
            </div>
        </div>




    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>


@stop
@section('CSS')
    <style>
        .banner-tp{ background:url({!! asset('public/img/profile.jpg') !!}) no-repeat center center;}

        .centr{ text-align:center !important;}
        .img-circle {
        border: 4px solid #f3f3f3;
        margin: 196px auto -80px auto;
        position: relative;
        width: 200px;
        border-radius: 50%;
        }

        .acc-dv{ margin-top:230px; float:right;}

        .m-t-60{ margin-top:60px;}
        .m-t-40{ margin-top:40px;}
        .p-10{ padding:10px;}
        .bg-silver{ background-color:#f6f6f6;}
    </style>
    @stop
