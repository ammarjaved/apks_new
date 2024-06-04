@extends('layouts.app')

@section('css')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />

    {{-- <link rel="stylesheet" href="{{ URL::asset('assets/test/css/style.css') }}" /> --}}
    <style>
        input[type='checkbox'],
        input[type='radio'] {
            min-width: 16px !important;
            margin-right: 12px;
        }

        .error {
            color: red;
        }

        label {
            margin-bottom: 0px !important;
            margin-top: 1rem;
        }

        input,
        select {
            /* color: black !important; */
            margin-bottom: 0px !important;
            margin-top: 1rem;
        }

        .adjust-height {
            height: 70px;
        }

        input[type='radio'] {
            border-radius: 50% !important;
        }

        .card {
            border: 0px
        }
    </style>
@endsection


@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row  " style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{ __('messages.notice_form') }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item text-lowercase"><a
                                href="{{ route('third-party-digging.index', app()->getLocale()) }}">{{ __('messages.index') }}</a>
                        </li>
                        <li class="breadcrumb-item  text-lowercase active">{{ __('messages.edit') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class=" ">

        <div class="container">

            <div class="form-input ">

                <div class=" card col-md-12 p-4 ">
                    <div class=" ">
                        <h3 class="text-center p-2"></h3>

                        <form action="{{ route('third-party-digging.update', [app()->getLocale(), $data->id]) }} "
                            id="myForm" method="POST" enctype="multipart/form-data">

                            @method('PATCH')
                            @csrf

                            <div class="row">
                                <div class="col-md-4"><label for="zone">{{ __('messages.zone') }}</label></div>
                                <div class="col-md-4">
                                    <input type="text" readonly id="zone" name="search_zone"
                                        value="{{ $data->zone }}" class="form-control">
                                    {{-- <select name="zone" id="search_zone" class="form-control" required
                                        onchange="getBa()">
                                        <option value="{{ $data->zone }}" hidden>{{ $data->zone }}</option>
                                        @if (Auth::user()->ba == '')
                                        <option value="W1">W1</option>
                                        <option value="B1">B1</option>
                                        <option value="B2">B2</option>
                                        <option value="B4">B4</option>
                                        @endif



                                    </select> --}}
                                </div>
                            </div>
                            <input type="hidden" name="zone" id="zone" value="{{ $data->zone }}">

                            <div class="row">
                                <div class="col-md-4"><label for="ba">{{ __('messages.ba') }}</label></div>
                                <div class="col-md-4">
                                    <input type="text" name="ba" id="ba" value="{{ $data->ba }}" readonly
                                        class="form-control">
                                    {{-- <select name="ba" id="ba" class="form-control" required onchange="getWp(this)">
                                        <option value="{{$data->ba}}" hidden>{{$data->ba}}</option>

                                    </select> --}}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="digging">{{ __('messages.digging') }}</label></div>
                                <div class="col-md-2">
                                    <input type="radio" name="digging" id="digging_yes" class="mt-0" value="yes"
                                        {{ $data->digging == 'yes' ? 'checked' : '' }}>
                                    <label for="digging_yes">Yes</label>

                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="digging" id="digging_no" class="mt-0" value="no"
                                        {{ $data->digging == 'no' ? 'checked' : '' }}>
                                    <label for="digging_no">No</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="notice">{{ __('messages.notice') }}</label></div>
                                <div class="col-md-2">
                                    <input type="radio" name="notice" id="notice_yes" class="mt-0" value="yes"
                                        {{ $data->notice == 'yes' ? 'checked' : '' }}>
                                    <label for="notice_yes">Yes</label>

                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="notice" id="notice_no" class="mt-0" value="no"
                                        {{ $data->notice == 'no' ? 'checked' : '' }}>
                                    <label for="notice_no">No</label>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="supervision">{{ __('messages.supervision') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="supervision"
                                        {{ $data->supervision == 'yes' ? 'checked' : '' }} id="supervision_yes"
                                        class="mt-0" value="yes">
                                    <label for="supervision_yes">Yes</label>

                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="supervision"
                                        {{ $data->supervision == 'no' ? 'checked' : '' }} id="supervision_no"
                                        class="mt-0" value="no">
                                    <label for="supervision_no">No</label>
                                </div>

                            </div>




                            <div class="row">
                                <div class="col-md-4"><label for="wp_name">{{ __('messages.wp_name') }}</label></div>
                                <div class="col-md-4">
                                    <input type="text" readonly name="wp_name" id="wp_name"
                                        value="{{ $data->wp_name }}" class="form-control">
                                    {{-- <select name="wp_name" id="wp_name" class="form-control" onchange="getWpId(this)" required>
                                        <option value="{{ $data->wp_name }}" hidden>{{ $data->wp_name }}</option>
                                        @foreach ($wp as $p)
                                            @if ($p->ba == $data->ba && $p->wp_status == 'approved')
                                                <option value="{{ $p->package_name }}">{{ $p->package_name }}</option>
                                            @endif
                                        @endforeach

                                    </select> --}}
                                    <input type="hidden" name="workpackage_id" id="workpackage_id"
                                        value="{{ $data->workpackage_id }}">
                                </div>
                            </div>




                            <div class="row">
                                <div class="col-md-4"><label for="survey_date">{{ __('messages.survey_date') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" name="survey_date" id="survey_date"
                                        value="{{ date('Y-m-d', strtotime($data->survey_date)) }}" class="form-control"
                                        required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="patrolling_time">{{ __('messages.patrolling_time') }}</label></div>
                                <div class="col-md-4">
                                    <input type="time" name="patrolling_time" id="patrolling_time"
                                        value="{{ date('H:i:s', strtotime($data->patrolling_time)) }}"
                                        class="form-control" required>
                                </div>
                            </div>








                            <div class="row">
                                <div class="col-md-4"><label for="company_name">{{ __('messages.company_name') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="company_name" id="company_name"
                                        value="{{ $data->company_name }}" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="office_phone_no">{{ __('messages.office_phone_no') }}</label></div>
                                <div class="col-md-4">
                                    <input type="number" name="office_phone_no" id="office_phone_no"
                                        class="form-control" required minlength="9"value="{{ $data->office_phone_no }}"
                                        maxlength="11">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="main_contractor">{{ __('messages.main_contractor') }}</label></div>
                                <div class="col-md-4">
                                    <input type="text" name="main_contractor" value="{{ $data->main_contractor }}"
                                        id="main_contractor" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="developer_phone_no">{{ __('messages.developer_phone_no') }}</label></div>
                                <div class="col-md-4">
                                    <input type="number" name="developer_phone_no" id="developer_phone_no"
                                        class="form-control" required minlength="9"
                                        value="{{ $data->developer_phone_no }}" maxlength="11">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="contractor_company_name">{{ __('messages.contractort_company_name') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="contractor_company_name"
                                        value="{{ $data->contractor_company_name }}" id="contractor_company_name"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="site_supervisor_name">{{ __('messages.site_supervisor_name') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="site_supervisor_name" id="site_supervisor_name"
                                        value="{{ $data->site_supervisor_name }}" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label
                                        for="site_supervisor_phone_no">{{ __('messages.site_supervisor_phone_no') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" name="site_supervisor_phone_no"
                                        value="{{ $data->site_supervisor_phone_no }}" id="site_supervisor_phone_no"
                                        class="form-control" required minlength="9" maxlength="11">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label
                                        for="excavator_operator_name">{{ __('messages.excavator_operator_name') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="excavator_operator_name"
                                        value="{{ $data->excavator_operator_name }}" id="excavator_operator_name"
                                        class="form-control" required>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-4"><label
                                        for="excavator_machinery_reg_no">{{ __('messages.excavator_machinery_reg_no') }}</label>
                                </div>
                                <div class="col-md-4"><input type="text" name="excavator_machinery_reg_no"
                                        value="{{ $data->excavator_machinery_reg_no }}" id="excavator_machinery_reg_no"
                                        class="form-control" required></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="department_diging">{{ __('messages.dpt_diging') }}</label></div>
                                <div class="col-md-4"><input type="text" name="department_diging"
                                        value="{{ $data->department_diging }}" id="department_diging"
                                        class="form-control" required></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="survey_status">{{ __('messages.survey_status') }}</label></div>
                                <div class="col-md-4">
                                    <select name="survey_status" id="survey_status" class="form-control" required>
                                        <option value="{{ $data->survey_status }}" hidden>{{ $data->survey_status }}
                                        </option>
                                        <option value="Inprogress">Inprogress</option>
                                        <option value="Complete">Complete</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="road_name">{{ __('messages.rd_name') }}</label></div>
                                <div class="col-md-4"><input type="text" name="road_name"
                                        value="{{ $data->road_name }}" id="road_name" class="form-control" required
                                        readonly></div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-md-4"><label for="third-party-image-1">{{__("messages.third_party_image")}} 1</label></div>
                                <div class="col-md-4"><input type="file" name="third_party_image_1" id="third-party-image-1"
                                        class="form-control" ></div>
                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->third_party_image_1)) && $data->third_party_image_1 != '')
                                        <a href="{{ URL::asset($data->third_party_image_1) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->third_party_image_1) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                                @else
                                                <strong>{{__('messages.no_image_found')}}</strong>
                                    @endif
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="third-party-image-2">{{__("messages.third_party_image")}} 2</label></div>
                                <div class="col-md-4"><input type="file" name="third_party_image_1" id="third-party-image-2"
                                        class="form-control" ></div>
                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->third_party_image_2)) && $data->third_party_image_2 != '')
                                        <a href="{{ URL::asset($data->third_party_image_2) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->third_party_image_2) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                                @else
                                                <strong>{{__('messages.no_image_found')}}</strong>
                                    @endif
                                </div>

                            </div> --}}
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="before_image1">{{ __('messages.before_image_1') }}</label></div>
                                <div class="col-md-4"><input type="file" name="before_image1" id="before_image1"
                                        class="form-control"></div>
                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->before_image1)) && $data->before_image1 != '')
                                        <a href="{{ URL::asset($data->before_image1) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->before_image1) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                    @else
                                        <strong>{{ __('messages.no_image_found') }}</strong>
                                    @endif
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="before_image2">{{ __('messages.before_image_2') }}</label></div>
                                <div class="col-md-4"><input type="file" name="before_image2" id="before_image2"
                                        class="form-control"></div>

                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->before_image2)) && $data->before_image2 != '')
                                        <a href="{{ URL::asset($data->before_image2) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->before_image2) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                    @else
                                        <strong>{{ __('messages.no_image_found') }}</strong>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="before_image3">{{ __('messages.before_image_3') }}</label></div>
                                <div class="col-md-4"><input type="file" name="before_image3" id="before_image3"
                                        class="form-control"></div>

                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->before_image3)) && $data->before_image3 != '')
                                        <a href="{{ URL::asset($data->before_image3) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->before_image3) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                    @else
                                        <strong>{{ __('messages.no_image_found') }}</strong>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="during_image1">{{ __('messages.during_image_1') }}</label></div>
                                <div class="col-md-4"><input type="file" name="during_image1" id="during_image1"
                                        class="form-control"></div>

                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->during_image1)) && $data->during_image1 != '')
                                        <a href="{{ URL::asset($data->during_image1) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->during_image1) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                    @else
                                        <strong>{{ __('messages.no_image_found') }}</strong>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="during_image2">{{ __('messages.during_image_2') }}</label></div>
                                <div class="col-md-4"><input type="file" name="during_image2" id="during_image2"
                                        class="form-control"></div>

                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->during_image2)) && $data->during_image2 != '')
                                        <a href="{{ URL::asset($data->during_image2) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->during_image2) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                    @else
                                        <strong>{{ __('messages.no_image_found') }}</strong>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="during_image3">{{ __('messages.during_image_3') }}</label></div>
                                <div class="col-md-4"><input type="file" name="during_image3" id="during_image3"
                                        class="form-control"></div>

                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->during_image3)) && $data->during_image3 != '')
                                        <a href="{{ URL::asset($data->during_image3) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->during_image3) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                    @else
                                        <strong>{{ __('messages.no_image_found') }}</strong>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="after_image1">{{ __('messages.after_image_1') }}</label></div>
                                <div class="col-md-4"><input type="file" name="after_image1" id="after_image1"
                                        class="form-control"></div>

                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->after_image1)) && $data->after_image1 != '')
                                        <a href="{{ URL::asset($data->after_image1) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->after_image1) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                    @else
                                        <strong>{{ __('messages.no_image_found') }}</strong>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="after_image2">{{ __('messages.after_image_2') }}</label></div>
                                <div class="col-md-4"><input type="file" name="after_image2" id="after_image2"
                                        class="form-control"></div>

                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->after_image2)) && $data->after_image2 != '')
                                        <a href="{{ URL::asset($data->after_image2) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->after_image2) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                    @else
                                        <strong>{{ __('messages.no_image_found') }}</strong>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="after_image3">{{ __('messages.after_image_3') }}</label></div>
                                <div class="col-md-4"><input type="file" name="after_image3" id="after_image3"
                                        class="form-control"></div>

                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->after_image3)) && $data->after_image3 != '')
                                        <a href="{{ URL::asset($data->after_image3) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->after_image3) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                    @else
                                        <strong>{{ __('messages.no_image_found') }}</strong>
                                    @endif
                                </div>
                            </div>





                            <div class="text-center p-4"><button
                                    class="btn btn-sm btn-success">{{ __('messages.update') }}</button></div>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    <script>
        const userBa = "{{ Auth::user()->ba }}";
        $(document).ready(function() {


            $("#myForm").validate();
            if (userBa == '') {
                getBa();
            }


        });

        function getBa() {
            const selectedValue = $('#search_zone').val()
            const zone = "{{ $data->zone }}";
            const areaSelect = $('#ba');
            var baValues = '';
            const ba = "{{ $data->ba }}";
            // Clear previous options
            areaSelect.empty();
            if (selectedValue === zone) {
                areaSelect.append(`<option value="${ba}" hidden>${ba}</option>`)
            } else {
                areaSelect.append(`<option value="" hidden>select ba</option>`)
                $('#wp_name').empty();
                $('#search_wp').append(`<option value="" hidden>select wp</option>`);
            }


            if (selectedValue === 'W1') {
                baValues = ['KUALA LUMPUR PUSAT'];

            } else if (selectedValue === 'B1') {
                baValues = ['PETALING JAYA', 'RAWANG', 'KUALA SELANGOR'];
            } else if (selectedValue === 'B2') {
                baValues = ['KLANG', 'PELABUHAN KLANG'];


            } else if (selectedValue === 'B4') {
                baValues = ['CHERAS', 'BANTING', 'BANGI', 'PUTRAJAYA & CYBERJAYA'];
            }


            baValues.forEach((data) => {
                areaSelect.append(`<option value="${data}">${data}</option>`);
            });

        }


        function getWp(event) {
            var wp = @json($wp);

            const wpSelect = $('#wp_name');
            wpSelect.empty();
            wpSelect.append(`<option value="" hidden>select wp</option>`)
            wp.forEach((data) => {
                if (event.value == data.ba) {
                    wpSelect.append(`<option value="${data.package_name}">${data.package_name}</option>`);
                }
            });

        }




        function getWpId(event) {
            var wp = @json($wp);

            wp.forEach((data) => {
                if (event.value == data.package_name) {
                    $('#workpackage_id').val(data.id)
                }
            });


        }




        //get current location

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
    </script>
@endsection
