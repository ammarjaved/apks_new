@extends('layouts.app')

@section('css')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />
    {{-- @include('partials.map-css') --}}

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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

        #map {
            margin: 30px;
            height: 400px;
            padding: 20px;
        }

        .form-input {
            border: 0
        }
    </style>
@endsection


@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row  " style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{ __('messages.feeder_pillar') }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item text-lowercase"><a
                                href="{{ route('substation.index', app()->getLocale()) }}">{{ __('messages.index') }}</a>
                        </li>
                        <li class="breadcrumb-item text-lowercase active">{{ __('messages.create') }} </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class=" ">

        <div class="container">

            <div class=" ">

                <div class=" card col-md-12 p-4 ">
                    <div class="form-input ">
                        <h3 class="text-center p-2"></h3>

                        <form action="{{ route('feeder-pillar.store', app()->getLocale()) }} " id="myForm" method="POST"
                            enctype="multipart/form-data" onsubmit="return submitFoam()">
                            @csrf


                            <div class="row">
                                <div class="col-md-4"><label for="zone">{{ __('messages.zone') }}</label></div>
                                <div class="col-md-4">
                                    <select name="zone" id="search_zone" class="form-control" required>

                                        @if (Auth::user()->zone == '')
                                            <option value="" hidden>select zone</option>
                                            <option value="W1">W1</option>
                                            <option value="B1">B1</option>
                                            <option value="B2">B2</option>
                                            <option value="B4">B4</option>
                                        @else
                                            <option value="{{ Auth::user()->zone }}" hidden>{{ Auth::user()->zone }}
                                            </option>
                                        @endif


                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="ba">{{ __('messages.ba') }}</label></div>
                                <div class="col-md-4"><select name="ba_s" id="ba_s" class="form-control" required
                                        onchange="getWp(this)">
                                        <option value="" hidden>select zone</option>

                                    </select>
                                    <input type="hidden" name="ba" id="ba">
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4"><label for="name">{{ __('messages.size') }}</label></div>
                                <div class="col-md-4">
                                    <select name="size" id="size" class="form-control" required>
                                        <option value="" hidden>select size</option>
                                        <option value="400">400</option>
                                        <option value="800">800</option>
                                        <option value="1600">1600</option>
                                    </select>

                                </div>
                            </div>




                            <input type="hidden" name="team" id="team" value="{{ $team }}"
                                class="form-control" readonly>

                            <div class="row">
                                <div class="col-md-4"><label for="visit_date">{{ __('messages.survey_date') }}</label></div>
                                <div class="col-md-4">
                                    <input type="date" name="visit_date" id="visit_date" value="{{ date('Y-m-d') }}"
                                        class="form-control" required>
                                </div>
                            </div>




                            <div class="row">
                                <div class="col-md-4"><label for="patrol_time">{{ __('messages.patrol_time') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="time" name="patrol_time" id="patrol_time" value="{{ date('H:i') }}"
                                        class="form-control" required>
                                </div>
                            </div>




                            {{--
                            <div class="row">
                                <div class="col-md-4"><label for="voltage">Area</label></div>
                                <div class="col-md-4">
                                    <input type="text" name="area" id="area"
                                        class="form-control" required >
                                    </div>
                            </div> --}}




                            <div class="row">
                                <div class="col-md-4"><label for="gate_status">{{ __('messages.gate') }} </label></div>
                                <div class="col-md-4">

                                    <div class=" d-flex">
                                        <input type="checkbox" name="gate_status[unlocked]" id="gate_status_unlocked"
                                            value="unlocked">
                                        <label for="gate_status_unlocked">{{ __('messages.unlocked') }}</label>
                                    </div>
                                    <div class=" d-flex">
                                        <input type="checkbox" name="gate_status[demaged]" id="gate_status_demaged">
                                        <label for="gate_status_demaged">{{ __('messages.demaged') }}</label>
                                    </div>

                                    <div class="d-flex">
                                        <input type="checkbox" name="gate_status[other]" id="gate_status_others"
                                            onclick="getStatus(this)">
                                        <label for="gate_status_others">{{ __('messages.others') }}</label>


                                    </div>
                                    <input type="text" name="gate_status[other_value]" id="gate_status_other"
                                        class="form-control d-none" placeholder="please enter other gate defect">

                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-4"><label for="vandalism_status">{{ __('messages.vandalism') }}
                                    </label></div>
                                <div class="col-md-4 ">
                                    <select name="vandalism_status" id="vandalism_status" class="form-control" required
                                        >
                                        <option value="" hidden>select status</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>

                                </div>
                            </div>





                            <div class="row">
                                <div class="col-md-4"><label for="leaning_staus">{{ __('messages.leaning') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <select name="leaning_staus" id="leaning_staus" class="form-control" required
                                        onchange="leaningStatus(this)">
                                        <option value="" hidden>select status</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>

                                </div>
                            </div>

                            <div class="row  d-none" id="leaning-angle">
                                <div class="col-md-4"><label
                                        for="leaning_angle">{{ __('messages.leaning_angle') }}</label></div>
                                <div class="col-md-4">
                                    <input type="text" name="leaning_angle" id="leaning_angle" class="form-control">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="rust_status">{{ __('messages.rusty') }}</label></div>
                                <div class="col-md-4">
                                    <select name="rust_status" id="rust_status" class="form-control" required>
                                        <option value="" hidden>select status</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="fp_gaurd">{{ __('messages.fp_gaurd') }}</label></div>
                                <div class="col-md-4">
                                    <select name="guard_status" id="fp_gaurd" class="form-control" required>
                                        <option value="" hidden>select status</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="paint_status">{{ __('messages.paint_faded') }}</label></div>
                                <div class="col-md-4">
                                    <select name="paint_status" id="paint_status" class="form-control" required>
                                        <option value="" hidden>select status</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="advertise_poster_status">{{ __('messages.cleaning_illegal_ads_banners') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <select name="advertise_poster_status" id="advertise_poster_status"
                                        class="form-control" required>
                                        <option value="" hidden>select status</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>


                            

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="feeder_pillar_image">{{ __('messages.feeder_pillar') }}
                                        {{ __('messages.images') }} </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="feeder_pillar_image_1" id="feeder_pillar_image_1"
                                        accept="image/*" class="form-control" required>
                                    <input type="file" name="feeder_pillar_image_2" id="feeder_pillar_image_2"
                                        accept="image/*" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="image_name_plate">{{ __('messages.name_plate') }}
                                        {{ __('messages.images') }} </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="image_name_plate" id="image_name_plate"
                                        accept="image/*" class="form-control" required>
                                     
                                </div>
                            </div>




                            <div class="row">
                                <div class="col-md-4">
                                    <label for="image_gate">{{ __('messages.image_gate') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="image_gate" id="image_gate" accept="image/*"
                                        class="form-control">
                                    <input type="file" name="image_gate_2" id="image_gate_2" accept="image/*"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="image_grass">{{ __('messages.image_vandalism') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="image_vandalism" id="image_vandalism" accept="image/*"
                                        class="form-control">
                                    <input type="file" name="image_vandalism_2" id="image_vandalism_2"
                                        accept="image/*" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="images_gate_after_lock">{{ __('messages.image_leaning') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="image_leaning" id="image_leaning" accept="image/*"
                                        class="form-control">
                                    <input type="file" name="image_leaning_2" id="image_leaning_2" accept="image/*"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="image_rust">{{ __('messages.image_rust') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="image_rust" id="image_rust" accept="image/*"
                                        class="form-control">
                                    <input type="file" name="image_rust_2" id="image_rust_2" accept="image/*"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label
                                        for="images_advertise_poster">{{ __('messages.image_advertise_poster') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="images_advertise_poster" id="images_advertise_poster"
                                        accept="image/*" class="form-control">
                                    <input type="file" name="images_advertise_poster_2" id="images_advertise_poster_2"
                                        accept="image/*" class="form-control">
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4"><label for="img_advertise_poster">{{__('messages.image_advertise_poster_removal')}}</label></div>
                                <div class="col-md-4">
                                    <input type="file" name="image_advertisement_after_1" id="image_advertisement_after_1" accept="image/*"
                                        class="form-control" >
                                        <input type="file" name="image_advertisement_after_2" id="image_advertisement_after_2" accept="image/*"
                                        class="form-control" >
                                    </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4">
                                    <label for="other_image">{{ __('messages.other_image') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="other_image" id="other_image" accept="image/*"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="coordinate">{{ __('messages.coordinate') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="coordinate" id="coordinate" readonly
                                        class="form-control" required>
                                </div>
                            </div>

                            <input type="hidden" name="lat" id="lat" required class="form-control">
                            <input type="hidden" name="log" id="log" class="form-control">
                            <div class="text-center">
                                <strong> <span class="text-danger map-error"></span> </strong>
                            </div>
                            <div id="map"></div>

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

    @include('partials.form-map-js')

    <script>
        const b1Options = [
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

        const userBa = "{{ Auth::user()->ba }}";

        $(document).ready(function() {



            if (userBa !== '') {
                getBaPoints(userBa)
            }

        });


        function getBaPoints(param) {
            var baSelect = $('#ba_s')
            baSelect.empty();

            b1Options.map((data) => {
                if (data[1] == param) {
                    baSelect.append(`<option value="${data}">${data[1]}</option>`)

                }
            });
            let baVal = document.getElementById('ba_s');

            getWp(baVal)
        }

        function leaningStatus(event) {
            var val = event.value;
            if (val == 'No') {
                if (!$('#leaning-angle').hasClass('d-none')) {
                    $('#leaning-angle').addClass('d-none')
                }
            } else {
                $('#leaning-angle').removeClass('d-none')
            }
        }

        function getStatus(event) {
            var val = event.value;

            if (!$('#gate_status_other').hasClass('d-none')) {
                $('#gate_status_other').addClass('d-none')
            } else {
                $('#gate_status_other').removeClass('d-none')
            }



        }
    </script>
@endsection
