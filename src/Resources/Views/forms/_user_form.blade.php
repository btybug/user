<div class="form-group">
    {!! Form::label('Name') !!}
    {!! Form::text('name', null, array('id' => 'name', 'class'=>'form-control', 'placeholder'=>'Name')) !!}
    @if($errors->has('name'))
        {{ $errors->first('name') }}
    @endif
</div>
<div class="form-group">
    {!! Form::label('Username') !!}
    {!! Form::text('username', null, array('id' => 'username', 'class'=>'form-control', 'placeholder'=>'Username')) !!}
    @if($errors->has('username'))
        {{ $errors->first('username') }}
    @endif

</div>
<div class="form-group">
    {!! Form::label('Email address') !!}
    {!! Form::email('email', null, array('id' => 'email', 'class'=>'form-control', 'placeholder'=>'Email address')) !!}
    @if($errors->has('email'))
        {{ $errors->first('email') }}
    @endif
</div>
{!! Form::hidden('role',\App\Modules\Users\User::ROLE_USER) !!}
<div class="form-group">
    {!! Form::label('Select Group') !!}
    {!! Form::select('group', $groups,(isset($group))?$group:'empty',array('id'=>'role','class' => 'form-control')) !!}
    {{--<input name="role" type="text" class="form-control" id="role" placeholder="Role">--}}
</div>
<div class="form-group">
    {!! Form::label('Status') !!} &nbsp;
    {!! Form::radio('active',1)  !!} Active
    {!! Form::radio('active',0)  !!} Inactive
</div>
<input type="submit" class="btn btn-success pull-right m-l-10" value=" Add " />
<a href="{!! url('/admin/users')!!}" class="btn btn pull-right btn-primary"> Back </a>
