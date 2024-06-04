@extends('layouts.map_layout', ['page_title' => 'Tiang Savr'])


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
                border-right: 0px !important;
            } */

        textarea {
            border: 1px solid #999999 !important;
        }

        body::-webkit-scrollbar {
            display: none !important;
        }

        span.number {
            display: none
        }

        .navbar {
            display: none !important
        }
    </style>
@endsection


@section('content')
    <div class="bg-white ">

        <div class="container- m-2 bg-white">

            <div class=" ">

                <div class=" card row bg-white   ">
                    <div class=" ">
                        {{-- <h3 class="text-center p-2">{{ __('messages.qr_savr') }}</h3> --}}

                        {{-- <div class="row p-4">
                            <div class="col-md-4">
                                <label for="zone">QA Status</label>
                            </div>
                            <div class="col-md-4">



                                @if ($data->review_date != '' && $data->pole_image_1 != '')


                                <button type="button" class="btn  text-left form-control {{$data->qa_status == 'Accept' ? 'btn-success' :($data->qa_status == 'Reject' ? 'btn-danger' :'btn-primary') }} "
                                    data-toggle="dropdown">
                                    {{ $data->qa_status }}

                                </button>
                                <div class="dropdown-menu" role="menu">
                                    @if ($data->qa_status != 'Accept')
                                        <a href="/{{ app()->getLocale() }}/tiang-talian-vt-and-vr-update-QA-Status?status=Accept&&id={{ $data->id }}"
                                            onclick="return confirm('are you sure?')">
                                            <button type="button"
                                                class="dropdown-item pl-3 w-100 text-left">Accept</button>
                                        </a>
                                    @endif

                                    @if ($data->qa_status != 'Reject')
                                        <button type="button" class="btn btn-primary dropdown-item" data-id="{{$data->id }}"
                                            data-toggle="modal" data-target="#rejectReasonModal">
                                            Reject
                                        </button>
                                    @endif



                                </div>
                                @else
                                <button type="button" class="btn  text-left form-control" style="background: orange ; color:white"
                                >
                                <strong >Unsurveyed</strong>


                             </button>
                                @endif
                                {{-- <select name="qa_status" id="qa_status" class="form-control" ></select> -
                            </div>
                        </div> --}}

                        {{--  @if ($data->qa_status == 'Reject')
                            <div class="row px-4">
                                <div class="col-md-4">
                                    <label for="zone">Reason</label>
                                </div>
                                <div class="col-md-4">
                                    <textarea name="" id="" cols="10" rows="4" disabled class="form-control">{{$data->reject_remarks}}</textarea>
                                </div>
                            </div>
                        @endif --}}
                        {{-- </fieldset>   --}}
                        <form id="framework-wizard-form"
                            action="/{{ app()->getLocale() }}/tiang-talian-vt-and-vr-map-edit/{{ $data->id }}"
                            enctype="multipart/form-data" method="POST">

                            @csrf

                            <fieldset class="form-input">
                                <div class="row ">
                                    <div class="col-md-4"><label for="id">ID </label></div>
                                    <div class="col-md-4">
                                        <input type="text" value="{{ $data->id }}" disabled
                                            class="form-control disabled">
                                    </div>
                                </div>

                                <div class="row ">
                                    <div class="col-md-4"><label for="id">Created By </label></div>
                                    <div class="col-md-4">
                                        <input type="text" value="{{ $data->created_by }}" disabled
                                            class="form-control disabled">
                                    </div>
                                </div>
                            </fieldset>
                            @include('Tiang.partials.editForm', ['data' => $data])


                            <fieldset class="form-input">
                                <div class="row  ">
                                    <div class="col-md-4">
                                        <label for="zone">QA Status</label>
                                    </div>
                                    <div class="col-md-4">

                                        <select name="qa_status" id="qa_status" class="form-control"
                                            onchange="onChangeQa(this.value)">
                                            <option value="{{ $data->qa_status }}" hidden>{{ $data->qa_status }}</option>
                                            <option value="Accept">Accept</option>
                                            <option value="Reject">Reject</option>
                                        </select>
                                    </div>
                                </div>


                                <div class=" row {{ $data->qa_status != 'Reject' ? 'd-none' : '' }} " id="reject-reason">

                                    <div class="col-md-4">
                                        <label for="zone">Reason</label>
                                    </div>
                                    <div class="col-md-4">
                                        <textarea name="reject_remakrs" id="reject_remakrs" cols="10" rows="4" class="form-control">{{ $data->reject_remarks }}</textarea>
                                    </div>
                                </div>



                                <div class="text-center py-4">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="removeRecord({{ $data->id }})">Remove</button>
                                    <button class="btn btn-sm btn-success" type="submit"
                                        onclick="$('#framework-wizard-form').submit()">UPDATE</button>
                                </div>

                            </fieldset>
                        </form>


                    </div>
                </div>
            </div>

        </div>
    </div>
    <x-reject-modal />
@endsection

@section('script')
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    <script src="{{ URL::asset('assets/test/js/jquery.steps.js') }}"></script>


    <script>
        // Function to handle changes in QA status
        function onChangeQa(status) {
            if (status === 'Accept') {
                $('#reject-reason').addClass('d-none');
            } else if (status === 'Reject') {
                $('#reject-reason').removeClass('d-none');
            }
        }

        // Function to get geolocation
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                console.log("Geolocation is not supported by this browser.");
            }
        }

        // Function to show geolocation
        function showPosition(position) {
            $('#lat').val(position.coords.latitude);
            $('#log').val(position.coords.longitude);
        }

        // Function to remove a record
        function removeRecord(paramId) {
            var confrim = confirm('Are you sure?')
            if (confrim) {
                $.ajax({
                    url: `/{{ app()->getLocale() }}/remove-tiang-talian-vt-and-vr/${paramId}`,
                    dataType: 'JSON',
                    method: 'GET',
                    success: function(response) {
                        // Send message to parent window to close modal after successful removal
                        window.parent.postMessage('closeModal', '*');
                    }
                });
            }
        }

        $(document).ready(function() {
            // Event handler for checkboxes in defects section
            $('.defects input[type="checkbox"]').on('click', function() {
                addRemoveImageField(this);
            });

            // Event handler for checkboxes in high clearance section
            $('.high-clearance input[type="checkbox"]').on('click', function() {
                addRemoveImageHighClearanceField(this);
            });

            // Event handler for radio buttons with 'other' option
            $('.select-radio-value').on('change', function() {
                var val = this.value;
                var id = `${this.name}_input`;
                var input = $(`#${id}`);
                if (val === 'other') {
                    input.val('').removeClass('d-none');
                } else {
                    input.val(val).addClass('d-none');
                }
            });
        });

        var total_defects = parseInt({{ $data->total_defects }});

        // Function to add or remove image field based on checkbox state
        function addRemoveImageField(checkbox) {
            var element = $(checkbox);
            var id = element.attr('id');
            var input_val = $(`#${id}-input`);

            if (checkbox.checked) {
                input_val.removeClass('d-none');
                total_defects += 1;
            } else {
                input_val.addClass('d-none').val('');
                total_defects -= 1;
            }

            $('#total_defects').val(total_defects);
        }

        // Function to add or remove image field in high clearance section based on checkbox state
        function addRemoveImageHighClearanceField(checkbox) {
            var element = $(checkbox);
            var id = element.attr('id');
            var input_val = $(`#${id}-input`);

            if (checkbox.checked) {
                input_val.removeClass('d-none');
            } else {
                input_val.addClass('d-none').val('');
                var span = input_val.parent().find('label');
                if (span.length > 0) {
                    span.html('');
                }
                var span_val = $(`#${id}-input-error`);
                if (span_val.length > 0) {
                    span_val.html('');
                }
            }
        }

        // Function to show main line connection options
        function getMainLine(val) {
            if (val === 'service_line') {
                $('#main_line_connection').removeClass('d-none');
            } else {
                $('#main_line_connection').addClass('d-none');
                $('#main_line_connection_one, #main_line_connection_many').prop('checked', false);
            }
        }
    </script>
@endsection
