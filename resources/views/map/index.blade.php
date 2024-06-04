@extends('layouts.app', ['page_title' => 'Index'])

@section('css')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
    <script src="https://malsup.github.io/jquery.form.js"></script>
    <script>
        var $jq = $.noConflict(true);
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <link rel="stylesheet" href="{{ URL::asset('assets/lib/images_slider/css-view/lightbox.css') }}">
    <script src="{{ URL::asset('assets/lib/images_slider/js-view/lightbox-2.6.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="{{ URL::asset('assets/pannellum/pannellum.css') }}" />

    <script src="{{ URL::asset('assets/pannellum/pannellum.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::asset('assets/pannellum/lib/window-engine.css') }}" />
    <script src="{{ URL::asset('assets/pannellum/lib/window-engine.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />


    {{-- <?php # header('Access-Control-Allow-Origin: *');
    ?> --}}
    <style>
        .card-header {
            font-weight: 700;
        }

        #map {
            height: 600px;
            z-index: 1;
        }

        li {
            list-style-type: none;
            margin-bottom: 0.5rem;

        }

        ul {
            padding-left: 0.5rem;
        }


        input {
            min-width: 16px !important;
        }

        /* div#lightbox {
                        display: none;
                    } */

        .side-bar>.table td {
            padding: 0.5rem !important
        }

        #map {
            height: 600px;
            z-index: 1;
        }

        .card-header {
            font-weight: 700;
        }


        li {
            list-style-type: none;
            margin-bottom: 0.5rem;

        }

        ul {
            padding-left: 0.5rem;
        }

        .side-bar::-webkit-scrollbar {
            display: none;
        }

        #panorama {
            width: 400px;
            height: 400px;
        }

        input {
            min-width: 16px !important;
        }

        div#lightbox {
            display: none;
        }

        .side-bar>.table td {
            padding: 0.5rem !important
        }

        /* CSS for the Select2 dropdown to match form-control style */
        .select2-container {

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
        input[type="radio"]{
            border-radius: 50% !important;
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
                    <h3>3rd Party Digging</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="/{{ app()->getLocale() }}/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">map</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid bg-white pt-2">


        <div class=" p-1  col-12 m-2 ">
            <div class="card p-0 mb-3">
                <div class="card-body row">

                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label for="search_ba">BA</label>
                        <select name="search_ba" id="search_ba" class="form-control" onchange="getWorkPackage(this.value)">
                            <option value="{{ Auth::user()->ba }}" hidden>
                                {{ Auth::user()->ba != '' ? Auth::user()->ba : 'Select BA' }}</option>
                        </select>
                    </div>



                    <div class="col-md-3">
                        <label for="search_wp">Work Package</label>
                        <select name="search_wp" id="search_wp" class="form-control"></select>
                    </div>
                    <div class="col-md-2 p-2 text-center pt-4" id="for-excel">
                    </div>



                </div>
            </div>
        </div>


        <!-- MAP DASHBOARD -->


        <div class=" p-1 col-12 m-2">
            <div class="card p-0 mb-3">
                <div class="card-body row">

                    <div class="col-md-4 text-center" style="cursor: pointer;">

                        <div class=" mx-1   p-1 t" style="background-color:  #92C400 !important;color:white">
                            <p style="font-weight: 600;">Total Km</p>
                            <span id="total"></span>

                        </div>

                    </div>

                    <div class="col-md-4 text-center" style="cursor: pointer;">

                        <div class=" mx-1   p-1 t" style="background-color:  #92C400 !important;color:white">
                            <p style="font-weight: 600;">Total Notice</p>
                            <span id="total_notice"></span>

                        </div>

                    </div>
                    <div class="col-md-4 text-center" style="cursor: pointer;">

                        <div class=" mx-1   p-1 t" style="background-color:  #92C400 !important;color:white">
                            <p style="font-weight: 600;">Total Supervision</p>
                            <span id="total_supervise"></span>

                        </div>

                    </div>

                </div>
            </div>


        </div>
        <!-- END MAP DASHBOARD -->



        <!--  START MAP CARD DIV -->
        <div class="row m-2">

            <!-- START MAP SIDEBAR DIV -->
            <div class="col-2 p-0">
                <div class="card p-0 m-0"
                    style="border: 1px solid rgb(177, 175, 175) !important; border-radius: 0px !important">
                    <div class="card-header"><strong> NAVIGATION</strong></div>
                    <div class="card-body">
                        <!-- MAP SIDEBAR LAYERS SELECTOR -->
                        <div class="side-bar" style="height: 569px !important; overflow-y: scroll;">
                            <div class="col-md-12 mb-2" class="form-group">
                                <label>Select Info Layer :</label>


                                    <div class="">
                                        <input type="radio" name="select_layer" id="select_layer_wp" value="wp" onchange="selectLayer(this.value)">
                                        <label for="select_layer_wp">Work Package</label>
                                    </div>

                                    <div class="  ">
                                        <input type="radio" name="select_layer" id="select_layer_rd" value="rd" onchange="activeSelectedLayerOther(this.value)">
                                        <label for="select_layer_rd">Road</label>
                                    </div>


                                    <div class=" ">
                                        <input type="radio" name="select_layer" id="select_layer_notice" value="notice" onchange="activeSelectedLayerOther(this.value)">
                                        <label for="select_layer_notice">Notice</label>
                                    </div>

                                    <div class=" ">
                                        <input type="radio" name="select_layer" id="select_layer_supervise" value="supervise" onchange="activeSelectedLayerOther(this.value)">
                                        <label for="select_layer_supervise">Supervise</label>
                                    </div>

                                    <div class=" ">
                                        <input type="radio" name="select_layer" id="select_layer_pano" value="pano" onchange="activeSelectedLayerOther(this.value)">
                                        <label for="select_layer_pano">Pano</label>
                                    </div>

                                    <div class="  ">
                                        <input type="radio" name="select_layer" id="select_layer_substation" value="substation" onchange="activeSelectedLayerOther(this.value)">
                                        <label for="select_layer_substation">Substation</label>
                                    </div>

                                {{-- <select class="form-select" id="tableLayer" onchange="activeSelectedLayerOther(this.value)">
                                    <option value="" hidden>Select Layer</option>
                                    <option value="wp">Work Package</option>
                                    <option value="rd">Road</option>
                                    <option value="notice">Notice</option>
                                    <option value="supervise">Supervise</option>
                                    <option value="pano">Pano</option>
                                    <option value="substation">Substation</option>


                                </select> --}}
                            </div>

                            <!-- START MAP SIDEBAR DETAILS -->
                            <details class="mb-3" open>
                                <summary><strong>Patrolling 3rd Party Digging Activities</strong> </summary>
                                <table class="table table-bordered" style="cursor: pointer">
                                    <tr>
                                        <td onclick="addNotice(this)">Mengeluarkan notis</td>
                                    </tr>
                                    <tr>
                                        <td onclick="addSupervise(this)">Menyelia kerja-kerja korekan</td>
                                    </tr>
                                    <tr>
                                        <td onclick="addpanolayer(this)">Pemeriksaan di Jalan</td>
                                    </tr>
                                </table>

                            </details>




                            <!-- END MAP SIDEBAR DETAILS -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MAP SIDEBAR DIV -->

            <!-- START MAP  DIV -->
            <div class="col-10 p-0 ">
                <div class="card p-0 m-0"
                    style="border: 1px solid rgb(177, 175, 175) !important; border-radius: 0px !important;">
                    <div class="card-header text-center"><strong> MAP</strong></div>
                    <div class="card-body p-0">
                        <div id="map">

                        </div>
                    </div>
                </div>

            </div>
            <!-- END MAP  DIV -->
            <div id="wg" class="windowGroup">

            </div>

            <div id="wg1" class="windowGroup">

            </div>


        </div><!--  END MAP CARD DIV -->
        {{-- <div id="panorama"></div> --}}


    </div>
    <div class="modal fade" id="geomModal" tabindex="-1" aria-labelledby="geomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add new W.P</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/{{app()->getLocale()}}/save-work-package" method="post" id="save_wp" onsubmit="return submitFoam()">
                    @csrf
                    <div class="modal-body ">


                        <label for="">Work Package Name</label>
                        <span class="text-danger" id="er-pw-name"></span> <br>
                        <input type="text" name="name" id="pw-name" class="form-control">
                        <label for="zone">Zone</label>

                        <input type="text" name="zone" id="pw-zone" class="form-control">
                        {{-- <select name="zone" id="pw-zone" class="form-control">
                        <option value="" hidden>select zone</option>
                        <option value="W1">W1</option>
                        <option value="B1">B1</option>
                        <option value="B2">B2</option>
                        <option value="B4">B4</option>
                    </select> --}}

                        <label for="ba">Select ba</label>
                        <input type="text" name="ba" id="pw-ba" class="form-control">
                        {{-- <select name="ba" id="pw-ba" class="form-control">
                        <option value="" hidden>Select zone</option>
                    </select> --}}

                        <input type="hidden" name="geom" id="geom">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    </div>
                </form>
            </div>
        </div>
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

    <div class="modal fade" id="polyLineModal" tabindex="-1" aria-labelledby="polyLineModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Identify Roads</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/{{app()->getLocale()}}/save-road" method="post" id="road-form" onsubmit="return submitFoam2()">
                    @csrf
                    <div class="modal-body ">
                        <label for="ba">Road Name</label>
                        <span class="text-center" id="er_raod_name"></span>
                        <input name="road_name" id="road_name" class="form-control">
                        <label for="">Work Package Name</label>
                        <input type="text" name="" id="raod-d-wp-id" class="form-control disabled">
                        <input type="hidden" name="id_wp" id="raod-wp-id">
                        {{-- <select name="id_wp" id="raod-wp-id" class="form-control" onchange="getWorkPackage(this)">
                        <option value="">select wp</option>
                        @foreach ($wps as $wp)
                            <option value="{{$wp->id}}">{{$wp->package_name}}</option>
                        @endforeach
                    </select> --}}
                        <label for="polyline-zone">Zone</label>
                        <input id="polyline-zone" name="zone" class="form-control">
                        <label for="polyline-ba">BA</label>
                        <input id="polyline-ba" name="ba" class="form-control">




                        <input type="hidden" name="geom" id="road-geom">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ URL::asset('map/draw/leaflet.draw.css') }}" />

    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>

    <script src="{{ URL::asset('map/draw/leaflet.draw-custom.js') }}"></script>

    <script src="{{ URL::asset('map/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <script type="text/javascript">
        var baseLayers
        var identifyme = '';
        var substation = '';
        var groupedOverlays = '';
        var layerControl = '';

        var popup = L.popup();

        map = L.map('map').setView([3.016603, 101.858382], 5);

        var st1 = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);
        var street = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

        // ADD MAPS
        baseLayers = {
            "Satellite": st1,
            "Street": street
        };





        // ADD DRAW TOOLS

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        var drawControl = new L.Control.Draw({
            draw: {
                circle: false,
                marker: true,
                polygon: true,
                polyline: {
                    shapeOptions: {
                        color: '#f357a1',
                        weight: 10
                    }
                },
                rectangle: true
            },
            edit: {
                featureGroup: drawnItems
            }
        });

        map.addControl(drawControl);

        // END DRAW TOOLS


        // DRAW TOOL ON CREATED EVENT
        map.on('draw:created', function(e) {
            var type = e.layerType;
            layer = e.layer;
            drawnItems.addLayer(layer);
            var data = layer.toGeoJSON();

            if (e.layerType == 'polyline') {
                var coords = layer.getLatLngs();
                var length = 0;
                for (var i = 0; i < coords.length - 1; i++) {
                    length += coords[i].distanceTo(coords[i + 1]);
                }
                mapLenght = parseInt(length)

                $('#polyLineModal').modal('show');
                $('#road-geom').val(JSON.stringify(data.geometry));

                getRoadInfo(JSON.stringify(data.geometry));

            } else {

                getBaInfo(JSON.stringify(data.geometry));


                $('#geomModal').modal('show');
                $('#geom').val(JSON.stringify(data.geometry));
            }

        })
        // END DRAW TOOL ON CREATED EVENT


        // DRAW TOOL ON EDIT EVENT
        map.on('draw:edited', function(e) {
            var layers = e.layers;
            layers.eachLayer(function(data) {
                let layer_d = data.toGeoJSON();
                let layer = JSON.stringify(layer_d.geometry);
                // console.log(layer);

                $('#geomID').val(layer);

            });
        });
        // END DRAW TOOL ON EDIT EVENT

        map.on('draw:deleted', function(e) {
            var layers = e.layers;
            layers.eachLayer(function(layer) {
                $('#geomID').val('');
            });
            for (let index = 0; index < 11; index++) {
                if (index <= 9) {
                    $(`#0${index}_check`).prop('checked', false);
                } else {
                    $(`#${index}_check`).prop('checked', false);
                }

            }
        });


        // ADD LAYERS


        boundary3 = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
            layers: 'cite:aero_apks',
            format: 'image/png',
            maxZoom: 21,
            transparent: true
        }, {
            buffer: 10
        })


        pano_layer = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
            layers: 'cite:pano_apks',
            format: 'image/png',
            maxZoom: 21,
            transparent: true
        }, {
            buffer: 10
        }).addTo(map);




        var bangi_status = false;
        var addTOmap = false;



        map.addLayer(boundary3)
        map.setView([2.59340882301331, 101.07054901123], 8);


        var boundary2 = '';
        var wp = '';
        var rd = '';
        var zoom = 8;



        function addRemoveBundary(param, paramY, paramX) {

            map.removeLayer(boundary3)


            if (boundary2 !== '') {
                map.removeLayer(boundary2)
            }
            boundary2 = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
                layers: 'cite:ba',
                format: 'image/png',
                cql_filter: "station ILIKE '%" + param + "%'",
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })
            map.addLayer(boundary2)
            boundary2.bringToFront()

            map.flyTo([parseFloat(paramY), parseFloat(paramX)], zoom, {
                duration: 1.5,
                easeLinearity: 0.25,
            });

            if (wp != '') {
                map.removeLayer(wp)
            }

            // workk package
            wp = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
                layers: 'cite:tbl_workpackage',
                format: 'image/png',
                cql_filter: "ba ILIKE '%" + param + "%'",
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
                cql_filter: "ba ILIKE '%" + param + "%'",
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })
            map.addLayer(rd)
            rd.bringToFront()


            if (pano_layer !== '') {
                map.removeLayer(pano_layer)
            }
            pano_layer = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
        layers: 'cite:pano_apks',
        format: 'image/png',
        cql_filter: "ba ILIKE '%" + param + "%'",
        maxZoom: 21,
        transparent: true
    }, {
        buffer: 10
    });
    map.addLayer(pano_layer);
    map.addLayer(pano_layer)
            // if (substation != '') {
            //     map.removeLayer(substation)
            // }
            // substation = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
            //     layers: 'cite:tbl_substation',
            //     format: 'image/png',
            //     cql_filter: "ba ILIKE '%" + param + "%'",
            //     maxZoom: 21,
            //     transparent: true
            // }, {
            //     buffer: 10
            // })

            // map.addLayer(substation)
            // substation.bringToFront()
            addLayerControl()
        }



        var panolayer = true;
        var selectedId = '';


        function preNext(status) {
            $("#wg").html('');
            $.ajax({
                url: '/{{ app()->getLocale() }}/preNext/' + selectedId + '/' + status,
                dataType: 'JSON',
                //data: data,
                method: 'GET',
                async: false,
                success: function callback(data) {

                    //  alert(data
                    var str = '<div id="window1" class="window">' +
                        '<div class="green">' +
                        '<p class="windowTitle">Pano Images</p>' +
                        '</div>' +
                        '<div class="mainWindow">' +
                        // '<canvas id="canvas" width="400" height="480">' +
                        // '</canvas>' +
                        '<div id="panorama" width="400px" height="480px"></div>' +
                        '<div class="row"><button style="margin-left: 30%;" onclick=preNext("pre") class="btn btn-success">Previous</button><button  onclick=preNext("next")  style="float: right;margin-right: 35%;" class="btn btn-success">Next</button></div>'
                    '</div>' +
                    '</div>'

                    $("#wg").html(str);

                    createWindow(1);
                    console.log(data)
                    selectedId = data[0].gid
                    pannellum.viewer('panorama', {
                        "type": "equirectangular",
                        "panorama": data[0].photo,
                        "compass": true,
                        "autoLoad": true
                    });
                    $('.windowTitle').html(`Pano Images ( LAT : ${(e.latlng.lat).toFixed(2)} , LNG : ${(e.latlng.lng).toFixed(2)} )`)

                    if (identifyme != '') {
                        map.removeLayer(identifyme)
                    }
                    identifyme = L.geoJSON(JSON.parse(data[0].geom)).addTo(map);


                }
            });

        }




        function addpanolayer(event) {



            map.off('click');

            map.on('click', function(e) {


                //map.off('click');
                $("#wg").html('');
                // Build the URL for a GetFeatureInfo
                var url = getFeatureInfoUrl(
                    map,
                    pano_layer,
                    e.latlng, {
                        'info_format': 'application/json',
                        'propertyName': 'NAME,AREA_CODE,DESCRIPTIO'
                    }
                );
                var secondUrl = encodeURIComponent(url)

                $.ajax({
                        url: '/{{ app()->getLocale() }}/proxy/' + encodeURIComponent(secondUrl),
                        dataType: 'json',
                        method: 'GET',
                    })
                    .done(function(data) {
                        var deco = JSON.parse(data)
                        console.log(deco.features[0]);
                        if (deco && deco.features && deco.features.length !== undefined) {
                            // Create the panorama viewer

                            var str = '<div id="window1" class="window">' +
                                '<div class="green">' +
                                '<p class="windowTitle">Pano Images</p>' +
                                '</div>' +
                                '<div class="mainWindow">' +
                                // '<canvas id="canvas" width="400" height="480">' +
                                // '</canvas>' +
                                '<div id="panorama" width="400px" height="480px"></div>' +
                                '<div class="row"><button style="margin-left: 30%;" onclick=preNext("pre") class="btn btn-success">Previous</button><button  onclick=preNext("next")  style="float: right;margin-right: 35%;" class="btn btn-success">Next</button></div>'

                            '</div>' +
                            '</div>'

                            $("#wg").html(str);



                            //   console.log(data)
                            //if(deco.features.length!=0){
                            createWindow(1);

                            var windowPosition = map.latLngToContainerPoint(e.latlng);
        // $('#window1').css({
        //     'position': 'absolute',
        //     'left': windowPosition.x + 'px',
        //     'top': windowPosition.y + 'px'
        // });
                            selectedId = deco.features[0].id.split('.')[1];

                            pannellum.viewer('panorama', {
                                "type": "equirectangular",
                                "panorama": deco.features[0].properties.photo,
                                "compass": true,
                                "autoLoad": true
                            });

                            if (identifyme !== '') {
                                map.removeLayer(identifyme);
                            }
                            $('.windowTitle').html(`Pano Images ( LAT : ${(e.latlng.lat).toFixed(2)} , LNG : ${(e.latlng.lng).toFixed(2)} )`)

                            identifyme = L.geoJSON(deco.features[0].geometry).addTo(map);
                        } else {
                            console.log(
                                'Data or data.features is undefined or does not have a valid length property.'
                            );
                        }
                    })
                    .fail(function(error) {
                        console.log('Error: ', error);
                    });




            });
        }



        function activeSelectedLayerOther(val) {


            var sel_lyr = ''

            if (val == 'rd') {
                sel_lyr = rd;

            }
            if (val == 'wp') {
                sel_lyr = wp;

            }
            if (val == 'notice') {
                sel_lyr = notice;

            }
            if (val == 'supervise') {
                sel_lyr = supervise;

            }
            if (val == 'substation') {
                sel_lyr = substation;

            }
            if (val == "pano") {
                addpanolayer();
                return;
            }
            map.off('click');
            map.on('click', function(e) {
                if (val == 'substation' || val == 'supervise' || val == 'notice') {
                popup
        .setLatLng(e.latlng)
        .setContent("You clicked the map at " + e.latlng.toString())
        .openOn(map);
                }
                var url = getFeatureInfoUrl(
                    map,
                    sel_lyr,
                    e.latlng, {
                        'info_format': 'application/json',
                        'propertyName': 'NAME,AREA_CODE,DESCRIPTIO'
                    }
                );
                var secondUrl = encodeURIComponent(url)

                $.ajax({
                    url: '/{{ app()->getLocale() }}/proxy/' + encodeURIComponent(secondUrl),
                    dataType: 'JSON',
                    //data: data,
                    method: 'GET',
                    async: false,
                    success: function callback(data1) {
                        console.log(data1)
                        data = JSON.parse(data1)
                        if (data.features.length != 0) {
                            var str = '';
                            var splitKey = '';
                            for (key in data.features[0].properties) {

                                splitKey = key.split("_");
                                str += '<tr>';


                                str +=
                                    `<th class="text-capitalize">${splitKey.map(part => part.charAt(0).toUpperCase() + part.slice(1)).join(' ')}</th>`;
                                if (key == 'during_image1' || key == 'during_image2' || key ==
                                    'during_image3' || key ==
                                    'before_image1' || key == 'before_image2' || key ==
                                    'before_image3' || key ==
                                    'after_image1' || key == 'after_image2' || key == 'after_image3') {

                                    if (data.features[0].properties[key] == '') {
                                        str = str + `<td>no image found</td></tr>`;
                                    } else {
                                        str = str + `<td><a href="http://` + window.location.host + `/${data.features[0].properties[key]}" data-lightbox="roadtrip">
                                                    <img src="http://` + window.location.host +
                                            `/${data.features[0].properties[key]}" alt=""
                                                    width="20px" height="20px" class="adjust-height ml-5  "></a></td></tr>`;
                                    }
                                    // str = str + '<tr><td>' + key + '</td><td><a href="' + data.features[
                                    //         0].properties[key] +
                                    //     '" class=\'example-image-link\' data-lightbox=\'example-set\' title=\'&lt;button class=&quot;primary &quot; onclick= rotate_img(&quot;pic1&quot)  &gt;Rotate image&lt;/button&gt;\'><img src="' +
                                    //     data.features[0].properties[key] +
                                    //     '" width="20px" height="20px"></a></td></tr>'

                                } else {
                                    str = str + `<td>${data.features[0].properties[key]}</td></tr>`;
                                }


                            }
                            if ($('#tableLayer').val() == 'supervise' || $('#tableLayer').val() ==
                                'notice') {
                                str = str +
                                    `<tr><td> Report</td><td> <a href="/{{ app()->getLocale() }}/generate-third-party-pdf/${data.features[0].properties.id}" target="_blank"><button class="btn btn-sm btn-success">Download</button></a></td></tr>`

                            }

                            $("#my_data").html(str);
                            $('#myModal').modal('show');
                            if (identifyme != '') {
                                map.removeLayer(identifyme)
                            }
                            var myStyle = {
                                "fillColor": "#ff7800"
                            };
                            identifyme = L.geoJSON(data.features[0].geometry, {
                                style: myStyle
                            }).addTo(map);

                        }

                    }
                });


            });
        }



        function getFeatureInfoUrl(map, layer, latlng, params) {

            var point = map.latLngToContainerPoint(latlng, map.getZoom()),
                size = map.getSize(),

                params = {
                    request: 'GetFeatureInfo',
                    service: 'WMS',
                    srs: 'EPSG:4326',
                    styles: layer.wmsParams.styles,
                    transparent: layer.wmsParams.transparent,
                    version: layer._wmsVersion,
                    format: layer.wmsParams.format,
                    bbox: map.getBounds().toBBoxString(),
                    height: size.y,
                    width: size.x,
                    layers: layer.wmsParams.layers,
                    query_layers: layer.wmsParams.layers,
                    info_format: 'application/json'
                };

            params[params.version === '1.3.0' ? 'i' : 'x'] = parseInt(point.x);
            params[params.version === '1.3.0' ? 'j' : 'y'] = parseInt(point.y);

            // return this._url + L.Util.getParamString(params, this._url, true);

            var url = layer._url + L.Util.getParamString(params, layer._url, true);
            if (typeof layer.wmsParams.proxy !== "undefined") {


                // check if proxyParamName is defined (instead, use default value)
                if (typeof layer.wmsParams.proxyParamName !== "undefined")
                    layer.wmsParams.proxyParamName = 'url';

                // build proxy (es: "proxy.php?url=" )
                _proxy = layer.wmsParams.proxy + '?' + layer.wmsParams.proxyParamName + '=';

                url = _proxy + encodeURIComponent(url);

            }

            return url.toString();

        }




        function addLayerControl() {
            if (layerControl != '') {
                map.removeControl(layerControl);
            }

            // ADD LAYERS GROUPED OVER LAYS
            groupedOverlays = {
                "POI": {
                    'BA': boundary2,
                    "Work Package": wp,
                    "Roads": rd,
                    "Pano ": pano_layer,
                    // 'Subsation': substation,

                }
            };

            layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
                collapsed: true,
                position: 'topright'
                // groupCheckboxes: true
            }).addTo(map);

        }


        var notice = '';
        var supervise = '';

        function addNotice(event) {
            if (notice == '') {
                notice = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
                    layers: 'cite:diging_notice',
                    format: 'image/png',
                    // cql_filter: "ba='" + param + "'",
                    maxZoom: 21,
                    transparent: true
                }, {
                    buffer: 10
                })
                map.addLayer(notice)
                notice.bringToFront()
                $(event).css('background', '#c9def2');
            } else {
                map.removeLayer(notice);
                notice = '';
                $(event).css('background', 'white');
            }

        }


        function addSupervise(event) {
            if (supervise == '') {
                supervise = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
                    layers: 'cite:diging_supervise',
                    format: 'image/png',
                    // cql_filter: "ba='" + param + "'",
                    maxZoom: 21,
                    transparent: true
                }, {
                    buffer: 10
                })
                map.addLayer(supervise)
                supervise.bringToFront()
                $(event).css('background', '#c9def2');
            } else {
                map.removeLayer(supervise);
                supervise = '';
                $(event).css('background', 'white');
            }

        }


        function zoomToxy(x, y) {
            map.setView([y, x], 16)
        }
    </script>


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
            ['B4', 'PUTRAJAYA & CYBERJAYA', 2.92875032271019, 101.675338316575],
            ['B4', 'SEPANG', 2.734218580014375, 101.69394518452967],
            ['B4', 'PUCHONG', 2.971632230751114, 101.62918173453126]
        ];
        const userBa = "{{ Auth::user()->ba }}";
        $(document).ready(function() {

            // check ba is empty or not
            if (userBa == '') {
                addRemoveBundary('', 2.75101756479656, 101.304931640625)
            } else {
                getWorkPackage(userBa);
            }

            $('#search_wp').select2();


            option = {
                success: callbackSuccess
            }
            //submit foam using ajax
            $jq('#save_wp').ajaxForm(option
                //     function() {
                //     alert("foam submitted!");
                //     $('#geomModal').modal('hide');
                //     map.removeLayer(drawnItems);
                // }
            );

            function callbackSuccess(rs) {
                console.log(rs);
                alert("foam submitted!");
                $('#geomModal').modal('hide');
                $("#pw-name").val('')
                map.removeLayer(drawnItems);
            }

            //submit foam 2 using ajax

            $jq('#road-form').ajaxForm(function() {
                alert("foam submitted!");
                $('#polyLineModal').modal('hide');
                $('#road_name').val('')
                map.removeLayer(drawnItems);
            });



            $('#ba').on('change', function() {
                $('#pw-ba').val(this.value)
            })


        })




        function onChangeZone(param) {
        const areaSelect = $('#search_ba');

        // Clear previous options
        areaSelect.empty();
        areaSelect.append(`<option value="" hidden>Select ba</option>`)


        b1Options.map((data) => {
            if (data[0] == param) {
                areaSelect.append(`<option value="${data[1]}">${data[1]}</option>`)
            }
        });
        $('#search_wp').empty();
                $('#search_wp').append(`<option value="" hidden>Select Work Package</option>`);
                $('#for-excel').html('')

    }



        function getWorkPackage(param) {
            clearFields()
            var selectBA = '';
            for (const data of b1Options) {
                if (data[1] == param) {
                    selectBA = data;
                    break;
                }
            }
            zoom = 11;
            addRemoveBundary(selectBA[1], selectBA[2], selectBA[3])
            var zone = $('#search_zone').val();
            $.ajax({
                url: `/{{ app()->getLocale() }}/get-work-package/${selectBA[1]}/${selectBA[0]}`,
                dataType: 'JSON',
                method: 'GET',
                async: false,
                success: function callback(data) {
                    console.log(data);
                    $('#search_wp').empty();
                    $('#search_wp').append(`<option value="%,101.07054901123,2.59340882301331,%" hidden>Select Work Package</option>`);
                    data.forEach((val) => {

                        $('#search_wp').append(
                            `<option value="${val.id},${val.x},${val.y},${val.package_name}">${val.package_name}</option>`
                        );
                    });
                    $('#for-excel').html('')

                }
            })

            $.ajax({
                url: `/{{ app()->getLocale() }}/getStats/${selectBA[1]}`,
                dataType: 'JSON',
                method: 'GET',
                async: false,
                success: function callback(data) {
                    console.log(data);
                    $("#total").html(Number((data[0].distance)).toFixed(1));
                    $("#total_notice").html((data[1].count))
                    $("#total_supervise").html((data[2].count))


                }
            })

        }

        var filter_wp='';
        $('#search_wp').on('change', function() {
            const selectedValue = this.value;
            var spiltVal = selectedValue.split(',');

           zoomToxy(parseFloat(spiltVal[1]), parseFloat(spiltVal[2]))

            $('#for-excel').html(`<a class="mt-4" href="/{{ app()->getLocale() }}/generate-third-party-diging-excel/${spiltVal[0]}"><button class="btn-sm mt-2
                btn btn-primary">Download Qr</button></a>`)


            if (rd != '') {
                map.removeLayer(rd)
            }
            //road wms layer
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

        if(wp){
        map.removeLayer(wp);
        }
        if(filter_wp){
            map.removeLayer(filter_wp);
        }
            filter_wp = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
                layers: 'cite:tbl_workpackage',
                format: 'image/png',
                cql_filter: "package_name='" + spiltVal[3] + "'",
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })
            map.addLayer(filter_wp)
            filter_wp.bringToFront()



        })

        function clearFields() {
            $("#total").html('');
            $("#total_notice").html('')
            $("#total_supervise").html('')
            $('#for-excel').html('')
        }




        function getBaInfo(param) {
            // console.log(param);
            $.ajax({
                url: `/{{app()->getLocale()}}/get-ba-info`,
                dataType: 'JSON',
                method: 'POST',
                async: false,
                data: {
                    'geom': param
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function callback(data) {

                    $('#pw-zone').val(data[0].ppb_zone)
                    $('#pw-ba').val(data[0].station)

                },

                error: function errorCallback(xhr, status, error) {
                    console.log(error);
                },
            })
        }



        function getRoadInfo(param) {
            console.log(param);
            $.ajax({
                url: `/{{app()->getLocale()}}/get-raod-info`,
                dataType: 'JSON',
                method: 'POST',
                async: false,
                data: {
                    'geom': param
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function callback(data) {
                    console.log(data);
                    $('#polyline-zone').val(data.data[0].zone)
                    $('#polyline-ba').val(data.data[0].ba)
                    $('#raod-wp-id').val(data.data[0].id)
                    $('#raod-d-wp-id').val(data.data[0].package_name)

                },

                error: function errorCallback(xhr, status, error) {
                    console.log(error);
                },
            })
        }


        function submitFoam() {
            if ($('#pw-name').val() == '') {
                $('#er-pw-name').html("This feild is required");
                return false;
            }
            $('#er-pw-name').html("");
        }

        function submitFoam2() {
            if ($('#road_name').val() == '') {
                $('#er_raod_name').html("This feild is required");
                return false;
            }
            $('#er_raod_name').html("");
        }
    </script>
@endsection
