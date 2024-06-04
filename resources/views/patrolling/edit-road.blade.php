@extends('layouts.app')

@section('css')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />





    @include('partials.map-css')
    <style>
        .error {
            color: red;
        }

        label {
            margin-bottom: 0px !important;
            margin-top: 1rem;
        }

        input,
        select {
            color: black !important;
            margin-bottom: 0px !important;
            margin-top: 1rem;
        }

        #map {
            margin: 30px;
            height: 400px;
            padding: 20px;
        }

        /* CSS for the Select2 dropdown to match form-control style */
        .select2-container {
            margin-top: 10px;
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
            font-size: 16px;
            line-height: 1.5;
            border: 1px solid #00000063;
            ;
            border-radius: 0;
        }

        /* Optionally, style the focus state */
        .select2-container .select2-selection--single:focus {
            border-color: 1px solid #00000063;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>
@endsection


@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row  " style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{ __('messages.Patrolling') }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="/get-all-work-packages">{{ __('messages.index') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.create') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class=" ">

        <div class="container">

            <div class=" ">

                <div class=" card col-md-12 p-4 ">
                    <div class=" ">
                        <h3 class="text-center p-2"></h3>

                        <form action="/{{ app()->getLocale() }}/patrolling-update" id="myForm" method="POST"
                            enctype="multipart/form-data" onsubmit="return submitFoam()">
                            @csrf

                            <div class="row">
                                <div class="col-md-4"><label for="zone">{{ __('messages.zone') }}</label></div>
                                <div class="col-md-4">
                                    <select name="zone" id="search_zone" class="form-control" required>

                                        @if (Auth::user()->zone != '')
                                            <option value="{{ Auth::user()->zone }}" hidden>{{ Auth::user()->zone }}
                                            </option>
                                        @else
                                            <option value="" hidden>select zone</option>
                                            <option value="W1">W1</option>
                                            <option value="B1">B1</option>
                                            <option value="B2">B2</option>
                                            <option value="B4">B4</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="ba">{{ __('messages.ba') }}</label></div>
                                <div class="col-md-4"><select name="ba" id="ba" class="form-control" required
                                        onchange="getWorkPackage(this.value)">
                                        @if (Auth::user()->ba != '')
                                            <option value="{{ Auth::user()->ba }}" hidden>{{ Auth::user()->ba }}</option>
                                        @else
                                            <option value="" hidden>select BA</option>
                                        @endif



                                    </select>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="search_wp">{{ __('messages.Work_Package_Name') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <select name="search_wp" id="search_wp" class="form-control" required>
                                        <option value="" hidden>select work package</option>

                                    </select>
                                    <input type="hidden" name="workpackage_id" id="workpackage_id" class="form-control">
                                    <input type="hidden" name="wp_name" id="wp_name">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="road_select">{{ __('messages.Select_Road') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <select name="road_select" id="road_select" class="form-control" required>
                                        <option value="" hidden>select road</option>

                                    </select>

                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-4"><label for="road_name">{{ __('messages.Road_Name') }}</label></div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="road_name" id="road_name" required>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-4"><label for="date_patrol">{{ __('messages.Patrolling_Date') }}</label>
                                </div>
                                <div class="col-md-4"><input type="date" name="date_patrol" id="date_patrol"
                                        class="form-control" required></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="time_petrol">{{ __('messages.patrolling_time') }}</label>
                                </div>
                                <div class="col-md-4"><input type="time" name="time_petrol" id="time_petrol"
                                        class="form-control" required></div>
                            </div>

                            <input type="hidden" name="road_id" id="road_id" class="form-control">


                            <div class="row">
                                <div class="col-md-4"><label for="fidar">{{ __('messages.Feeder') }}</label></div>
                                <div class="col-md-4"><input type="text" name="fidar" id="fidar"
                                        class="form-control" required></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="name_project">{{ __('messages.Project_Name') }}</label>
                                </div>
                                <div class="col-md-4"><input type="text" name="name_project" id="name_project"
                                        class="form-control" required></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="km">{{ __('messages.Km_Plan') }}</label></div>
                                <div class="col-md-4"><input type="number" name="km" id="km"
                                        class="form-control" required></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="actual_km">{{ __('messages.km_actual') }}</label></div>
                                <div class="col-md-4"><input type="number" name="actual_km" id="actual_km"
                                        class="form-control" required></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="digging">{{ __('messages.Total_Digging') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="total_digging" id="total_digging"
                                        required>


                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="notice">{{ __('messages.Total_Notice') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="total_notice" id="total_notice"
                                        required>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="supervision">{{ __('messages.Total_Supervision') }}</label></div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="total_supervision"
                                        id="total_supervision" required>

                                </div>
                            </div>







                            <div class="text-center">
                                <strong> <span class="text-danger map-error"></span></strong>
                            </div>

                            <div class="text-center p-4"><button
                                    class="btn btn-sm btn-success">{{ __('messages.submit') }}</button></div>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    <script src="{{ URL::asset('map/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.js') }}"></script>
    {{-- <script src="{{URL::asset('assets/lib/de-select/dselect.js')}}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <script>
        var wp = '';
        var rd = '';
        var userBa = "{{ Auth::user()->ba }}";

        const b10ptions = [
            ['W1', 'KUALA LUMPUR PUSAT', 3.14925905877391, 101.754098819705],
            ['B1', 'PETALING JAYA', 3.1128074178475, 101.605270457169],
            ['B1', 'RAWANG', 3.47839445121726, 101.622905486475],
            ['B1', 'KUALA SELANGOR', 3.40703209426401, 101.317426926947],
            ['B2', 'KLANG', 3.08428642705789, 101.436185279023],
            ['B2', 'PELABUHAN KLANG', 2.98188527916042, 101.324234779569],
            ['B4', 'CHERAS', 3.14197346621987, 101.849883983416],
            ['B4', 'BANTING', 2.82111390453244, 101.505890775541],
            ['B4', 'BANGI', 2.965810949933260, 101.81881303103104],
            ['B4', 'PUTRAJAYA & CYBERJAYA', 2.92875032271019, 101.675338316575]
        ];

        $(document).ready(function() {

            $('#search_wp').select2();
            $('#road_select').select2();
            $("#myForm").validate();

            if (userBa != '') {
                getWorkPackage(userBa)
            }

        })


        $('#search_zone').on('change', function() {
            const selectedValue = this.value;
            const areaSelect = $('#ba');

            // Clear previous options
            areaSelect.empty();
            areaSelect.append(`<option value="" hidden>Select ba</option>`)

            b10ptions.forEach((data) => {
                if (selectedValue == data[0]) {
                    areaSelect.append(`<option value="${data[1]}">${data[1]}</option>`);
                }
            });

            $('#search_wp').empty();
            $('#search_wp').append(`<option value="" hidden>Select Work Package</option>`);
            $('#road_select').append(`<option value="" hidden>select road </option>`)
            nullAllfeilds()

        })






        $('#search_wp').on('change', function() {
            var workPakcageId = this.value;
            var selectedRoad = $('#road_select')

            selectedRoad.empty();

            selectedRoad.append(`<option value="" hidden>select road </option>`)
            $.ajax({
                url: `/{{ app()->getLocale() }}/get-roads-name/${workPakcageId}`,
                dataType: 'JSON',
                method: 'GET',
                async: false,
                success: function callback(data) {

                    data.forEach((key) => {
                        selectedRoad.append(
                            `<option value="${key.id}">${key.road_name}</option>`);
                    });

                }
            })
            nullAllfeilds()

        })



        $('#road_select').on('change', function() {

            var selectedRoad = $('#road_select').val()




            $.ajax({
                url: `/{{ app()->getLocale() }}/get-roads-id/${selectedRoad}`,
                dataType: 'JSON',
                method: 'GET',
                async: false,
                success: function callback(data) {
                    nullAllfeilds()
                    var patrolTime = '';
                    if (data.time_petrol != '') {
                        var splitTime = data.time_petrol.split(' ')
                        patrolTime = splitTime[1];
                    }

                    $('#road_name').val(data.road_name)
                    $('#date_patrol').val(data.date_patrol)
                    $('#time_petrol').val(data.time_patrol)
                    $('#name_project').val(data.name_project);
                    $('#road_id').val(data.id)
                    $('#actual_km').val(parseFloat(data.actual_km).toFixed(2))
                    $('#km').val(parseFloat(data.km).toFixed(2))
                    $('#total_digging').val(data.total_digging)
                    $('#total_notice').val(data.total_notice)
                    $('#total_supervision').val(data.total_supervision)
                    $('#road_id').val(data.id)
                    $('#fidar').val(data.fidar)

                }
            })

        })

        function getWorkPackage(param) {

            console.log(param);
            var zone = $('#search_zone').val();
            $.ajax({
                url: `/{{ app()->getLocale() }}/get-work-package/${param}/${zone}`,
                dataType: 'JSON',
                method: 'GET',
                async: false,
                success: function callback(data) {

                    $('#search_wp').empty();
                    $('#search_wp').append(`<option value="" hidden>Select Work Package</option>`);
                    data.forEach((val) => {
                        if (val.wp_status == 'approved') {


                            $('#search_wp').append(
                                `<option value="${val.id}">${val.package_name}</option>`
                            );
                        }
                    });

                }
            })
            $('#road_select').empty();

            $('#road_select').append(`<option value="" hidden>select road </option>`)
            nullAllfeilds()

        }

        function nullAllfeilds() {
            $('#road_name').val('')
            $('#date_patrol').val('')
            $('#time_petrol').val('')
            $('#name_project').val('');
            $('#road_id').val('')
            $('#actual_km').val('')
            $('#km').val('')
            $('#total_digging').val('')
            $('#total_notice').val('')
            $('#total_supervision').val('')
            $('#road_id').val('')
            $('#fidar').val('')

        }
    </script>
@endsection
