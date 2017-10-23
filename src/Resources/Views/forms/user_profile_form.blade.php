{!! Form::open(array('route' => ['profile.update'], 'method' => 'POST','class' => 'form','files' => true)) !!}

<input type="hidden" name="category" value="{!! $form !!}"/>
@if(count($fields) > 0)
    @foreach($fields as $field)
        <div class="form-group">
            <?php $f = (string)$field->field; ?>
            {!! Form::label(ucfirst($field->field)) !!}
            @if($field->type == 'text')
                {!! Form::text($field->field, ($user->$f)? $user->$f : Request::old($field->field), array('id' => $field->field, 'class'=>'form-control', 'placeholder'=> ucfirst($field->field))) !!}
            @endif
            @if($field->type == 'file')
                {!! Form::file($field->field, array('id' => $field->field, 'class'=>'form-control')) !!}
            @endif

            @if($errors->has($field->field))
                {{ $errors->first($field->field) }}
            @endif
        </div>
    @endforeach
    <input type="submit" class="btn btn-success pull-left m-b-10 m-r-10" value="Update"/>
@endif

<a href="{!! url('/')!!}" class="btn btn-primary pull-left m-b-10"> Back </a>

{!! Form::close() !!}
