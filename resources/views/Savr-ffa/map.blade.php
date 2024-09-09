@extends('layouts.app', ['page_title' => 'Index'])

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />

    @include('partials.map-css')
    <style>
        #map {
            height: 700px;
        }
        #lightbox .lb-outerContainer{display: none}
        #myLargeModalLabel>.modal-dialog {
    max-width: 1100px !important;
    margin: 0.75rem 9rem !important ;}


    </style>
@endsection




@section('content')
    @if (Session::has('failed'))
        <div class="alert {{ Session::get('alert-class', 'alert-secondary') }}" role="alert">
            {{ Session::get('failed') }}

            <button type="button" class="close border-0 bg-transparent" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <section class="content-header">
        <div class="container-  ">
            <div class="row  " style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{__('messages.savr_ffa')}}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">index</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid bg-white pt-2">


        <div class="card p-0 mb-3">
            <div class="card-body row form-input">

                <div class="col-md-2">
                    <label for="search_zone">Zone</label>
                    <select name="search_zone" id="search_zone" class="form-control"
                        onchange="onChangeZone(this.value)">

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
                <div class="col-md-2">
                    <label for="search_ba">BA</label>
                    <select name="search_ba" id="search_ba" class="form-control" onchange="callLayers(this.value)">

                        <option value="{{ Auth::user()->ba }}" hidden>
                            {{ Auth::user()->ba != '' ? Auth::user()->ba : 'Select BA' }}</option>
                    </select>
                </div>

                {{-- <div class="col-md-2">
                    <label for="from_date">Fom</label>
                    <input type="date" class="form-control" id="from_date" onchange="filterByDate(this)" />
                </div>

                <div class="col-md-2">
                    <label for="to_date">To</label>
                    <input type="date" class="form-control" id="to_date" onchange="filterByDate(this)" />
                </div> --}}

                <div class="col-md-2">
                    <label for="cycle">Cycle</label>
                    <select name="cycle" id="cycle" class="form-control" onchange="setCycle(this.value)">
                        <option value="1" selected>1</option>
                        <option value="2">2</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <br />
                    <input type="button" class="btn btn-secondary mt-2" id="reset" value="Reset"
                        onclick="resetMapFilters()" />
                </div>



            </div>
        </div>

        <div class="p-3 form-input  ">
            <label for="select_layer">Select Layer : </label>
            <span class="text-danger" id="er-select-layer"></span>

            <div class="d-sm-flex">
                <div class="px-3 d-flex">



                <div class="px-3 d-flex">

                    <input type="radio" name="select_layer" id="ffa_accept" class="without_defects m-1" value="ffa_accept" onchange="selectLayer(this.value)">
                    <label for="ffa_accept">Accept</label>
                </div>


                <div class=" mx-4">
                    <input type="radio" name="select_layer" id="ffa_layer_pending" value="ffa_pending"
                        onchange="selectLayer(this.value)" class="pending">
                    <label for="ffa_layer_pending">Pending </label>
                </div>


                <div class=" mx-4">
                    <input type="radio" name="select_layer" id="ffa_layer_reject" value="ffa_reject"
                        onchange="selectLayer(this.value)" class="reject">
                    <label for="ffa_layer_reject">Reject </label>
                </div>


                <div class="px-3 d-flex">

                    <input type="radio" name="select_layer" id="select_layer_pano" class="m-1 pano" value="pano" onchange="selectLayer(this.value)">
                    <label for="select_layer_pano">Pano</label>
                </div>


                <div class="mx-4">
                    <div id="the-basics">
                        <div class="d-flex">
                            <div class="col-6">
                                <input type="radio" name="search-by" value="ffa_house_no" id="search-by-house-no"  onclick="$('#search-input').attr('placeholder','Search by House No')"> <label for="search-by-house-no">House No</label>
                            </div>
                            <div class="col-6">
                                <input type="radio" name="search-by" value="ffa_id" id="search-by-id"  onclick="document.getElementById('search-input').placeholder = 'Search by ID'"> <label for="search-by-id">Id</label>
                            </div>
                            <div class="col-6">
                                <input type="radio" name="search-by" value="tiang_id" id="search-by-tiang" checked onclick="document.getElementById('search-input').placeholder = 'Search by tiang id'"> <label for="search-by-tiang">Tiang Id</label>
                            </div>
                        </div>
                        <input class="typeahead" type="text" placeholder="search by id" id="search-input" class="form-control">

                    </div>
                </div>
            </div>

            {{-- <div class="col-md-4">
                <div id="the-basics-substation">
                    <div class="d-flex">
                        <div class="col-6">
                            <input type="radio" name="substation-search-by" value="substation_name" id="search-by-substation-name" checked onclick="$('#substation-search-input').attr('placeholder','Search by Substation Name ').val('')"> <label for="search-by-substation-name">Substation Name</label>
                        </div>
                        <div class="col-6">
                            <input type="radio" name="substation-search-by" value="substation_id" id="search-by-substation-id" onclick="$('#substation-search-input').attr('placeholder','Search by Substation ID ').val('')"> <label for="search-by-substation-id">Substation ID</label>
                        </div>
                    </div>
                    <input class="typeahead-substation" type="text" placeholder="search by Substation Name" id="substation-search-input" class="form-control">
                </div>
            </div> --}}

        </div>

        <!--  START MAP CARD DIV -->
        <div class="row m-2">


            <!-- START MAP  DIV -->
            <div class="col-md-8 p-0 ">
                <div class="card p-0 m-0"
                    style="border: 1px solid rgb(177, 175, 175) !important; border-radius: 0px !important;">
                    <div class="card-header text-center"><strong> MAP</strong></div>
                    <div class="card-body p-0">
                        <div id="map">

                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="card p-0 m-0"
                    style="border: 1px solid rgb(177, 175, 175) !important; border-radius: 0px !important;">

                    <div class="card-header text-center"><strong>Detail</strong></div>

                    <div class="card-body p-0" style="height: 700px ;overflow: hidden;" id='set-iframe'>

                    </div>
                </div>
            </div>
            <!-- END MAP  DIV -->
            <div id="wg" class="windowGroup">

            </div>

            <div id="wg1" class="windowGroup">

            </div>

        </div><!--  END MAP CARD DIV -->

    </div>

    <div class="modal fade bd-example-modal-lg " id="myLargeModalLabel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="width:1100px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Site Data Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body " style="max-height : 80vh ; overflow: scroll" >
                     <table class="table table-hover" id="polygone-tiang-data" >
                        <thead>
                            <th>ID</th>
                            <th>BA</th>
                            <th>POLE NO</th>
                            <th>POLE ID</th>
                            <th>HOUSE NUMBER</th>
                            <th>WAYAR TERTANGGAL</th>
                            <th>JOINT BOX</th>
                            <th>IPC TERBAKAR</th>
                            <th>HOUSE RENOVATION</th>
                            <th>OTHER</th>
                            <th>OTHER NAME</th>
                            <th>QA Status</th>
                            <th>HOUSE IMAGE</th>

                            {{-- <th>IMAGE 1</th>
                            <th>IMAGE 2</th>
                            <th>FROM IMAGE 1</th>
                            <th>FROM IMAGE 2</th> --}}

                            <th>ACTION</th>
                        </thead>
                        <tbody id="polygone-tiang-data-body">

                        </tbody>
                     </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="rejectReasonModal">
        <div class="modal-dialog">
            <div class="modal-content ">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title">Reject</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="" id="reject-foam" method="GET">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" name="id" id="reject-id">
                        <input type="hidden" name="status" id="qa_status" value="Reject">
                        <label for="reject">Reject Remarks : </label>
                        <textarea name="reject_remakrs" id="reject_remakrs" cols="20" rows="5" class="form-control" placeholder="enter resaon" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" onclick="QaStatusReject()">update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <div class="modal fade" id="rejectReasonModalShow">
        <div class="modal-dialog">
            <div class="modal-content ">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title">Reject Reason</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>


                    <div class="modal-body">
                        <input type="hidden" name="id" id="reject-id">
                        <input type="hidden" name="status" id="qa_status" value="Reject">
                        <label for="reject">Reject Remarks : </label>
                        <textarea name="reject_remakrs" id="reject_remakrs_show" disabled readonly cols="20" rows="5" class="form-control" placeholder="enter resaon" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    </div>


            </div>
        </div>
    </div>


    <div class="modal fade" id="tiangDetailModal">
        <div class="modal-dialog">
            <div class="modal-content ">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title">Detail</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>


                <div class="modal-body" id="tiangDetailModalBody" style="max-height : 90vh ; overflow-y: scroll">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-danger" onclick="QaStatusReject()">update</button> --}}
                </div>


            </div>
        </div>
    </div>


    <div class="modal fade" id="removeConfirm">
        <div class="modal-dialog">
            <div class="modal-content ">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Remove Recored</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="" id="remove-foam" method="POST">
                    @method('DELETE')
                    @csrf

                    <div class="modal-body">
                        Are You Sure ?
                        <input type="hidden" name="id" id="remove-modal-id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" onclick="removeRecord()">Remove</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
{{-- <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script> --}}
    @include('partials.map-js')



    <script>

        // SEARCH BY TIANG NO OR NAME
        var substringMatcher = function(strs) {

            return function findMatches(q, cb) {

                var matches;
                var searchBy= $('input[name="search-by"]:checked').val();
                var nameUrl='';

                if(searchBy!='tiang_id'){
                    nameUrl=`/{{ app()->getLocale() }}/search/find-savr-ffa?type=${searchBy}&q=${q}&cycle=${cycle}`;
                }else{
                    nameUrl=`/{{ app()->getLocale() }}/search/find-tiang?type=${searchBy}&q=${q}&cycle=${cycle}`;
                }

                matches = [];

                $.ajax({
                    url: nameUrl,
                    dataType: 'JSON',
                    //data: data,
                    method: 'GET',
                    async: false,
                    success: function callback(data) {
                        $.each(data, function(i, str) {
                            if(searchBy!='tiang_id'){
                            matches.push(str.house_number);
                            }else{
                                matches.push(str.tiang_no);
                            }

                        });
                    }
                })

                cb(matches);
            };
        };


        var marker = '';
        var typeahead_url;
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
           if(searchBy!='tiang_id'){
            typeahead_url='/{{ app()->getLocale() }}/search/find-savr-ffa-cordinated/' + encodeURIComponent(name)+'/'+searchBy;
           }else{
            typeahead_url='/{{ app()->getLocale() }}/search/find-tiang-cordinated/' + encodeURIComponent(name)+'/'+searchBy;
           }

            if (marker != '') {
                map.removeLayer(marker)
            }
            $.ajax({
                url: typeahead_url,
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


    <script>

        $(function(){
            // Event handler for hiding Tiang modal
            $('#tiangDetailModal').on('hide.bs.modal', function(event) {
                getTiangByPolyGone()
                $('#tiangDetailModalBody').html('');
            });


            // Event handler for showing reject reason modal
            $('#rejectReasonModalShow').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var remarks = button.data('reject_remarks');
                $('#reject_remakrs_show').val(remarks);
            });


            // Event handler for showing remove confirm modal
            $('#removeConfirm').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                $('#remove-modal-id').val(id);
            });


            // Event handler for showing reject reason modal
            $('#rejectReasonModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                $('#reject-id').val(id);
            });


            // Listen for message from iframe
            window.addEventListener('message', function(event) {
                if (event.data === 'closeModal') {
                    $('#tiangDetailModal').modal('hide');
                    getTiangByPolyGone()
                }
            });
        });



        // ADD DRAW TOOLS

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        var drawControl = new L.Control.Draw({
            draw: {
                circle    : false,
                marker    : false,
                polygon   : true,
                polyline  : false,
                rectangle : false,
                circlemarker : false,
            },
            edit: {
                featureGroup: drawnItems,
                edit: false,  // Disable editing mode
                remove: false // Disable deletion mode
            }
        });

        map.addControl(drawControl);

        var newLayer = '';
        var jsonData = '';

        // DRAW TOOL ON CREATED EVENT
        map.on('draw:created', function(e){
            var type = e.layerType;
            newLayer = e.layer;
            // drawnItems.addLayer(newLayer);
            var data = newLayer.toGeoJSON();
            jsonData = JSON.stringify(data.geometry);

            getTiangByPolyGone()

        })


        function getTiangByPolyGone()
        {
            $.ajax(
                {
                    url: `/{{ app()->getLocale() }}/search/savr-ffa-by-polygon?json=${jsonData}&cycle=${cycle}`,
                    dataType: 'JSON',
                    //data: data,
                    method: 'GET',
                    async: false,
                    success: function callback(response) {

                        if (response.status == 200) {
                            $("#polygone-tiang-data-body").html('');
                            var data = response.data;

                            for (let index = 0; index < data.length; index++) {
                                const element = data[index];
                                let status = '';

                                if (element.qa_status == 'Accept') {
                                   status=  `<span class="badge bg-success">Accept</span>`;

                                }else if (element.qa_status == 'Reject') {
                                    status = ` <a type="button" class=" " data-reject_remarks="${element.reject_remarks}" data-toggle="modal" data-target="#rejectReasonModalShow">
                                                    <span class="badge bg-danger">${element.reject_remarks.substring(0, 8)}...</span>
                                                </a>`;
                                }else{
                                    status = `<div class="d-flex text-center" id="status-${element.id}">
                                                <button class="btn btn-success btn-sm" type="button" onclick="QaStatusAccept(${element.id})">Accept</button>
                                                    /
                                                <a type="button" class="btn btn-danger  btn-sm" data-id="${element.id}" data-toggle="modal" data-target="#rejectReasonModal">
                                                    Reject
                                                </a>
                                            </div>`;
                                }

                                let str = `
                                            <tr>
                                                <td>${element.id}</td>
                                                <td>${element.ba}</td>
                                                <td>${element.pole_no}</td>
                                                <td>${element.pole_id}</td>
                                                <td>${element.house_number}</td>
                                                <td>${element.wayar_tertanggal}</td>
                                                <td>${element.joint_box}</td>
                                                <td>${element.ipc_terbakar}</td>
                                                <td>${element.house_renovation}</td>
                                                <td>${element.other}</td>
                                                <td>${element.other_name}</td>
                                                <td>${status}</td>
                                                <td>
                                                    <a href="{{config('globals.APP_IMAGES_URL')}}${element.house_image}" target="_blank" />
                                                        <img src="{{config('globals.APP_IMAGES_URL')}}${element.house_image}" style="height:50px;" >
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class='d-flex'>
                                                        <button type="button" class="btn  mr-2" onclick="getTiangDetail(${element.id})"><i class="fas fa-eye text-primary"></i></button>
                                                        <button type="button" class="btn  "  data-id="${element.id}" data-toggle="modal" data-target="#removeConfirm"><i class="fas fa-trash text-danger"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                `;
                                $('#polygone-tiang-data-body').append(str);
                            }
                            $('#myLargeModalLabel').modal('show');
                        }else{
                            alert('Request Failed');
                        }
                        console.log(response);
                    }
                })

        }


        function getTiangDetail(paramId){
            $('#tiangDetailModalBody').html('');

            $('#tiangDetailModalBody').html(
                `<iframe src="/{{ app()->getLocale() }}/get-savr-ffa-edit/${paramId}" frameborder="0" style="height:500px; width:100%"></iframe>`
                )
                $('#tiangDetailModal').modal('show');

        }


        function QaStatusAccept(paramId )
        {

            $.ajax(
                {
                    url: `/{{ app()->getLocale() }}/savr-ffa-update-QA-Status?status=Accept&&id=${paramId}`,
                    dataType: 'JSON',
                    //data: data,
                    method: 'GET',
                    async: false,
                    success: function callback(response) {
                        console.log(response);
                        if (response.status == 200) {
                            $('#status-'+paramId).html('<span class="badge bg-success">Accept</span>');
                        }else{
                            alert('Request Failed')
                        }

                    }
                }
                )
        }


        function QaStatusReject( )
        {
            let id = $('#reject-id').val()
            let remarks = $('#reject_remakrs').val()

            $.ajax(
                {
                    url: `/{{ app()->getLocale() }}/savr-ffa-update-QA-Status?status=Reject&id=${id}&reject_remakrs=${remarks}`,
                    dataType: 'JSON',
                    //data: data,
                    method: 'GET',
                    async: false,
                    success: function callback(response) {
                        console.log(response);
                        if (response.status == 200) {
                            getTiangByPolyGone()
                        }else{
                            alert('Request Failed')
                        }
                        $('#rejectReasonModal').modal('hide');

                           $('#reject-id').val('')
                          $('#reject_remakrs').val('')
                    }
                }
                )
        }


        function removeRecord() {
            var id = document.getElementById('remove-modal-id').value;
            axios.get('/{{app()->getLocale()}}/remove-savr-ffa/' + id)
            .then(function (response) {
                getTiangByPolyGone()
            })
            .catch(function (error) {
                alert('Request Failed')
            });
            $('#removeConfirm').modal('hide');
        }


        var ffaYes='';

        // for add and remove layers
        function addRemoveBundary(param, paramY, paramX) {


            var q_cql = '';
            var boundaryFilter = '';
            var baFilter = '';
            var cycle_filter = ''

            if (param == '') {
                baFilter = "ba ILIKE '%" + param + "%' "
                boundaryFilter = "station ILIKE '%" + param + "%' ";
            }else{
                baFilter = "ba ='" + param + "' ";
                boundaryFilter = "station ='" + param + "' ";

            }
            q_cql = baFilter +` AND cycle=${cycle} `;
            cycle_filter = q_cql ;
            if (from_date != '') {
                q_cql = q_cql + "AND review_date >=" + from_date;
            }
            if (to_date != '') {
                q_cql = q_cql + "AND review_date <=" + to_date;
            }

            if (boundary !== '') {
                map.removeLayer(boundary)
            }
            console.log(q_cql);

            boundary = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                layers: 'apks:ba_2',
                format: 'image/png',
                cql_filter: boundaryFilter,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })
            map.addLayer(boundary)
            boundary.bringToFront()

            map.flyTo([parseFloat(paramY), parseFloat(paramX)], zoom, {
                duration: 1.5, // Animation duration in seconds
                easeLinearity: 0.25,
            });



            road = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                layers: 'apks:tbl_roads_2',
                format: 'image/png',
                cql_filter: baFilter,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })
            // map.addLayer(road)
            // road.bringToFront()


            // ACCEPT
            if (savr_ffa_accept != '') {
                map.removeLayer(savr_ffa_accept)
            }

            savr_ffa_accept = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                layers: 'apks:savr_ffa_accept_2',
                format: 'image/png',
                cql_filter: cycle_filter,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })



            map.addLayer(savr_ffa_accept)
            savr_ffa_accept.bringToFront()



            if (ffaYes != '') {
                map.removeLayer(ffaYes)
            }

            ffaYes = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                layers: 'apks:tiang_ffa_yes',
                format: 'image/png',
                cql_filter: cycle_filter,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })



            // map.addLayer(ffaYes)
            // ffaYes.bringToFront()




            // PENDING
            if (savr_ffa_pending != '') {
                map.removeLayer(savr_ffa_pending)
            }

            savr_ffa_pending = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                layers: 'apks:savr_ffa_pending_2',
                format: 'image/png',
                cql_filter: cycle_filter,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })
            map.addLayer(savr_ffa_pending)
            savr_ffa_pending.bringToFront()


            if (savr_ffa_reject != '') {
                map.removeLayer(savr_ffa_reject)
            }

            savr_ffa_reject = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                layers: 'apks:savr_ffa_reject_2',
                format: 'image/png',
                cql_filter: q_cql,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })

            map.addLayer(savr_ffa_reject)
            savr_ffa_reject.bringToFront()


            if (pano_layer !== '') {
                map.removeLayer(pano_layer)
            }
            pano_layer = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                layers: 'apks:pano_apks_2',
                format: 'image/png',
                cql_filter: baFilter,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            });
            // map.addLayer(pano_layer);
            // map.addLayer(pano_layer)


            if(work_package){
            map.removeLayer(work_package);
            }

            work_package = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                    layers: 'apks:tbl_workpackage_2',
                    format: 'image/png',
                    cql_filter: baFilter,
                    maxZoom: 21,
                    transparent: true
                }, {
                    buffer: 10
                })
            // map.addLayer(work_package)
            // work_package.bringToFront()

            if(g5_x_5_grid){
                map.removeLayer(g5_x_5_grid);
            }
            g5_x_5_grid = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                    layers: 'apks:grid_5x5_2',
                    format: 'image/png',
                    cql_filter: baFilter,
                    maxZoom: 21,
                    transparent: true
                }, {
                    buffer: 10
                })

            addGroupOverLays()

        }


        // add group overlayes
        function addGroupOverLays() {
            if (layerControl != '') {
                // console.log("inmsdanssdkjnasjnd");
                map.removeControl(layerControl);
            }
            // console.log("sdfsdf");
            groupedOverlays = {
                "POI": {
                    'BA': boundary,
                    '5_x_5_grid':g5_x_5_grid,
                    // 'Substation': substation,
                    // 'Feeder Pillar': feeder_pillar,
                    'Pano': pano_layer,
                    'Reject' : savr_ffa_reject,
                    'Accept' : savr_ffa_accept,
                    'Pending' : savr_ffa_pending,
                    'Roads': road,
                    'Work Package':work_package,
                    'TIANG': ffaYes

                }
            };
            //add layer control on top right corner of map
            layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
                collapsed: true,
                position: 'topright'
                // groupCheckboxes: true
            }).addTo(map);
        }

        function roadModal(data, id) {

            var str = '';
            gid = id.split('.')

            $('#exampleModalLabel').html("Road Info")
            str = ` <tr>
                <tr><th>Road Name</th><td>${data.road_name}</td> </tr>
                <tr><th>KM</th><td>${data.km}</td> </tr>

                <tr><th>Totoal Digging</th><td>${data.total_digging}</td> </tr>
                <tr><th>Total Notice</th><td>${data.total_notice}</td> </tr>
                <th>Total Supervision</th><td>${data.total_supervision}</td> </tr>

                <tr><th>Detail</th><td class="text-center"><a href="/{{ app()->getLocale() }}/patrolling-detail/${gid[1]}" target="_blank" class="btn btn-sm btn-secondary">Detail</a>
                    </td> </tr>

            `;
            $("#my_data").html(str);
            $('#myModal').modal('show');
        }

        function showModalData(data, id) {
//             var str = '';
//             gid = id.split('.')
//             console.log(gid);
//             $('#exampleModalLabel').html("Tiang Info")
//             str = ` <tr>
//         <tr><th>Ba</th><td>${data.ba}</td> </tr>
//         <tr><th>Section From</th><td>${data.section_from}</td> </tr>
//         <tr><th>Section To</th><td>${data.section_to}</td> </tr>
//         <th>Actual Date</th><td>${data.actual_date}</td> </tr>
//         <th>Planed Date</th><td>${data.planed_date}</td> </tr>

//         <tr><th>Coordinate</th><td>${data.coordinate}</td> </tr>
//         <tr><th>Created At</th><td>${data.created_at}</td> </tr>
//         <tr><th>Detail</th><td class="text-center">    <button type="button" onclick="openDetails(${gid[1]})" class="btn btn-sm btn-secondary">Edit</button>
// </td></tr>
//         <tr><th>Detail</th><td class="text-center">    <a href="/{{ app()->getLocale() }}/tiang-talian-vt-and-vr/${gid[1]}" target="_blank" class="btn btn-sm btn-secondary">Detail</a>
//             </td> </tr>
//         `
            // $("#my_data").html(str);
            // $('#myModal').modal('show');
            openDetails(data.id)

        }

        function openDetails(id) {
            // $('#myModal').modal('hide');
            $('#set-iframe').html('');

            $('#set-iframe').html(
                `<iframe src="/{{ app()->getLocale() }}/get-savr-ffa-edit/${id}" frameborder="0" style="height:700px; width:100%" ></iframe>`
                )


        }
    </script>
@endsection
