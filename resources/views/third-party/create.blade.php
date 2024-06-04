@extends('layouts.app')

@section('css')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />





    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>



    {{-- @include('partials.map-css') --}}
    <style>
        .error {
            color: red;
        }

        label {
            margin-bottom: 0px !important;
            margin-top: 1rem;
        }

        input[type='radio'] {
            border-radius: 50% !important;
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

        input {
            min-width: 16px !important;
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
            border-radius: 0;
        }

        /* Optionally, style the focus state */
        .select2-container .select2-selection--single:focus {
            border-color: 1px solid #00000063;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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
                    <h3>{{ __('messages.notice_form') }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item text-lowercase"><a
                                href="{{ route('third-party-digging.index', app()->getLocale()) }}">{{ __('messages.index') }}</a>
                        </li>
                        <li class="breadcrumb-item active text-lowercase">{{ __('messages.create') }}</li>
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

                        <form action="{{ route('third-party-digging.store', app()->getLocale()) }} " id="myForm"
                            method="POST" enctype="multipart/form-data" onsubmit="return submitFoam()">
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
                                        onchange="getWorkPackage(this)">
                                        <option value="" hidden>select ba</option>

                                    </select>
                                    <input type="hidden" name="ba" id="ba">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="digging">{{ __('messages.digging') }}</label></div>
                                <div class="col-md-2">
                                    <input type="radio" name="digging" id="digging_yes" class="mt-0" value="yes">
                                    <label for="digging_yes">Yes</label>

                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="digging" id="digging_no" class="mt-0" value="no">
                                    <label for="digging_no">No</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="notice">{{ __('messages.notice') }}</label></div>
                                <div class="col-md-2">
                                    <input type="radio" name="notice" id="notice_yes" class="mt-0" value="yes">
                                    <label for="notice_yes">Yes</label>

                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="notice" id="notice_no" class="mt-0" value="no">
                                    <label for="notice_no">No</label>
                                </div>

                                {{-- <div class="col-md-4 text-center pt-3 ">
                                    <button type="button" class="btn btn-sm btn-success d-none" id="generate-notice" data-toggle="modal" data-target="#myModal">{{__('messages.generate_notice')}}</button>
                                </div> --}}
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="supervision">{{ __('messages.supervision') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="supervision" id="supervision_yes" class="mt-0"
                                        value="yes">
                                    <label for="supervision_yes">Yes</label>

                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="supervision" id="supervision_no" class="mt-0"
                                        value="no">
                                    <label for="supervision_no">No</label>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-md-4"><label for="search_wp">{{ __('messages.wp_name') }}</label></div>
                                <div class="col-md-4">
                                    <select name="search_wp" id="search_wp" class="form-control" required>
                                        <option value="" hidden>select workpackage</option>

                                    </select>
                                    <input type="hidden" name="workpackage_id" id="workpackage_id"
                                        class="form-control">
                                    <input type="hidden" name="wp_name" id="wp_name">
                                </div>
                            </div>



                            <input type="hidden" class="form-control" name="team_name" value="{{ $team }}"
                                readonly id="team_name">



                            <div class="row">
                                <div class="col-md-4"><label for="survey_date">{{ __('messages.survey_date') }}</label>
                                </div>
                                <div class="col-md-4"><input type="date" name="survey_date" id="survey_date"
                                        value="{{ date('Y-m-d') }}" class="form-control" required></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="patrolling_time">{{ __('messages.patrolling_time') }}</label></div>
                                <div class="col-md-4"><input type="time" name="patrolling_time" id="patrolling_time"
                                        value="{{ Carbon\Carbon::now()->format('H:i:s') }}" class="form-control"
                                        required>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-4"><label for="road_id">Road Id</label></div> --}}
                            <div class="col-md-4"><input type="hidden" name="road_id" id="road_id"
                                    class="form-control">
                            </div>
                            {{-- </div> --}}
                            {{-- <div class="row">
                                <div class="col-md-4"><label for="project_name">{{__('messages.project_name')}}</label></div>
                                <div class="col-md-4"><input type="text" name="project_name" id="project_name"
                                        class="form-control" required></div>
                            </div> --}}

                            {{--
                            <div class="row">
                                <div class="col-md-4"><label for="km_actual">{{__('messages.km_actual')}}</label></div>
                                <div class="col-md-4"><input type="number" name="km_actual" id="km_actual"
                                        class="form-control" required></div>
                            </div>
 --}}



                            <div class="row">
                                <div class="col-md-4"><label for="company_name">{{ __('messages.company_name') }}</label>
                                </div>
                                <div class="col-md-4"><input type="text" name="company_name" id="company_name"
                                        class="form-control" required></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="office_phone_no">{{ __('messages.office_phone_no') }}</label></div>
                                <div class="col-md-4"><input type="number" name="office_phone_no" id="office_phone_no"
                                        class="form-control" required minlength="9" maxlength="11"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="main_contractor">{{ __('messages.main_contractor') }}</label></div>
                                <div class="col-md-4"><input type="text" name="main_contractor" id="main_contractor"
                                        class="form-control" required></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="developer_phone_no">{{ __('messages.developer_phone_no') }}</label></div>
                                <div class="col-md-4"><input type="number" name="developer_phone_no"
                                        id="developer_phone_no" class="form-control" required minlength="9"
                                        maxlength="11"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="contractor_company_name">{{ __('messages.contractort_company_name') }}</label>
                                </div>
                                <div class="col-md-4"><input type="text" name="contractor_company_name"
                                        id="contractor_company_name" class="form-control" required></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="site_supervisor_name">{{ __('messages.site_supervisor_name') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="site_supervisor_name" id="site_supervisor_name"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="site_supervisor_phone_no">{{ __('messages.site_supervisor_phone_no') }}</label>
                                </div>
                                <div class="col-md-4"><input type="number" name="site_supervisor_phone_no"
                                        id="site_supervisor_phone_no" class="form-control" required minlength="9"
                                        maxlength="11">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="excavator_operator_name">{{ __('messages.excavator_operator_name') }}</label>
                                </div>
                                <div class="col-md-4"><input type="text" name="excavator_operator_name"
                                        id="excavator_operator_name" class="form-control" required></div>
                            </div>



                            <div class="row">
                                <div class="col-md-4"><label
                                        for="excavator_machinery_reg_no">{{ __('messages.excavator_machinery_reg_no') }}</label>
                                </div>
                                <div class="col-md-4"><input type="text" name="excavator_machinery_reg_no"
                                        id="excavator_machinery_reg_no" class="form-control" required></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="department_diging">{{ __('messages.dpt_diging') }}</label></div>
                                <div class="col-md-4"><input type="text" name="department_diging"
                                        id="department_diging" class="form-control" required></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="survey_status">{{ __('messages.survey_status') }}</label></div>
                                <div class="col-md-4">
                                    <select name="survey_status" id="survey_status" class="form-control" required>
                                        <option value="" hidden>select status</option>
                                        <option value="Inprogress">Inprogress</option>
                                        <option value="Complete">Complete</option>
                                    </select>
                                </div>
                            </div>


                            {{-- <div class="row">
                                <div class="col-md-4"><label for="third-party-image-1">{{__('messages.third_party_image')}} 1</label></div>
                                <div class="col-md-4"><input type="file" name="third_party_image_1" id="third-party-image-1" accept="image/*"
                                        class="form-control"  ></div>
                                        <div class="col-md-4  " id="third-party-image-1-div">

                                        </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="third-party-image-2">{{__('messages.third_party_image')}} 2</label></div>
                                <div class="col-md-4"><input type="file" name="third_party_image_2" id="third-party-image-2" accept="image/*"
                                        class="form-control"  ></div>
                                        <div class="col-md-4  " id="third-party-image-2-div">

                                        </div>
                            </div> --}}

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="before_image1">{{ __('messages.before_image_1') }}</label></div>
                                <div class="col-md-4"><input type="file" name="before_image1" id="before_image1"
                                        accept="image/*" class="form-control"></div>
                                <div class="col-md-4  " id="before_image1-div">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="before_image2">{{ __('messages.before_image_2') }}</label></div>
                                <div class="col-md-4"><input type="file" name="before_image2" id="before_image2"
                                        accept="image/*" class="form-control"></div>
                                <div class="col-md-4  " id="before_image2-div">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="before_image3">{{ __('messages.before_image_3') }}</label></div>
                                <div class="col-md-4"><input type="file" name="before_image3" id="before_image3"
                                        accept="image/*" class="form-control"></div>
                                <div class="col-md-4  " id="before_image3-div">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="during_image1">{{ __('messages.during_image_1') }}</label></div>
                                <div class="col-md-4"><input type="file" name="during_image1" id="during_image1"
                                        accept="image/*" class="form-control"></div>
                                <div class="col-md-4  " id="during_image1-div">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="during_image1">{{ __('messages.during_image_2') }}</label></div>
                                <div class="col-md-4"><input type="file" name="during_image2" id="during_image2"
                                        accept="image/*" class="form-control"></div>
                                <div class="col-md-4  " id="during_image2-div">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="during_image1">{{ __('messages.during_image_3') }}</label></div>
                                <div class="col-md-4"><input type="file" name="during_image3" id="during_image3"
                                        accept="image/*" class="form-control"></div>
                                <div class="col-md-4  " id="during_image3-div">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label
                                        for="after_image1">{{ __('messages.after_image_1') }}</label></div>
                                <div class="col-md-4"><input type="file" name="after_image1" id="after_image1"
                                        accept="image/*" class="form-control"></div>
                                <div class="col-md-4  " id="after_image1-div">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="after_image2">{{ __('messages.after_image_2') }}</label></div>
                                <div class="col-md-4"><input type="file" name="after_image2" id="after_image2"
                                        accept="image/*" class="form-control"></div>
                                <div class="col-md-4  " id="after_image2-div">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label
                                        for="after_image3">{{ __('messages.after_image_3') }}</label></div>
                                <div class="col-md-4"><input type="file" name="after_image3" id="after_image3"
                                        accept="image/*" class="form-control"></div>
                                <div class="col-md-4 " id="after_image3-div">

                                </div>
                            </div>

                            <div class="row   road-d">
                                <div class="col-md-4"><label for="road_name">{{ __('messages.rd_name') }}</label></div>
                                <div class="col-md-4">
                                    <span id="road_name_check" class="text-danger"></span>
                                    <input type="text" name="road_name" id="road_name" class="form-control" required>
                                </div>
                            </div>








                            <input type="hidden" name="lat" id="lat" class="form-control">
                            <input type="hidden" name="log" id="log" class="form-control">
                            <div class="text-center">
                                <strong> <span class="text-danger map-error"></span></strong>
                            </div>

                            <div id="map">

                            </div>

                            <div class="text-center p-4"><button
                                    class="btn btn-sm btn-success">{{ __('messages.submit') }}</button></div>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content ">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('messages.generate_notice') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="/{{ app()->getLocale() }}/generate-notice" id="remove-foam" method="POST"
                    target="_blank">

                    @csrf

                    <div class="modal-body">
                        comming soon ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>

                        <button type="submit"
                            class="btn btn-sm btn-success">{{ __('messages.generate_notice') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    <script src="{{ URL::asset('map/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    @include('partials.form-map-js')
    <script>
        // ba their names and their points
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

        var wp = '';
        var rd = '';

        $(document).ready(function() {

            $('#search_wp').select2();
            console.log(userBa);

            if (userBa !== '') {
                getBaPoints(userBa)
            }

            $('#remove-foam').on('submit', function() {

                $('#myModal').modal('hide');
            });

            $('input[type="file"]').on('change', function() {
                showUploadedImage(this)
            })




        });



        function showUploadedImage(param) {
            const file = param.files[0];
            const id = $(`#${param.id}-div`);

            if (file) {
                id.empty()
                const reader = new FileReader();
                reader.onload = function(e) {
                    var img =
                        `<a class="text-right"  href="${e.target.result}" data-lightbox="roadtrip"><span class="close-button" onclick="removeImage('${param.id}')">X</span><img src="${e.target.result}" style="height:50px;"/></a>`;
                    id.append(img)
                };

                reader.readAsDataURL(file);
            }
        }

        function removeImage(id) {
            console.log(id);
            $(`#${id}`).val('');
            $(`#${id}-div`).empty();
        }


        function getBaPoints(param) {
            var baSelect = $('#ba_s')
            baSelect.empty();

            b1Options.map((data) => {
                if (data[1] == param) {
                    baSelect.append(`<option value="${data}">${data[1]}</option>`)
                }
            });
            let baVal = document.getElementById('ba_s');
            getWorkPackage(baVal)
        }

        function getWorkPackage(param) {
            var splitVal = param.value.split(',');
            addRemoveBundary(splitVal[1], splitVal[2], splitVal[3])
            $('#ba').val(splitVal[1]);
            if (wp != '') {
                map.removeLayer(wp)
            }
            wp = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
                layers: 'cite:tbl_workpackage',
                format: 'image/png',
                cql_filter: "ba='" + splitVal[1] + "'",
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })
            map.addLayer(wp)
            wp.bringToFront()
            if (rd != '') {
                map.removeLayer(rd)
            }


            var zone = $('#search_zone').val();
            $.ajax({
                url: `/{{ app()->getLocale() }}/get-work-package/${splitVal[1]}/${zone}`,
                dataType: 'JSON',
                method: 'GET',
                async: false,
                success: function callback(data) {
                    $('#search_wp').empty();
                    $('#search_wp').append(`<option value="" hidden>Select Work Package</option>`);
                    data.forEach((val) => {
                        if (val.wp_status == 'approved') {


                            $('#search_wp').append(
                                `<option value="${val.id},${val.package_name},${val.x} ,${val.y}">${val.package_name}</option>`
                            );
                        }
                    });


                }
            })

        }


        $('#search_wp').on('change', function() {
            const selectedValue = this.value;
            var spiltVal = selectedValue.split(',');

            $('#wp_name').val(spiltVal[1]);
            map.setView([parseFloat(spiltVal[3]), parseFloat(spiltVal[2])], 16)
            $('#for-excel').html(`<a class="mt-4" href="/generate-third-party-diging-excel/${spiltVal[0]}"><button class="btn-sm mt-2
                btn btn-primary">Download Qr</button></a>`)
            $('#workpackage_id').val(spiltVal[0])

            if (wp != '') {
                map.removeLayer(wp)
            }
            wp = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
                layers: 'cite:tbl_workpackage',
                format: 'image/png',
                cql_filter: "package_name='" + spiltVal[1] + "'",
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })
            map.addLayer(wp)
            wp.bringToFront()
            if (rd != '') {
                map.removeLayer(rd)
            }
            rd = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
                layers: 'cite:tbl_roads',
                format: 'image/png',
                cql_filter: "id_workpackage='" + spiltVal[0] + "'",
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })
            map.addLayer(rd)
            rd.bringToFront()

        })

        function generateNotice(param) {
            if (param.value == "yes") {
                $('#generate-notice').removeClass('d-none')
            } else {
                if (!$('#generate-notice').hasClass('d-none')) {
                    $('#generate-notice').addClass('d-none')
                }
            }
        }
    </script>
@endsection
