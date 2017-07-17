@if(!isset($role))
    <div class="form-group">
        {!! Form::label('name','Role Name',[])!!}
        {!! Form::input('text','name',isset($role->name) ? $role->name : '',['class'=>'form-control','placeholder'=>'Enter Role Name'])!!}
    </div>
@endif
<div class="form-group">
    {!! Form::label('slug','Display Name',[])!!}
    {!! Form::input('text','slug',isset($role->slug) ? $role->slug : '',['class'=>'form-control','placeholder'=>'Enter Role Name'])!!}
</div>
{!! Form::submit(isset($buttonText) ? $buttonText : 'Add Role',['class' => 'btn btn-primary']) !!}
