@extends('layouts.app')

@section('css')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />
    {{-- @include('partials.map-css') --}}

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

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
            color: black !important;
            margin-bottom: 0px !important;
            margin-top: 1rem;
        }

        #map {
            margin: 30px;
            height: 400px;
            padding: 20px;
        }
        .form-input{border: 0}
    </style>
@endsection


@section('content')
    {{-- TITLE & BREAD CRUMBS --}}
    <section class="content-header">
        <div class="container-  ">
            <div class="row  " style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{__('messages.cable_bridge')}}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="{{ route('cable-bridge.index',app()->getLocale()) }}">{{__('messages.index')}}</a></li>
                        <li class="breadcrumb-item active">{{__("messages.create")}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content container">

        {{-- CARD START --}}
        <div class=" card col-md-12 p-4 ">
            <div class="form-input ">
                <h3 class="text-center p-2"></h3>

                {{-- FROM START --}}
                <form action="{{ route('cable-bridge.store',app()->getLocale()) }} " id="myForm" method="POST"
                    enctype="multipart/form-data"  onsubmit="return submitFoam()">

                    @csrf

                    {{-- ZONE --}}
                    <div class="row">
                        <div class="col-md-4"><label for="zone">{{__("messages.zone")}}</label></div>
                        <div class="col-md-4">
                            <select name="zone" id="search_zone" class="form-control" required>
                              @if (Auth::user()->zone == '')
                                <option value="" hidden>select zone</option>
                                <option value="W1">W1</option>
                                <option value="B1">B1</option>
                                <option value="B2">B2</option>
                                <option value="B4">B4</option>
                              @else
                                <option value="{{ Auth::user()->zone }}" hidden>{{ Auth::user()->zone }}</option>
                              @endif
                            </select>
                        </div>
                    </div>

                    {{-- BA --}}
                    <div class="row">
                        <div class="col-md-4"><label for="ba">{{__("messages.ba")}}</label></div>
                        <div class="col-md-4">
                            <select name="ba_s" id="ba_s" class="form-control" required onchange="getWp(this)">
                                <option value="" hidden>select zone</option>
                            </select>
                            <input type="hidden" name="ba" id="ba">
                        </div>
                    </div>

                    {{-- SECTION TO --}}
                    <div class="row">
                        <div class="col-md-4"><label for="end_date">{{__("messages.to")}}</label></div>
                        <div class="col-md-4">
                            <input type="text" name="end_date" id="end_date" class="form-control" >
                        </div>
                    </div>

                    {{-- SECTION FROM --}}
                    <div class="row">
                        <div class="col-md-4"><label for="start_date">{{__("messages.from")}}</label></div>
                        <div class="col-md-4">
                            <input type="text" name="start_date" id="start_date" class="form-control" >
                        </div>
                    </div>

                    {{-- VOLTAGE --}}
                    <div class="row">
                        <div class="col-md-4"><label for="voltage">{{ __('messages.voltage') }}</label></div>
                        <div class="col-md-4">
                            <select name="voltage" id="voltage" class="form-control">
                                <option value="" hidden>select</option>
                                <option value="11kw">11kv</option>
                                <option value="13kw">13kv</option>
                            </select>
                        </div>
                    </div>

                    {{-- SURVEY DATE --}}
                    <div class="row">
                        <div class="col-md-4"><label for="visit_date">{{__("messages.survey_date")}}</label></div>
                        <div class="col-md-4">
                            <input type="date" name="visit_date" id="visit_date" class="form-control" required value="{{date('Y-m-d')}}">
                        </div>
                    </div>

                    {{-- PATROL TIME --}}
                    <div class="row">
                        <div class="col-md-4"><label for="patrol_time">{{__('messages.patrol_time')}}</label></div>
                        <div class="col-md-4">
                            <input type="time" name="patrol_time" id="patrol_time" class="form-control" required value="{{date('H:i')}}">
                        </div>
                    </div>

                    {{-- TEAM --}}
                    <input type="hidden" name="team" id="team" value="{{ $team }}" class="form-control" readonly>

                    {{-- VANDALISM --}}
                    <div class="row">
                        <div class="col-md-4"><label for="vandalism_status">{{__("messages.vandalism")}} </label></div>
                        <div class="col-md-4">
                            <select name="vandalism_status" id="vandalism_status" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- PIPE BROKEN --}}
                    <div class="row">
                        <div class="col-md-4"><label for="pipe_staus">{{__("messages.pipe_broken")}}</label></div>
                        <div class="col-md-4">
                            <select name="pipe_staus" id="pipe_staus" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- COLLAPSED STATUS --}}
                    <div class="row">
                        <div class="col-md-4"><label for="collapsed_status">{{__("messages.collapsed")}} </label></div>
                        <div class="col-md-4">
                            <select name="collapsed_status" id="collapsed_status" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- RUSTY --}}
                    <div class="row">
                        <div class="col-md-4"><label for="rust_status">{{__("messages.rusty")}}</label></div>
                        <div class="col-md-4">
                            <select name="rust_status" id="rust_status" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- DANGER SIGN --}}
                    <div class="row">
                        <div class="col-md-4"><label for="danger_sign">{{__("messages.danger_sign")}}</label></div>
                        <div class="col-md-4">
                            <select name="danger_sign" id="danger_sign" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- ANTI CROSSING DEVICE --}}
                    <div class="row">
                        <div class="col-md-4"><label for="anti_crossing_device">{{__("messages.anti_crossing_device")}}</label></div>
                        <div class="col-md-4">
                            <select name="anti_crossing_device" id="anti_crossing_device" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- LEANING --}}
                    <div class="row">
                        <div class="col-md-4"><label for="condong">{{__("messages.leaning")}}</label></div>
                        <div class="col-md-4">
                            <select name="condong" id="condong" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- TREAPASS --}}
                    <div class="row">
                        <div class="col-md-4"><label for="pencerobohan">{{__("messages.trespass")}}</label></div>
                        <div class="col-md-4">
                            <select name="pencerobohan" id="pencerobohan" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- BUSHY --}}
                    <div class="row">
                        <div class="col-md-4"><label for="bushes_status">{{__("messages.bushy")}}</label></div>
                        <div class="col-md-4">
                            <select name="bushes_status" id="bushes_status" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- CLEANLINESS --}}
                    <div class="row">
                        <div class="col-md-4"><label for="kebersihan_jabatan">{{__("messages.cleanliness")}}</label></div>
                        <div class="col-md-4">
                            <select name="kebersihan_jabatan" id="kebersihan_jabatan" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- CABLE BRIDGE --}}
                    <div class="row">
                        <div class="col-md-4"><label for="cable_bridge_image">{{__("messages.cable_bridge")}} {{__("messages.images")}} </label></div>
                        <div class="col-md-4">
                            <input type="file" name="cable_bridge_image_1" id="cable_bridge_image_1" class="form-control" accept="image/*" required>
                            <input type="file" name="cable_bridge_image_2" id="cable_bridge_image_2" class="form-control" accept="image/*" required>
                        </div>
                    </div>

                    {{-- VANDALISM --}}
                    <div class="row">
                        <div class="col-md-4"><label for="image_vandalism">{{__("messages.image_vandalism")}}</label></div>
                        <div class="col-md-4">
                            <input type="file" name="image_vandalism" id="image_vandalism" class="form-control" accept="image/*">
                            <input type="file" name="image_vandalism_2" id="image_vandalism_2" class="form-control" accept="image/*">
                        </div>
                    </div>

                    {{-- IMAGE PIPE --}}
                    <div class="row">
                        <div class="col-md-4"><label for="image_pipe">{{__("messages.image_pipe")}}</label></div>
                        <div class="col-md-4">
                            <input type="file" name="image_pipe" id="image_pipe" class="form-control" accept="image/*">
                            <input type="file" name="image_pipe_2" id="image_pipe_2" class="form-control" accept="image/*">
                        </div>
                    </div>

                    {{-- IMAGE COLLAPSED --}}
                    <div class="row">
                        <div class="col-md-4"><label for="image_collapsed">{{__("messages.image_collapsed")}}</label></div>
                        <div class="col-md-4">
                            <input type="file" name="image_collapsed" id="image_collapsed" class="form-control" accept="image/*">
                            <input type="file" name="image_collapsed_2" id="image_collapsed_2" class="form-control" accept="image/*">
                        </div>
                    </div>

                    {{-- IMAGE RUST --}}
                    <div class="row">
                        <div class="col-md-4"><label for="image_rust">{{__("messages.image_rust")}}</label></div>
                        <div class="col-md-4">
                            <input type="file" name="image_rust" id="image_rust" class="form-control" accept="image/*">
                            <input type="file" name="image_rust_2" id="image_rust_2" class="form-control" accept="image/*">
                        </div>
                    </div>

                    {{-- DANGER SIGN --}}
                    <div class="row">
                        <div class="col-md-4"><label for="danger_sign_img">{{__("messages.danger_sign")}} {{__("messages.image")}}</label></div>
                        <div class="col-md-4">
                            <input type="file" name="danger_sign_img" id="danger_sign_img" class="form-control" accept="image/*">
                        </div>
                    </div>

                    {{-- ANTI CROSSING DECIVE --}}
                    <div class="row">
                        <div class="col-md-4"><label for="anti_cross_device_img">{{__("messages.anti_crossing_device")}} {{__("messages.image")}}</label></div>
                        <div class="col-md-4">
                            <input type="file" name="anti_cross_device_img" id="anti_cross_device_img" class="form-control" accept="image/*">
                        </div>
                    </div>

                    {{-- IMAGE BUSHES --}}
                    <div class="row">
                        <div class="col-md-4"><label for="images_bushes">{{__("messages.image_bushes")}}</label></div>
                        <div class="col-md-4">
                            <input type="file" name="images_bushes" id="images_bushes" class="form-control" accept="image/*">
                            <input type="file" name="images_bushes_2" id="images_bushes_2" class="form-control" accept="image/*">
                        </div>
                    </div>

                    {{-- OTHER IMAGE --}}
                    <div class="row">
                        <div class="col-md-4"><label for="other_image">{{__("messages.other_image")}}</label></div>
                        <div class="col-md-4">
                            <input type="file" name="other_image" id="other_image" class="form-control" accept="image/*">
                        </div>
                    </div>

                    {{-- COORDINATE --}}
                    <div class="row">
                        <div class="col-md-4"><label for="coordinate">{{__("messages.coordinate")}}</label></div>
                        <div class="col-md-4">
                            <input type="text" name="coordinate" id="coordinate" class="form-control" readonly
                                required>
                        </div>
                    </div>

                    {{-- HIDDEN LAT LOG --}}
                    <input type="hidden" name="lat" id="lat" required class="form-control">
                    <input type="hidden" name="log" id="log" class="form-control">

                    {{-- MAP ERROR DIV --}}
                    <div class="text-center">
                        <strong><span class="text-danger map-error"></span></strong>
                    </div>

                    {{-- MAP DIV --}}
                    <div id="map"></div>

                    {{-- SUBMIT BUTTON --}}
                    <div class="text-center p-4">
                        <button class="btn btn-sm btn-success">{{__("messages.submit")}}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>


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
                    ['B4', 'BANGI',2.965810949933260,101.81881303103104 ],
                    ['B4', 'PUTRAJAYA & CYBERJAYA', 2.92875032271019,101.675338316575],
                    ['B4', 'PUCHONG', 2.971632230751114, 101.62918173453126]
                ];
                const userBa = "{{Auth::user()->ba}}";
                $(document).ready(function() {



       if (userBa !== '') {
           getBaPoints(userBa)
       }

    });


       function getBaPoints(param){
           var baSelect = $('#ba_s')
               baSelect.empty();

               b1Options.map((data)=>{
                   if (data[1] == param) {
                       baSelect.append(`<option value="${data}">${data[1]}</option>`)
                   }
               });
               let baVal = document.getElementById('ba_s');
               getWp(baVal)
       }

 </script>
@endsection
