@extends('layouts.app')

@section('css')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />

    <link rel="stylesheet" href="{{ URL::asset('assets/test/css/style.css') }}" />
    <style>
        input[type='checkbox'],
        input[type='radio'] {
            min-width: 16px !important;
            margin-right: 12px;
        }

        input[type='radio'] {
            border-radius: 50% !important;
        }

        .fw-400 {
            font-weight: 400 !important;
        }

        input[type='file'],
        table input {
            margin: 0px !important;
        }

        table label {
            font-size: 14px !important;
            font-weight: 400 !important;
            margin-left: 10px !important;
            margin-bottom: 0px !important
        }

        th {
            font-size: 14px !important;
        }

        th,
        td {
            padding: 6px 16px !important
        }

        table,
        input[type='file'] {
            width: 90% !important;
        }


        table input[type="file"] {
            font-size: 11px !important;
            height: 33px !important;
        }

        /* td.d-flex {
            border-bottom: 0px !important;
            border-left: 0px !important;
        } */

        textarea {
            border: 1px solid #999999 !important;
        }

        span.number {
            display: none
        }
    </style>
@endsection


@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row " style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{ __('messages.tiang') }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a
                                href="{{ route('tiang-talian-vt-and-vr.index', app()->getLocale()) }}">{{ __('messages.index') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('messages.edit') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class=" ">

        <div class="container ms-auto">

            <div class=" ">

                <div class=" card col-md-12 p-3 ">
                    <div class=" ">
                        <h3 class="text-center p-2">{{ __('messages.qr_savr') }}</h3>
                        <form id="framework-wizard-form"
                            action="/{{app()->getLocale()}}/tiang-talian-vt-and-vr/{{$data->id}}"
                            enctype="multipart/form-data" style="display: none" method="POST">
                            @method('PATCH')
                            @csrf
                        @include('Tiang.partials.editForm',['data'=>$data ])
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    <script src="{{ URL::asset('assets/test/js/jquery.steps.js') }}"></script>


    <script>
        var form = $("#framework-wizard-form").show();
        form
            .steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",

                onStepChanging: function(event, currentIndex, newIndex) {
                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex) {
                        return true;
                    }

                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                },




                onFinished: function(event, currentIndex) {
                    form.submit();
                },
                // autoHeight: true,
            })

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {

            $('#lat').val(position.coords.latitude)
            $('#log').val(position.coords.longitude)

        }

        $(document).ready(function() {

            $('.defects input[type="checkbox"]').on('click', function() {
                addReomveImageField(this)

            })

            $('.high-clearance input[type="checkbox"]').on('click', function() {
                addReomveImageHighClearanceField(this)

            })

            $('input[name="tiang_defect_current_leakage"]').on('change', function() {
                if (this.value == 'Yes') {
                    if ($('#arus_pada_tiang_amp_div').hasClass('d-none')) {
                        $('#arus_pada_tiang_amp_div').removeClass('d-none');
                    }
                } else {
                    if (!$('#arus_pada_tiang_amp_div').hasClass('d-none')) {
                        $('#arus_pada_tiang_amp_div').addClass('d-none');
                    }
                }
            })

            $('input[name="umbang_defect_current_leakage"]').on('change', function() {
                if (this.value == 'Yes') {
                    if ($('#arus_pada_umbgan_amp_div').hasClass('d-none')) {
                        $('#arus_pada_umbgan_amp_div').removeClass('d-none');
                    }
                } else {
                    if (!$('#arus_pada_umbgan_amp_div').hasClass('d-none')) {
                        $('#arus_pada_umbgan_amp_div').addClass('d-none');
                    }
                }
            })

            $('.select-radio-value').on('change',function(){
                var val = this.value;
                var id = `${this.name}_input`;
                var input = $(`#${id}`)
                if (val === 'other') {
                    input.val('');
                    input.removeClass('d-none');
                }else{
                    input.val(val);
                    if (!input.hasClass('d-none')) {
                        input.addClass('d-none')
                    }
                }
            });


        });

        var total_defects = parseInt({{ $data->total_defects }});

        function addReomveImageField(checkbox) {
            var element = $(checkbox);
            var id = element.attr('id');
            var input_val = $(`#${id}-input`)

            if (checkbox.checked) {
                if (input_val.hasClass('d-none')) {
                    input_val.removeClass('d-none');

                    total_defects += 1;
                }
            } else {

                if (!input_val.hasClass('d-none')) {
                    input_val.addClass('d-none');
                    input_val.val('');
                    total_defects -= 1;

                }
            }

            $('#total_defects').val(total_defects)

        }


        function addReomveImageHighClearanceField(checkbox) {
            var element = $(checkbox);
            var id = element.attr('id');
            var input_val = $(`#${id}-input`)

            if (checkbox.checked) {
                if (input_val.hasClass('d-none')) {
                    input_val.removeClass('d-none');
                }
            } else {

                if (!input_val.hasClass('d-none')) {

                    input_val.addClass('d-none');
                    input_val.val('');

                    var span = input.parent().find('label');
                    if (span.length > 0) {
                        span.html('')
                    }

                    var span_val = $(`#${id}-input-error`);
                    if (span_val.length > 0) {
                        span_val.html('')
                    }
                }

            }
        }

        function getMainLine(val){
            if (val == 'service_line') {
                $('#main_line_connection').removeClass('d-none')
            }else{
                if (!$('#main_line_connection').hasClass('d-none')) {
                $('#main_line_connection').addClass('d-none')
                $('#main_line_connection_one , #main_line_connection_many').prop('checked', false);

                }
            }
        }
    </script>
@endsection
