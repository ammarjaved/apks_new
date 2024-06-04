@extends('layouts.app')

@section('css')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />

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
    </style>
@endsection


@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row  " style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>Patrolling</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="{{ route('third-party-digging.index') }}">index</a></li>
                        <li class="breadcrumb-item active">update</li>
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

                        <form action="/patrolling-update" id="myForm" method="POST" enctype="multipart/form-data"
                            onsubmit="return submitFoam()">
                            @csrf

                            <div class="row">
                                <div class="col-md-4"><label for="zone">Zone</label></div>
                                <div class="col-md-4">
                                    <select name="zone" id="search_zone" class="form-control" required>

                                        <option value="" hidden>select zone</option>
                                        <option value="W1">W1</option>
                                        <option value="B1">B1</option>
                                        <option value="B2">B2</option>
                                        <option value="B4">B4</option>

                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="ba">BA</label></div>
                                <div class="col-md-4"><select name="ba_s" id="ba_s" class="form-control" required
                                        onchange="getWorkPackage(this)">
                                        <option value="" hidden>select BA</option>

                                    </select>
                                    <input type="hidden" name="ba" id="ba">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="search_wp">Work Package Name</label></div>
                                <div class="col-md-4">
                                    <select name="search_wp" id="search_wp" class="form-control" required>
                                        <option value="" hidden>select work package</option>

                                    </select>
                                    <input type="hidden" name="workpackage_id" id="workpackage_id" class="form-control">
                                    <input type="hidden" name="wp_name" id="wp_name">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="road_select">Select Road</label></div>
                                <div class="col-md-4">
                                    <select name="road_select" id="road_select" class="form-control" required>
                                        <option value="" hidden>select road</option>

                                    </select>

                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-4"><label for="road_name">Road Name</label></div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="road_name" id="road_name">
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-4"><label for="date_patrol">Patrolling Date</label></div>
                                <div class="col-md-4"><input type="date" name="date_patrol" id="date_patrol"
                                        class="form-control" required></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="time_petrol">Patrolling Time</label></div>
                                <div class="col-md-4"><input type="time" name="time_petrol" id="time_petrol"
                                        class="form-control" required></div>
                            </div>

                            <input type="hidden" name="road_id" id="road_id" class="form-control">


                            <div class="row">
                                <div class="col-md-4"><label for="fidar">Feeder</label></div>
                                <div class="col-md-4"><input type="text" name="fidar" id="fidar"
                                        class="form-control" required></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="name_project">Project Name</label></div>
                                <div class="col-md-4"><input type="text" name="name_project" id="name_project"
                                        class="form-control" required></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="km">Km Plan</label></div>
                                <div class="col-md-4"><input type="number" name="km" id="km"
                                        class="form-control" required></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="actual_km">Km Actual</label></div>
                                <div class="col-md-4"><input type="number" name="actual_km" id="actual_km"
                                        class="form-control" required></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="digging">Total Digging</label></div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="total_digging" id="total_digging"
                                        required>


                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="notice">Total Notice</label></div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="total_notice" id="total_notice"
                                        required>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="supervision">Total Supervision</label></div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="total_supervision"
                                        id="total_supervision" required>

                                </div>
                            </div>

















                            <div class="text-center">
                                <strong> <span class="text-danger map-error"></span></strong>
                            </div>

                            <div class="text-center p-4"><button class="btn btn-sm btn-success">Submit</button></div>


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


    <script>
        var wp = '';
        var rd = '';

        $(document).ready(function() {


            $("#myForm").validate();
        })



        $('#search_zone').on('change', function() {
            const selectedValue = this.value;
            const areaSelect = $('#ba_s');

            // Clear previous options
            areaSelect.empty();
            areaSelect.append(`<option value="" hidden>Select ba</option>`)


            if (selectedValue === 'W1') {
                const w1Options = [
                    ['KL PUSAT', 'KUALA LUMPUR PUSAT', 3.14925905877391, 101.754098819705]
                ];

                w1Options.forEach((data) => {
                    areaSelect.append(`<option value="${data}">${data[0]}</option>`);
                });
            } else if (selectedValue === 'B1') {
                const b1Options = [
                    ['PJ', 'PETALING JAYA', 3.1128074178475, 101.605270457169],
                    ['RAWANG', 'RAWANG', 3.47839445121726, 101.622905486475],
                    ['K.SELANGOR', 'KUALA SELANGOR', 3.40703209426401, 101.317426926947]
                ];

                b1Options.forEach((data) => {
                    areaSelect.append(`<option value="${data}">${data[0]}</option>`);
                });
            } else if (selectedValue === 'B2') {
                const b2Options = [
                    ['KLANG', 'KLANG', 3.08428642705789, 101.436185279023],
                    ['PORT KLANG', 'PELABUHAN KLANG', 2.98188527916042, 101.324234779569]
                ];

                b2Options.forEach((data) => {
                    areaSelect.append(`<option value="${data}">${data[0]}</option>`);
                });
            } else if (selectedValue === 'B4') {
                const b4Options = [
                    ['CHERAS', 'CHERAS', 3.14197346621987, 101.849883983416],
                    ['BANTING/SEPANG', 'BANTING', 2.82111390453244, 101.505890775541],
                    ['BANGI', 'BANGI', 2.965810949933260, 101.81881303103104],
                    ['PUTRAJAYA/CYBERJAYA/PUCHONG', 'PUTRAJAYA & CYBERJAYA', 2.92875032271019,
                        101.675338316575
                    ]
                ];

                b4Options.forEach((data) => {
                    areaSelect.append(`<option value="${data}">${data[0]}</option>`);
                });
            }
            $('#search_wp').empty();
            $('#search_wp').append(`<option value="" hidden>Select Work Package</option>`);
            $('#road_select').empty();



            $('#road_select').append(`<option value="" hidden>select road </option>`)
            nullAllfeilds()


        });


        function getWorkPackage(param) {
            var splitVal = param.value.split(',');

            $('#ba').val(splitVal[1]);

            var zone = $('#search_zone').val();
            $.ajax({
                url: `/get-work-package/${splitVal[1]}/${zone}`,
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


        $('#search_wp').on('change', function() {
            var workPakcageId = this.value;
            var selectedRoad = $('#road_select')

            selectedRoad.empty();

            selectedRoad.append(`<option value="" hidden>select road </option>`)
            $.ajax({
                url: `/get-roads-name/${workPakcageId}`,
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
                url: `/get-roads-id/${selectedRoad}`,
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
                    $('#time_petrol').val(data.time_petrol)
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
