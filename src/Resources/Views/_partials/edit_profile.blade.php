<div class="col-md-9 m-t-40">
    <div class="prfl-banner m-b-15">
        <div class="row m-0">
            <div class="col-xs-12 col-md-2 prfl-box">
                <img src="{!! BBGetUserAvatar($user->id) !!}" class="img-responsive img-circle bounceInDown animated"/>

                <h4>{!! BBGetUserName($user->id) !!}</h4>
                <p>{!! BBGetUserRole($user->id) !!}</p>
            </div>
            <!--col-md-4 -->
            <div class="col-xs-12 col-md-10 postn-rltv">
                <div class="profile-actions text-right">
                    <button class="btn btn-success btn-sm" type="button"><i class="fa fa-check"></i> Friends</button>
                    <button class="btn btn-primary btn-sm" type="button"><i class="fa fa-envelope"></i> Send Message
                    </button>
                    <button class="btn btn-primary btn-sm" type="button"><i class="fa fa-ellipsis-v"></i></button>
                </div>
            </div>
            <!--col-md-9 -->
        </div>
    </div>
</div>


<div class="col-md-3">
    {!! Form::model($user,array('route' => ['admin.users.editAdmins', $user->id], 'method' => 'POST','class' => 'form')) !!}
    {!! Form::button('save',['class' => 'btn btn-success save-edit']) !!}
            <!-- begin panel -->
    <div class="panel panel-default m-t-5" data-sortable-id="form-stuff-4">
        <div class="panel-heading bg-black-darker text-white">Publishing</div>
        <div class="panel-body">

            <fieldset>
                <div class="form-group">
                    <label class="col-md-4 m-t-5 control-label f-w-100">Status</label>
                    <div class="col-md-8 m-b-10 f-s-11">
                        {!! Form::radio('active', true ,($user->active) ? true : false )  !!} Active
                        {!! Form::radio('active', false, ($user->active) ? false : true )  !!} Inactive
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 m-t-5 control-label f-w-100">Role</label>
                    <div class="col-md-8 m-b-10">
                        {!! Form::select('role', $roles,($user->roleUser->role_id)?$user->roleUser->role_id:3 ,array('id'=>'role','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 m-t-5 control-label f-w-100">Email</label>
                    <div class="col-md-8 m-b-10">
                        {!! Form::email('email', ($user->email)? $user->email : Request::old('email'), array('class'=>'form-control', 'placeholder'=> 'Email Address')) !!}
                        @if($errors->has('email'))
                            {{ $errors->first('email') }}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 m-t-5 control-label f-w-100">Username</label>
                    <div class="col-md-8 m-b-10">
                        {!! Form::text('username', ($user->username)? $user->username : Request::old('username'), array('class'=>'form-control', 'placeholder'=> 'Username')) !!}
                        @if($errors->has('username'))
                            {{ $errors->first('username') }}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 m-b-12 p-10">
                        <a style="width: 100%" href="{!! url('admin/users/sendPassword/'.$user->id) !!}"
                           class="btn btn-info password-reminder"><i class="fa fa-undo"></i> &nbsp; Password
                            Reminder</a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 m-b-12 p-10">
                        <a style="width: 100%" href="javascript:void(0)" data-email="{{ $user->email }}"
                           class="btn btn-warning password-reset"><i class="fa fa-paper-plane"></i>&nbsp; Password Reset</a>
                    </div>
                </div>

            </fieldset>
            {!! Form::close() !!}
            <p class="f-s-12 m-b-0 m-t-5">Created on 12/15/2015 @ 8:00 AM</p>
        </div>
    </div>
</div>
<div class="clearfix p-10">
</div>


<ul class="nav nav-tabs grybg" role="tablist">
    <li role="presentation" class="active"><a href="#profileTab" aria-controls="profileTab" role="tab"
                                              data-toggle="tab">Profile</a></li>
    <li role="presentation"><a href="#login" aria-controls="login" role="tab" data-toggle="tab">Login</a></li>
</ul>

<div class="tab-content ">

    <div role="tabpanel" class="tab-pane fade in active p-15 bg-silver" id="profileTab">
        <div class="col-sm-7 p-10 bg-white">
            {{--{!! sc('getCoreForm',['id' => '2','raw_id' => $user->profile->id,'update' => true]) !!}--}}
        </div>
        <div class="clearfix"></div>
    </div>

    <div role="tabpanel" class="tab-pane fade in p-15 bg-silver" id="login">
        <div class="col-sm-7 p-10 bg-white">
            {{--{!! sc('getCoreForm',['id' => '18','raw_id' => $user->id,'update' => true]) !!}--}}
        </div>
        <div class="clearfix"></div>
    </div>

</div>
{!! Form::close() !!}
