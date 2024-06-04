@extends('layouts.app', ['page_title' => 'Index'])
@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>




    <style>
        #map {
            height: 400px;
            z-index: 1;
        }

        .leaflet-control-attribution.leaflet-control {
            display: none;
        }
    </style>


    @guest
        <style>
            body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .content-wrapper,
            body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer,
            body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-header {
                transition: margin-left .3s ease-in-out;
                margin-left: 0px !important;
            }

            .content-header,
            .fa-bars {
                display: none
            }
        </style>
    @endguest
@endsection



@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row " style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>APKS</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="/{{app()->getLocale()}}/get-all-work-packages">index</a></li>
                        <li class="breadcrumb-item active">detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>




    <section class="content mt-2">
        <div class="container-fluid">
            @include('components.message')
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between ">
                            <p class="mb-0">Workpackage Detail</p>
                            <div class="d-flex ml-auto">


                                <button type="button" id="wp_status"
                                class="btn  btn-secondary form-control " data-toggle="dropdown">
                                {{ $rec->wp_status }}
                            </button>
                            <div class="dropdown-menu" role="menu">
                                @if ($rec->wp_status != 'approved')
                                <a href="/{{app()->getLocale()}}/sbum-status/{{$rec->id}}/approved"> <button type="button" class="dropdown-item pl-3 w-100 text-left"
                                        value="approved"
                                        onclick="return confirm('are you sure')">Approved</button></a>
                                @endif
                                @if ($rec->wp_status != 'rejected')
                                   <a href="/{{app()->getLocale()}}/sbum-status/{{$rec->id}}/rejected"> <button type="button" class="dropdown-item pl-3 w-100 text-left"

                                    onclick="return confirm('are you sure') " >Rejected</button></a>
                                @endif
                                @if ($rec->wp_status != 'pending')
                                <a href="/{{app()->getLocale()}}/sbum-status/{{$rec->id}}/pending">   <button type="button" class="dropdown-item pl-3 w-100 text-left"
                                        value="pending"
                                        onclick="return confirm('are you sure') ">Pending</button></a>
                                @endif



                            </div>
                        </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4"><label for=""> Name</label></div>
                                <div class="col-md-4"><input disabled class="form-control" value="{{ $rec->package_name }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="">Zone</label></div>
                                <div class="col-md-4"><input disabled class="form-control" value="{{ $rec->zone }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="">BA</label></div>
                                <div class="col-md-4"><input disabled class="form-control" value="{{ $rec->ba }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="">Created At</label></div>
                                <div class="col-md-4"><input disabled class="form-control" value="{{ $rec->created_at }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="">No of Roads</label></div>
                                <div class="col-md-4"><input disabled class="form-control" value="{{ $count }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="">Remarks</label></div>
                                <div class="col-md-4"><input disabled class="form-control" value="{{ $rec->remarks }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="">Status</label></div>
                                <div class="col-md-4"> <button type="button" id="wp_status"
                                        class="btn  btn-secondary form-control mb-3" data-toggle="dropdown">
                                        {{ $rec->wp_status }}
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        @if ($rec->wp_status != 'approved')
                                        <a href="/{{app()->getLocale()}}/sbum-status/{{$rec->id}}/approved"> <button type="button" class="dropdown-item pl-3 w-100 text-left"
                                                value="approved"
                                                onclick="return confirm('are you sure')">Approved</button></a>
                                        @endif
                                        @if ($rec->wp_status != 'rejected')
                                           <a href="/{{app()->getLocale()}}/sbum-status/{{$rec->id}}/rejected"> <button type="button" class="dropdown-item pl-3 w-100 text-left"

                                            onclick="return confirm('are you sure') " >Rejected</button></a>
                                        @endif
                                        @if ($rec->wp_status != 'pending')
                                        <a href="/{{app()->getLocale()}}/sbum-status/{{$rec->id}}/pending">   <button type="button" class="dropdown-item pl-3 w-100 text-left"
                                                value="pending"
                                                onclick="return confirm('are you sure') ">Pending</button></a>
                                        @endif



                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="">Total Distance KM</label></div>
                                <div class="col-md-4"><input disabled class="form-control"
                                        value="{{ number_format($distance, 2) }}">
                                </div>
                            </div>


                            <h4 class="text-center mt-3">Road Details</h4>
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </section>


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
@endsection



@section('script')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>

    <script type="text/javascript">
        var baseLayers
        var identifyme = '';
        map = L.map('map').setView([{{ $wp->y }}, {{ $wp->x }}], 14);

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

        wp = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
            layers: 'cite:tbl_workpackage',
            format: 'image/png',
            cql_filter: "package_name='{{ $rec->package_name }}'",
            maxZoom: 21,
            transparent: true
        }, {
            buffer: 10
        })

        wp.addTo(map)

        rd = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
            layers: 'cite:tbl_roads',
            format: 'image/png',
            cql_filter: "id_workpackage='{{ $rec->id }}'",
            maxZoom: 21,
            transparent: true
        }, {
            buffer: 10
        })

        rd.addTo(map)

        var sel_lyr = rd;
        map.on('click', function(e) {
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
                url: '/proxy/' + encodeURIComponent(secondUrl),
                dataType: 'JSON',
                //data: data,
                method: 'GET',
                async: false,
                success: function callback(data1) {
                    console.log(data1)
                    data = JSON.parse(data1)
                    if (data.features.length != 0) {
                        var str = '';
                        for (key in data.features[0].properties) {
                            //console.log(key);
                            //console.log(data.features[0].properties[key]);
                            if (key == 'image_1' || key == 'image_2' || key == 'image_3' || key ==
                                'image_4' || key == 'image_5' || key == 'image_6' || key ==
                                'image_7' || key == 'image_8' || key == 'image_9' || key ==
                                'image_10') {
                                str = str + '<tr><td>' + key + '</td><td><a href="' + data.features[
                                        0].properties[key] +
                                    '" class=\'example-image-link\' data-lightbox=\'example-set\' title=\'&lt;button class=&quot;primary &quot; onclick= rotate_img(&quot;pic1&quot)  &gt;Rotate image&lt;/button&gt;\'><img src="' +
                                    data.features[0].properties[key] +
                                    '" width="20px" height="20px"></a></td></tr>'

                            } else {
                                str = str + '<tr><td>' + key + '</td><td>' + data.features[0]
                                    .properties[key] + '</td></tr>'
                            }
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



        function changeStatus(event, id) {
            var status = event.value;
            console.log(status);
            $('#wp_status').html(status)
            $.ajax({
                url: `/sbum-status/${id}/${status}`,
                dataType: 'JSON',
                method: 'GET',
                success: function callback(data) {

              alert('Status Change Successfully')
                }
            })
        }
    </script>
@endsection
