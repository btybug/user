{!! Form::open(array('route' => ['profile.changePassword'], 'method' => 'POST','class' => 'form')) !!}
<div class="form-group">
    {!! Form::label('Email address') !!}
    {!! Form::email('email', ($user->email)? $user->email : Request::old('email'), array('id' => 'email', 'class'=>'form-control', 'placeholder'=>'Email address')) !!}
    @if($errors->has('email'))
        {{ $errors->first('email') }}
    @endif
</div>
<div class="form-group">
    {!! Form::label('Current Password') !!}
    {!! Form::password('current_password', array('id' => 'current_password', 'class'=>'form-control', 'placeholder'=>'Current Password')) !!}
    @if($errors->has('current_password'))
        {{ $errors->first('current_password') }}
    @endif
</div>
<div class="form-group">
    {!! Form::label('Password') !!}
    {!! Form::password('password', array('id' => 'password', 'class'=>'form-control', 'placeholder'=>'Password')) !!}
    @if($errors->has('password'))
        {{ $errors->first('password') }}
    @endif
</div>
<div class="form-group">
    {!! Form::label('Confirm password') !!}
    {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class'=>'form-control', 'placeholder'=>'Confirm password')) !!}
</div>
<input type="submit" class="btn btn-success pull-left m-b-10" value="Change" />
{!! Form::close() !!}
