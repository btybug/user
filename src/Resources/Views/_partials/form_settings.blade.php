<div class="form-group custom-field-container">
    @if(isset($formSettings) && $formSettings && count($formSettings))
        @foreach($formSettings as $key => $setting)
            @if($key != 'units')
                <div class="row" data-key="{{ $key }}">
                    <label class="col-md-2 control-label text-center" for="textarea">{{ $setting['label'] }}</label>
                    <div class="col-md-8 custom-field-wrapper">
                        <div class="col-md-4" style="padding-left: 0!important;">
                            <input name="custom[{{$key}}][name]"  type="text" value="{!! $setting['name'] !!}" class="form-control input-md" /></div>

                        <div class="col-md-3">
                            {{--<select name="custom[{!! $key !!}][type]" class="form-control input-md type-select existing-select" data-count="{!! $key !!}">--}}
                            {{--@foreach($types as $dataType => $type)--}}
                            {{--<option @if($dataType == @$setting['type']){!! 'selected' !!}@endif value="{!! $dataType !!}" data-type="{!! $blog->getOptionDataType($dataType) !!}">{!! $type !!}</option>--}}
                            {{--@endforeach--}}
                            {{--</select>--}}
                        </div>
                        @if(isset($setting['values']))
                            <div class="col-md-3 values-container">
                                <select name='custom[{!! $key !!}][values][]' class="col-md-3 input-md custom-field-values" multiple>
                                    @foreach($setting['values'] as $value)
                                        <option value="{!! $value !!}" selected>{!! $value !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-md-2 text-right" style="padding-right: 0!important;"><a class="btn btn-danger delete-field" data-del="{!! $key !!}"><i class="fa fa-trash"></i> </a> </div>

                    </div>

                </div>
            @elseif($key == 'units')
                @foreach($setting as $order => $unitSlug)
                    @if($unitSlug)
                        <div class="row custom-unit-wrapper" >
                            {!! Form::hidden('customs[units]['. $order .']', $unitSlug,['data-type' => 'custom-field-input']) !!}
                            {!! BBRenderUnits($unitSlug,["slug" => $unitSlug],["slug" => $unitSlug, 'user_id' => $user_id]) !!}
                            @if(\Request::route()->getUri() == 'admin/users/settings')
                            <div class="col-md-2"><a class="btn btn-danger pull-right delete-unit-field" data-slug="{!! $unitSlug !!}">
                                    <i class="fa fa-trash"></i> </a>
                            </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif

</div>
<div class="col-md-2"></div>
{!! Form::hidden('form_setting_id', '58e21be5a8bd8')!!}