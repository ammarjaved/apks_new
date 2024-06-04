@extends('layouts.app', ['page_title' => 'Index'])

@section('css')

@include('partials.map-css')
<style>#map{
    height: 600px ;
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
                    <h3>Map</h3>
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


    <div class=" p-1 col-12 m-2">
        <div class="card p-0 mb-3">
            <div class="card-body row">

                <div class="col-md-3">
                    <label for="search_zone">Zone</label>
                    <select name="search_zone" id="search_zone" class="form-control">

                        <option value="" hidden>select zone</option>
                        <option value="W1">W1</option>
                        <option value="B1">B1</option>
                        <option value="B2">B2</option>
                        <option value="B4">B4</option>

                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search_ba">Ba</label>
                    <select name="search_ba" id="search_ba" class="form-control" onchange="getWorkPackage(this)">
                        <option value="">Select zone</option>
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
                            <select class="form-select" id="tableLayer" onchange="activeSelectedLayerOther(this.value)">
                                <option value="" hidden>Select Layer</option>
                                <option value="lv_fuse">lv_fuse</option>
                                <option value="lv_ug_conductor">lv_ug_conductor</option>
                                <option value="lvdb_fp">lvdb_fp</option>
                                <option value="street_light">street_light</option>
                                <option value="pole">pole</option>
                                <option value="wp">wp</option>
                                <option value="notice">notice</option>
                                <option value="supervise">supervise</option>

                            </select>
                        </div>

                        <!-- START MAP SIDEBAR DETAILS -->




                        <details class="mb-3" open>
                            <summary><strong>Feeder Pillar</strong> </summary>
                            <table class="table table-bordered">
                                <tr>
                                    <td>Pemeriksaan visual</td>
                                </tr>
                                <tr>
                                    <td>Pembersihan iklan haram/banner</td>
                                </tr>
                                <tr>
                                    <td>Report</td>
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
                <form action="/save-work-package" method="post" id="save_wp" onsubmit="return submitFoam()">
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
                <form action="/save-road" method="post" id="road-form" onsubmit="return submitFoam2()">
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

<script type="text/javascript">
    var baseLayers
    var identifyme = '';
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




    // ADD LAYERS GROUPED OVER LAYS
    groupedOverlays = {
        "POI": {
        }
    };

    var layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
        collapsed: true,
        position: 'topright'
        // groupCheckboxes: true
    }).addTo(map);






</script>


@endsection
