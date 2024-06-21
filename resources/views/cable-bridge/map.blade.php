@extends('layouts.app', ['page_title' => 'Index'])

@section('css')
    @include('partials.map-css')
    <style>
        #map {
            height: 700px;
        }
        #myLargeModalLabel>.modal-dialog {
            max-width: 1100px !important;
            margin: 0.75rem 9rem !important ;
        }
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
                    <h3>Cable Bridge</h3>
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
                    <select name="search_zone" id="search_zone" class="form-control" onchange="onChangeZone(this.value)">

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

                <div class="col-md-2">
                    <label for="from_date">Fom</label>
                    <input type="date" class="form-control" id="from_date" onchange="filterByDate(this)" />
                </div>

                <div class="col-md-2">
                    <label for="to_date">To</label>
                    <input type="date" class="form-control" id="to_date" onchange="filterByDate(this)" />
                </div>
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
            <label for="select_layer">Select : </label>
            <span class="text-danger" id="er-select-layer"></span>

            <div class="d-sm-flex">
                {{-- <div class="">
                    <input type="radio" name="select_layer" id="select_layer_substation" value="substation" onchange="selectLayer(this.value)">
                    <label for="select_layer_substation">Substation</label>
                </div> --}}

                {{-- <div class=" mx-4">
                    <input type="radio" name="select_layer" id="cb_unsurveyed" value="cb_unsurveyed" class="unsurveyed"
                        onchange="selectLayer(this.value)">
                    <label for="cb_unsurveyed">Unsurveyed</label>
                </div> --}}



                <div class=" mx-4">
                    <input type="radio" name="cb_with_defects" id="cable_bridge" value="cb_with_defects"
                        class="with_defects" onchange="selectLayer(this.value)">
                    <label for="cb_with_defects">Surveyed with defects</label>
                </div>

                <div class=" mx-4">
                    <input type="radio" name="select_layer" id="cb_without_defects" value="cb_without_defects"
                        class="without_defects" onchange="selectLayer(this.value)">
                    <label for="cb_without_defects">Surveyed witout defects</label>
                </div>


                {{-- @if (Auth::user()->ba != '') --}}
                <div class=" mx-4">
                    <input type="radio" name="select_layer" id="select_layer_unsurveyed" value="cb_unsurveyed"
                        onchange="selectLayer(this.value)" class="unsurveyed">
                    <label for="select_layer_unsurveyed">Unsurveyed </label>
                </div>

                <div class=" mx-4">
                    <input type="radio" name="select_layer" id="select_layer_pending" value="cb_pending"
                        onchange="selectLayer(this.value)" class="pending">
                    <label for="select_layer_pending">Pending </label>
                </div>


                <div class=" mx-4">
                    <input type="radio" name="select_layer" id="select_layer_reject" value="cb_reject"
                        onchange="selectLayer(this.value)" class="reject">
                    <label for="select_layer_reject">Reject </label>
                </div>
                {{-- @endif --}}


                <div class=" mx-4">
                    <input type="radio" name="select_layer" id="select_layer_pano" value="pano"
                        onchange="selectLayer(this.value)">
                    <label for="select_layer_pano">Pano</label>
                </div>

                <div class="mx-4">
                    <div id="the-basics">
                        <input class="typeahead" type="text" placeholder="search id" class="form-control">
                    </div>
                </div>

            </div>

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


    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Site Data Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <table class="table table-bordered">
                        <tbody id="my_data"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg " id="myLargeModalLabel" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="width:1100px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Site Data Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3" style="max-height : 80vh ; overflow: scroll">
                    <table class="table table-hover" id="polygone-tiang-data">
                        <thead>
                            <th>ID</th>
                            <th>BA</th>
                            <th>TO</th>
                            <th>FROM</th>
                            <th>VISIT DATE</th>
                            <th>TOTAL DEFECTS</th>
                            <th>QA Status</th>
                            <th>CREATED BY</th>
                            <th>IMAGE 1</th>
                            <th>IMAGE 2</th>
                            <th>ACTION</th>
                        </thead>
                        <tbody id="polygone-data-body">

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
                        <textarea name="reject_remakrs" id="reject_remakrs" cols="20" rows="5" class="form-control"
                            placeholder="enter resaon" required></textarea>
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
                    <textarea name="reject_remakrs" id="reject_remakrs_show" disabled readonly cols="20" rows="5"
                        class="form-control" placeholder="enter resaon" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>


            </div>
        </div>
    </div>


    <div class="modal fade" id="DetailModal">
        <div class="modal-dialog">
            <div class="modal-content ">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title">Detail</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>


                <div class="modal-body" id="DetailModalBody" style="max-height : 90vh ; overflow-y: scroll">

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
    @include('partials.map-js')

    <script>
        var substringMatcher = function(strs) {

            return function findMatches(q, cb) {

                var matches;

                matches = [];
                $.ajax({
                    url: `/{{ app()->getLocale() }}/search/find-cable-bridge/${q}/${cycle}`,
                    dataType: 'JSON',
                    //data: data,
                    method: 'GET',
                    async: false,
                    success: function callback(data) {
                        $.each(data, function(i, str) {

                            matches.push(str.id);

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

            if (marker != '') {
                map.removeLayer(marker)
            }
            $.ajax({
                url: '/{{ app()->getLocale() }}/search/find-cable-bridge-cordinated/' + encodeURIComponent(
                    name),
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
        $(function() {
            // Event handler for hiding Tiang modal
            $('#DetailModal').on('hide.bs.modal', function(event) {
                getRecoredByPolyGone()
                $('#DetailModalBody').html('');
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
                    $('#DetailModal').modal('hide');
                    getRecoredByPolyGone()
                }
            });
        });

        // ADD DRAW TOOLS

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        var drawControl = new L.Control.Draw({
            draw: {
                circle: false,
                marker: false,
                polygon: true,
                polyline: false,
                rectangle: false,
                circlemarker: false,
            },
            edit: {
                featureGroup: drawnItems,
                edit: false, // Disable editing mode
                remove: false // Disable deletion mode
            }
        });

        map.addControl(drawControl);

        var newLayer = '';
        var jsonData = '';

        // DRAW TOOL ON CREATED EVENT
        map.on('draw:created', function(e) {
            var type = e.layerType;
            newLayer = e.layer;
            // drawnItems.addLayer(newLayer);
            var data = newLayer.toGeoJSON();
            jsonData = JSON.stringify(data.geometry);

            getRecoredByPolyGone()

        })



        function getRecoredByPolyGone() {
            $.ajax({
                url: `/{{ app()->getLocale() }}/search/cable-bridge-by-polygon?json=${jsonData}&cycle${cycle}`,
                dataType: 'JSON',
                //data: data,
                method: 'GET',
                async: false,
                success: function callback(response) {

                    console.log(response);
                    if (response.status == 200) {
                        $("#polygone-data-body").html('');
                        var data = response.data;

                        for (let index = 0; index < data.length; index++) {
                            const element = data[index];
                            let status = '';

                            if (element.qa_status == 'Accept') {
                                status = `<span class="badge bg-success">Accept</span>`;

                            } else if (element.qa_status == 'Reject') {
                                status = ` <a type="button" class=" " data-reject_remarks="${element.reject_remarks}" data-toggle="modal" data-target="#rejectReasonModalShow">
                                              <span class="badge bg-danger">${element.reject_remarks.length > 9 ? element.reject_remarks.substring(0, 8) + '...' : element.reject_remarks}</span>

                                          </a>`;
                            } else {
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
                                          <td>${element.end_date}</td>
                                          <td>${element.start_date}</td>
                                          <td>${element.visit_date}</td>
                                          <td>${element.total_defects}</td>
                                          <td>${status}</td>
                                          <td>${element.created_by}</td>
                                          <td>
                                              <a href="{{config('globals.APP_IMAGES_URL')}}${element.cable_bridge_image_1}" target="_blank" />
                                                  <img src="{{config('globals.APP_IMAGES_URL')}}${element.cable_bridge_image_1}" style="height:50px;" >
                                              </a>
                                          </td>
                                          <td>
                                              <a href="{{config('globals.APP_IMAGES_URL')}}${element.cable_bridge_image_2}" target="_blank" />
                                                  <img src="{{config('globals.APP_IMAGES_URL')}}${element.cable_bridge_image_2}" style="height:50px;"  />
                                              </a>
                                          </td>
                                          <td>
                                              <div class='d-flex'>
                                                  <button type="button" class="btn  mr-2" onclick="getFormDetail(${element.id})"><i class="fas fa-eye text-primary"></i></button>
                                                  <button type="button" class="btn  "  data-id="${element.id}" data-toggle="modal" data-target="#removeConfirm"><i class="fas fa-trash text-danger"></i></button>
                                              </div>
                                          </td>
                                      </tr>
                          `;
                            $('#polygone-data-body').append(str);
                        }
                        $('#myLargeModalLabel').modal('show');
                    } else {
                        alert('Request Failed');
                    }
                    // console.log(response);
                }
            })

        }


        function getFormDetail(paramId) {
            $('#DetailModalBody').html('');

            $('#DetailModalBody').html(
                `<iframe src="/{{ app()->getLocale() }}/get-cable-bridge-edit/${paramId}" frameborder="0" style="height:500px; width:100%"></iframe>`
            )
            $('#DetailModal').modal('show');

        }


        function QaStatusAccept(paramId) {

            $.ajax({
                url: `/{{ app()->getLocale() }}/cable-bridge-update-QA-Status?status=Accept&&id=${paramId}`,
                dataType: 'JSON',
                //data: data,
                method: 'GET',
                async: false,
                success: function callback(response) {
                    console.log(response);
                    if (response.status == 200) {
                        $('#status-' + paramId).html('<span class="badge bg-success">Accept</span>');
                    } else {
                        alert('Request Failed')
                    }

                }
            })
        }


        function QaStatusReject() {
            let id = $('#reject-id').val()
            let remarks = $('#reject_remakrs').val()

            $.ajax({
                url: `/{{ app()->getLocale() }}/cable-bridge-update-QA-Status?status=Reject&id=${id}&reject_remakrs=${remarks}`,
                dataType: 'JSON',
                //data: data,
                method: 'GET',
                async: false,
                success: function callback(response) {
                    console.log(response);
                    if (response.status == 200) {
                        getRecoredByPolyGone()
                    } else {
                        alert('Request Failed')
                    }
                    $('#rejectReasonModal').modal('hide');

                    $('#reject-id').val('')
                    $('#reject_remakrs').val('')
                }
            })
        }


        function removeRecord() {
            var id = document.getElementById('remove-modal-id').value;
            axios.get('/{{ app()->getLocale() }}/remove-cable-bridge/' + id)
                .then(function(response) {
                    getRecoredByPolyGone()
                })
                .catch(function(error) {
                    alert('Request Failed')
                });
            $('#removeConfirm').modal('hide');
        }
    </script>


    <script>
        // for add and remove layers
        // for add and remove layers
        function addRemoveBundary(param, paramY, paramX) {



            var q_cql = '';
            var boundaryFilter = '';
            var baFilter = '';

            if (param == '') {
                baFilter = "ba ILIKE '%" + param + "%' "
                boundaryFilter = "station ILIKE '%" + param + "%' ";
            } else {
                baFilter = "ba ='" + param + "' ";
                boundaryFilter = "station ='" + param + "' ";

            }
            q_cql = baFilter;
            q_cql = q_cql +` AND cycle=${cycle} `;
            if (from_date != '') {
                q_cql = q_cql + "AND visit_date >=" + from_date;
            }
            if (to_date != '') {
                q_cql = q_cql + "AND visit_date <=" + to_date;
            }


            if (work_package) {
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
            map.addLayer(work_package)
            // work_package.bringToFront()



            if (boundary !== '') {
                map.removeLayer(boundary)
            }



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




            map.flyTo([parseFloat(paramY), parseFloat(paramX)], zoom, {
                duration: 1.5, // Animation duration in seconds
                easeLinearity: 0.25,
            });

            updateLayers(q_cql, baFilter);

        }


        function updateLayers(q_cql, baFilter) {



            if (cb_without_defects != '') {
                map.removeLayer(cb_without_defects)
            }
            cb_without_defects = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                layers: 'apks:cb_without_defects_2',
                format: 'image/png',
                cql_filter: q_cql,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })

            map.addLayer(cb_without_defects)
            cb_without_defects.bringToFront()


            // link box with defects ----

            if (cb_with_defects != '') {
                map.removeLayer(cb_with_defects)
            }

            cb_with_defects = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                layers: 'apks:cb_with_defects_2',
                format: 'image/png',
                cql_filter: q_cql,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })


            map.addLayer(cb_with_defects)
            cb_with_defects.bringToFront()



            // if user is not admin
            // if (ba !== '') {



            if (cb_pending != '') {
                map.removeLayer(cb_pending)
            }

            cb_pending = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                layers: 'apks:cb_pending_2',
                format: 'image/png',
                cql_filter: q_cql,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })


            map.addLayer(cb_pending)
            cb_pending.bringToFront()


            // link box Reject -----
            if (cb_reject != '') {
                map.removeLayer(cb_reject)
            }

            cb_reject = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                layers: 'apks:cb_reject_2',
                format: 'image/png',
                cql_filter: q_cql,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })


            map.addLayer(cb_reject)
            cb_reject.bringToFront()

            // link box unsurvey  -----

            if (cb_unsurveyed != '') {
                map.removeLayer(cb_unsurveyed)
            }
            cb_unsurveyed = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/apks/wms", {
                layers: 'apks:cb_unsurveyed_2',
                format: 'image/png',
                cql_filter: baFilter,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })

            map.addLayer(cb_unsurveyed)
            cb_unsurveyed.bringToFront()


            if (g5_x_5_grid) {
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

            // }

            addGroupOverLays()

        }


        // add group overlayes
        function addGroupOverLays() {
            if (layerControl != '') {
                // console.log("inmsdanssdkjnasjnd");
                map.removeControl(layerControl);
            }

            // if (ba !== '') {


            groupedOverlays = {
                "POI": {
                    'BA': boundary,
                    'Pano': pano_layer,
                    'With defects': cb_with_defects,
                    'Without defects': cb_without_defects,
                    'Unsurveyed': cb_unsurveyed,
                    '5_x_5_grid': g5_x_5_grid,
                    'Work Package': work_package,
                    'Pending': cb_pending,
                    'Reject': cb_reject
                }
            };
            // } else {

            //     groupedOverlays = {
            //         "POI": {
            //             'BA': boundary,
            //             'Pano': pano_layer,
            //             'With defects': cb_with_defects,
            //             'Without defects': cb_without_defects,
            //             'Work Package': work_package,
            //         }
            //     };

            // }
            //add layer control on top right corner of map
            layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
                collapsed: true,
                position: 'topright'
                // groupCheckboxes: true
            }).addTo(map);
        }


        function showModalData(data, id) {
            // var str = '';
            // var idSp = id.split('.');
            // var vDS = '';
            // if (data.visit_date != '' && data.visit_date != null) {
            //     var sDate = data.visit_date.split('T');
            //     console.log(sDate[0]);
            //     vDS = sDate[0]
            // }
            // var vTM = '';
            // if (data.patrol_time != '' && data.patrol_time != null) {
            //     var VTime = data.patrol_time.split('T');

            //     vTM = VTime[1]
            // }


            $('#exampleModalLabel').html("Cable Bridge Info")
            // str = `
        // <tr>
        //     <th>Zone</th>
        //     <td>${data.zone}</td>
        // </tr>
        // <tr>
        //     <th>Ba</th>
        //     <td>${data.ba}</td>
        // </tr>
        // <tr>
        //     <th>Area</th>
        //     <td>${data.area}</td>
        // </tr>
        // <tr>
        //     <th>Visit Date</th>
        //     <td>${vDS}</td>
        // </tr>
        // <tr>
        //     <th>Patrol TIme</th>
        //     <td>${vTM}</td>
        // </tr>
        // <tr>
        //     <th>Coordinate</th>
        //     <td>${data.coordinate}</td>
        // </tr>
        // <tr>
        //     <th>Created At</th>
        //     <td>${data.created_at}</td>
        // </tr>
        // <tr>
        //     <th>Detail</th>
        //     <td class="text-center">  <a href="/{{ app()->getLocale() }}/cable-bridge/${idSp[1]}" target="_blank" class="btn btn-sm btn-secondary">Detail</a></td>
        // </tr>`;

            // $("#my_data").html(str);
            // $('#myModal').modal('show');
            openDetails(data.id);

        }

        function openDetails(id) {
            // $('#myModal').modal('hide');
            $('#set-iframe').html('');
            $('#set-iframe').html(
                `<iframe src="/{{ app()->getLocale() }}/get-cable-bridge-edit/${id}" frameborder="0" style="height:700px; width:100%" ></iframe>`
            )
        }
    </script>
@endsection
