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
                    <h3>{{__('messages.savr_ffa')}}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="{{ route('savr-ffa.index',app()->getLocale()) }}">{{__('messages.index')}}</a></li>
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
                <form action="{{ route('savr-ffa.store',app()->getLocale()) }} " id="myForm" method="POST"
                    enctype="multipart/form-data"  onsubmit="return submitFoam()">

                    @csrf


                    {{-- BA --}}
                    <div class="row">
                        <div class="col-md-4"><label for="ba">{{__("messages.ba")}}</label></div>
                        <div class="col-md-4">
                            <select name="ba_s" id="ba_s" class="form-control" onchange="getWp(this)" required>
                                @if (Auth::user()->ba == '')
                                    <option value="" hidden>Select ba</option>

                                    <optgroup label="W1">
                                        <option value="KL PUSAT,KUALA LUMPUR PUSAT, 3.14925905877391, 101.754098819705">KL PUSAT</option>
                                    </optgroup>

                                    <optgroup label="B1">
                                        <option value="PJ,PETALING JAYA, 3.1128074178475, 101.605270457169">PETALING JAYA</option>
                                        <option value="RAWANG,RAWANG, 3.47839445121726, 101.622905486475">RAWANG</option>
                                        <option value="K.SELANGOR,KUALA SELANGOR, 3.40703209426401, 101.317426926947">KUALA SELANGOR</option>
                                    </optgroup>

                                    <optgroup label="B2">
                                        <option value="KLANG,KLANG, 3.08428642705789, 101.436185279023">KLANG</option>
                                        <option value="PORT KLANG,PELABUHAN KLANG, 2.98188527916042, 101.324234779569">PELABUHAN KLANG</option>
                                    </optgroup>

                                    <optgroup label="B4">
                                        <option value="CHERAS,CHERAS, 3.14197346621987, 101.849883983416">CHERAS</option>
                                        <option value="BANTING/SEPANG,BANTING, 2.82111390453244, 101.505890775541">BANTING</option>
                                        <option value="BANGI,BANGI,2.965810949933260,101.81881303103104">BANGI</option>
                                        <option value="PUTRAJAYA/CYBERJAYA/PUCHONG,PUTRAJAYA & CYBERJAYA, 2.92875032271019, 101.675338316575">PUTRAJAYA & CYBERJAYA</option>
                                    </optgroup>

                                @else
                                @endif
                            </select>
                            <input type="hidden" name="ba" id="ba">
                        </div>
                    </div>


                    {{-- POLE NO --}}
                    <div class="row">
                        <div class="col-md-4"><label for="pole_no">{{__("messages.pole_no")}} </label></div>
                        <div class="col-md-4">
                            <div id="the-basics" >
                                <input class="typeahead" type="text" placeholder="search by ID" id="search-input" class="form-control">
                            </div>
                        </div>
                    </div>

                    {{-- POLE ID --}}
                    <div class="row">
                        <div class="col-md-4"><label for="pole_id">{{__("messages.pole_id")}}</label></div>
                        <div class="col-md-4">
                            <input type="text" name="pole_id" id="pole_id" class="form-control" readonly>
                        </div>
                    </div>


                    {{-- HOUSE NUMBER --}}
                    <div class="row">
                        <div class="col-md-4"><label for="house_number">{{__("messages.house_number")}}</label></div>
                        <div class="col-md-4">
                            <input type="text" name="house_number" id="house_number" class="form-control" >
                        </div>
                    </div>









                    {{-- WAYAR TERTANGGAL --}}

                    <div class="row">
                        <div class="col-md-4"><label for="wayar_tertanggal">{{__("messages.wayar_tertanggal")}}</label></div>
                        <div class="col-md-4">
                            <select name="wayar_tertanggal" id="wayar_tertanggal" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- JOINT BOX --}}
                    <div class="row">
                        <div class="col-md-4"><label for="joint_box">{{__("messages.joint_box")}} </label></div>
                        <div class="col-md-4">
                            <select name="joint_box" id="joint_box" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- IPC TERBAKAR --}}
                    <div class="row">
                        <div class="col-md-4"><label for="ipc_terbakar">{{__("messages.ipc_terbakar")}}</label></div>
                        <div class="col-md-4">
                            <select name="ipc_terbakar" id="ipc_terbakar" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- HOUSE RENOVATION --}}
                    <div class="row">
                        <div class="col-md-4"><label for="house_renovation">{{__("messages.house_renovation")}}</label></div>
                        <div class="col-md-4">
                            <select name="house_renovation" id="house_renovation" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- OTHER --}}
                    <div class="row">
                        <div class="col-md-4"><label for="other">{{__("messages.other")}}</label></div>
                        <div class="col-md-4">
                            <select name="other" id="other" class="form-control" required>
                                <option value="" hidden>select option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- OTHER NAME --}}
                    <div class="row d-none" id="other_name_div">
                        <div class="col-md-4"><label for="other_name">{{__("messages.other_name")}}</label></div>
                        <div class="col-md-4">
                           <input type="text" name="other_name" id="other_name" class="form-control ">
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-4">
                            <label for="house_image">{{ __('messages.house_image') }} </label>
                        </div>
                        <div class="col-md-4 p-2 pr-5">
                            <input type="file" name="house_image" id="house_image" required accept="image/*" class="form-control">
                        </div>
                        <div class="col-md-4" id="house_image_div"></div>
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

        const userBa = "{{Auth::user()->ba}}";
        $(document).ready(function() {



                if (userBa !== '') {
                    getBaPoints(userBa)
                }

                $('input[type="file"]').on('change', function() {
                showUploadedImage(this)
            })

        });


       function getBaPoints(param){
           var baSelect = $('#ba_s')
               baSelect.empty();

               b10ptions.map((data)=>{
                   if (data[1] == param) {
                       baSelect.append(`<option value="${data}">${data[1]}</option>`)
                   }
               });
               let baVal = document.getElementById('ba_s');
               getWp(baVal)
       }



       // DISPALY UPLOADED IMAGE
       function showUploadedImage(param)
        {
            const file = param.files[0];
            const id = $(`#${param.id}_div`);

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

            // REMOVE UPLOADED IMAGES
            function removeImage(id) {
                        console.log(id);
                        $(`#${id}`).val('');
                        $(`#${id}_div`).empty();
                    }
 </script>


<script>

    // SEARCH BY TIANG NO OR NAME
    var substringMatcher = function(strs) {

        return function findMatches(q, cb) {

            var matches; 

            matches = [];


            $.ajax({
                url: `/{{ app()->getLocale() }}/search/find-tiang?type=${searchBy}&q=${q}&cycle=${cycle}`,
                dataType: 'JSON',
                //data: data,
                method: 'GET',
                async: false,
                success: function callback(data) {
                    $.each(data, function(i, str) {

                        matches.push(str.tiang_no);

                    });
                }
            })

            cb(matches);
        };
    };


    var marker = '';
    $('#the-basics .typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    }, {
        name: 'states',
        source: substringMatcher()
    });

    $('.typeahead').on('typeahead:select', function(event, suggestion) {
        var name = encodeURIComponent(suggestion);
        var searchBy= $('input[name="search-by"]:checked').val();


        if (marker != '') {
            map.removeLayer(marker)
        }
        $.ajax({
            url: '/{{ app()->getLocale() }}/search/find-tiang-cordinated/' + encodeURIComponent(name)+'/'+searchBy,
            dataType: 'JSON',
            //data: data,
            method: 'GET',
            async: false,
            success: function callback(data) {
                console.log(data);
                map.flyTo([parseFloat(data.y), parseFloat(data.x)], 16, {
                    duration: 1.5, // Animation duration in seconds
                    easeLinearity: 0.25,
                });

                marker = new L.Marker([data.y, data.x]);
                map.addLayer(marker);
            }
        })
    });



    // SEARCH BY TIANG NO OR NAME
    var substationSubstringMatcher = function(strs) {

    return function findMatches(q, cb) {

        var matches;
        var searchBy= $('input[name="substation-search-by"]:checked').val();

        matches = [];
        $.ajax({
            url: '/{{ app()->getLocale() }}/search/find-substation-in-tiang/'+searchBy+'/' + q,
            dataType: 'JSON',
            //data: data,
            method: 'GET',
            async: false,
            success: function callback(data) {
                $.each(data, function(i, str) {

                    matches.push(str.name);

                });
            }
        })

        cb(matches);
    };
    };



    $('#the-basics-substation .typeahead-substation').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
        }, {
        name: 'states',
        source: substationSubstringMatcher()
    });

    $('.typeahead-substation').on('typeahead:select', function(event, suggestion) {
    var name = encodeURIComponent(suggestion);
    var searchBy= $('input[name="substation-search-by"]:checked').val();


    if (marker != '') {
        map.removeLayer(marker)
    }
    $.ajax({
        url: '/{{ app()->getLocale() }}/search/find-substation-in-tiang-cordinated/' + encodeURIComponent(name)+'/'+searchBy,
        dataType: 'JSON',
        //data: data,
        method: 'GET',
        async: false,
        success: function callback(data) {
            console.log(data);
            map.flyTo([parseFloat(data.y), parseFloat(data.x)], 16, {
                duration: 1.5, // Animation duration in seconds
                easeLinearity: 0.25,
            });

            marker = new L.Marker([data.y, data.x]);
            map.addLayer(marker);
        }
    })

    });

</script>

@endsection
