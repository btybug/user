@extends('btybug::layouts.admin')

@section('content')
    <div class="col-md-12">
        <legend>Form Settings
            {{--<button class="btn btn-info pull-right add-new"><i class="fa fa-plus"></i></button>--}}
        </legend>
        <div class="col-md-6">
        {!! Form::open(['class' => 'form-horizontal', 'id' => 'blog_settings_form']) !!}
        <!-- Form Name -->
        @include('users::_partials._default_user_form')
        {{--            @include('users::_partials.form_settings')--}}

        <!-- Button -->
            <div class="form-group">
                <div class="col-md-2"></div>
                <div class="col-md-10">
                    <button id="singlebutton" class="btn btn-primary pull-right">Save</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
        {{--<div id="blog_units" class="col-md-4 add-new-modal">--}}
        {{--<a class="btn btn-danger create-custom-field"><i class="fa fa-plus"></i> Add Custom Field</a>--}}
        {{--@if($blogUnits)--}}
        {{--@foreach($blogUnits as $unit)--}}
        {{--<div class="col-md-12 blog-unit">--}}
        {{--{!! BBRenderUnits($unit->variations()->first()->id) !!}--}}
        {{--</div>--}}
        {{--@endforeach--}}
        {{--@endif--}}
        {{--</div>--}}
    </div>

    @include('resources::assests.magicModal')
@stop
@section('CSS')
    <style>
        .add-new-modal {
            padding: 20px;
            height: 600px;
            border: 1px solid black;
            display: none;
        }

        .custom-field-drop-container {
            min-height: 100px;
            border: 1px dashed;
            padding: 15px;
            /*width: 65%;*/
            /*margin: 0 auto;*/
        }

        .blog-unit {
            margin-top: 15px;
        }
    </style>
    {!! HTML::style('public/js/select2/css/select2.min.css') !!}
@stop
@section('JS')
    {!! HTML::script('public/js/select2/js/select2.full.js') !!}
    {!! HTML::script("/resources/assets/js/UiElements/bb_styles.js?v.5") !!}
    <script>
        $(document).ready(function () {
            $('body').on('click', '.item', function () {
                var value = $(this).data('value');
                var $customFieldsCount = $('#blog_settings_form input[type="hidden"][data-type="custom-field-input"]').length;
                $customFieldsCount++;
                $.ajax({
                    url: '/admin/blogs/append-unit',
                    data: {
                        value: value,
                        order: $customFieldsCount
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $("input[name='_token']").val()
                    },
                    success: function (data) {
                        if (!data.error) {
                            var except = $(".select-meta-unit").data('except');
                            except.push(value);

                            $(".select-meta-unit").attr('data-except', JSON.stringify(except));
                            $('.custom-field-container').append(data.html);
                        }
                    },
                    type: 'POST'
                });
            });

            $("body").on('click', '.delete-field', function () {
                var key = $(this).data('del');
                $("[data-key=" + key + "]").remove();
            });

            $('body').on('click', '.delete-unit-field', function () {
                var except = $(".select-meta-unit").data('except');
                var value = $(this).data('slug');
                except.pop(value);
                $(".select-meta-unit").attr('data-except', JSON.stringify(except));

                $(this).parents('.custom-unit-wrapper').remove();
            });
            $('.add-new').on('click', function () {
                $('.add-new-modal').slideToggle();
            });

            $('.custom-field-values').select2({
                allowClear: false,
                tags: true
            });

            $('.select2-container--open .select2-dropdown--above').css("width", "100");
            $('.select2-selection--multiple').css("width", "100");


            var count = 1;
            $("body").on('click', '.create-custom-field', function () {
                var field = "";
                field += '<div class="col-md-12 m-t-10" data-key="' + count + '">' +
                    '<label class="col-md-2 control-label text-center" for="textarea">Custom Field</label>';
                field += '<div class="col-md-5 custom-field-wrapper">';
                field += '<div class="col-md-4" style="padding-left: 0!important;">' +
                    '<input name="custom[' + count + '][name]" type="text" class="form-control input-md" /></div>';
                field += '<div class="col-md-3">';
                field += '<select name="custom[' + count + '][type]" class="form-control input-md type-select" data-count="' + count + '"><option value="text" data-type="input">Input</option><option value="selectbox" data-type="select">Selectbox</option><option value="checkbox" data-type="select">Checkbox</option><option value="radio" data-type="select">Radio</option><option value="textarea" data-type="input">Textarea</option></select>';
                field += '</div>';
                field += '<div class="col-md-2"><a class="btn btn-danger pull-right delete-field" data-del="' + count + '"><i class="fa fa-trash"></i> </a> </div>';
                field += ' </div></div>';

                $(".custom-field-container").append(field);
                count++;
            });

            $('body').on('change', '.type-select', function () {
                var select = $(this);
                if ($('option:selected', this).data('type') == 'select') {
                    if ($(this).hasClass('existing-select')) {
                        $(this).parent().next().removeClass('hidden');
                    } else {
                        var customFieldsValues = $('<select/>', {
                                multiple: 'multiple',
                                class: 'form-control input-md custom-field-values',
                                name: 'custom[' + $(this).data('count') + '][values][]'
                            }),
                            customFieldsValuesContainer = $('<div/>', {
                                class: 'col-md-3 values-container'
                            });
                        customFieldsValues.appendTo(customFieldsValuesContainer);
                        $(this).parent().after(customFieldsValuesContainer);
                        customFieldsValues.select2({
                            allowClear: false,
                            tags: true
                        });
                    }
                } else {
                    if ($(this).hasClass('existing-select')) {
                        $(this).parent().next().addClass('hidden');
                    } else {
                        $(this).parent().next().remove();
                    }
                }
            });
            //-------------------------------------------------
            //Drag and drop units
            // There's the settings form and the units
            var $settingForm = $('.custom-field-drop-container'),
                $units = $('#blog_units');

            // Let the units be draggable
            $(".blog-unit", $units).draggable({
                cancel: ".panel-body", // clicking an icon won't initiate dragging
                revert: "invalid", // when not dropped, the item will revert back to its initial position
                containment: "document",
                helper: "clone",
                cursor: "move"
            });

            // Let the settings be droppable, accepting the units
            $settingForm.droppable({
//                accept: "#blog_units > .blog-unit",
                classes: {
                    "ui-droppable-active": "ui-state-highlight"
                },
                drop: function (event, ui) {
                    deleteUnit(ui.draggable);
                }
            });

            // Let the unit container be droppable as well, accepting items from the trash
            $units.droppable({
//                accept: ".custom-field-drop-container div",
                classes: {
                    "ui-droppable-active": "custom-state-active"
                },
                drop: function (event, ui) {
                    recycleUnit(ui.draggable);
                }
            });

            // Unit deletion function
            var recycle_icon = "<a href='link/to/recycle/script/when/we/have/js/off' title='Recycle this image' class='ui-icon ui-icon-refresh'>Recycle image</a>";

            function deleteUnit($item) {
                $item.fadeOut(function () {
//                    $item.find('label').addClass('ui-draggable ui-draggable-handle');
//                    $item.find('.unit-drop-part label').addClass('ui-draggable ui-draggable-handle');
                    var $customFieldsCount = $('#blog_settings_form input[type="hidden"][data-type="custom-field-input"]').length;
                    $customFieldsCount++;
                    var $hiddenInput = $('<input/>', {
                        name: 'custom[units][' + $customFieldsCount + ']',
                        'data-type': 'custom-field-input',
                        type: 'hidden',
                        value: $item.children('.unit-drop-part').data('slug')
                    });
                    $hiddenInput.appendTo($('#blog_settings_form'));
                    $item.appendTo($settingForm).fadeIn();
                });
            }

            // Unit recycle function
            var trash_icon = "<a href='link/to/trash/script/when/we/have/js/off' title='Delete this image' class='ui-icon ui-icon-trash'>Delete image</a>";

            function recycleUnit($item) {
                $('#blog_settings_form input[type="hidden"][data-type="custom-field-input"][value="' + $item.children('.unit-drop-part').data('slug') + '"]').remove();
                $item.fadeOut(function () {
                    $item.appendTo($units).fadeIn();
                });
            }

        });
    </script>
@stop