@extends('layouts.app', ['page_title' => 'Index'])

@section('css')
    <!-- @include('partials.map-css') -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>



    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <style>
        select.custom-select.custom-select-sm.form-control.form-control-sm {
            width: 65px !important;
            min-width: 20px !important;
        }

        .collapse {
            visibility: visible;
        }

        input {
            min-width: 16px !important;
        }


        #map {
            height: 800px;
        }

        th,
        td {
            font-size: 13px !important;
            padding: 5px !important;
        }

        th {
            font-size: 14px !important
        }

        #detail-card {
            max-height: 800px !important;
            overflow-y: scroll
        }
    </style>
@endsection



@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row mb-2" style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{ __('messages.Patrolling') }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a
                                href="/{{ app()->getLocale() }}/dashboard">{{ __('messages.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.index') }} </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <section class="content">
        <div class="container-fluid">



            @include('components.message')




            {{-- DATA TABLE --}}




            {{-- BA ZONE SEARCH FILTER --}}
            <form action="{{ route('generate-patrolling-excel', app()->getLocale()) }}" method="post">
                @csrf
                <div class="form-input card p-0 mb-3">
                    <div class="card-body row">


                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <label for="search_ba">BA</label>
                            <select name="excelBa" id="search_ba" class="form-control"
                                onchange="callPatrlloingLayer(this.value)">

                                <option value="{{ Auth::user()->ba }}" hidden>
                                    {{ Auth::user()->ba != '' ? Auth::user()->ba : 'Select BA' }}</option>
                            </select>
                        </div>


                        <div class=" col-md-2">
                            <label for="excel_from_date">From Date : </label>
                            <input type="date" name="excel_from_date" id="excel_from_date" class="form-control"
                                onchange="setMinDate(this.value)">
                        </div>
                        <div class=" col-md-2">
                            <label for="excel_to_date">To Date : </label>
                            <input type="date" name="excel_to_date" id="excel_to_date" onchange="setMaxDate(this.value)"
                                class="form-control">
                        </div>

                        <div class="col-md-2 pt-2 ">

                            <button type="submit" class="btn text-white btn-sm mt-4 " class="form-control"
                                style="background-color: #708090">Download QR </button>
                        </div>

                        <div class="col-md-6">
                            <br>
                            <div class="d-flex">
                                <button class="btn btn-secondary btn-sm mt-2   m-2 " type="button"
                                    onclick="removeLines()">Clear
                                    Lines</button>
                                <button class="btn btn-secondary btn-sm mt-2   m-2 " type="button"
                                    onclick="removePoint()">Clear
                                    Points</button>
                                <button class="btn bt-sm btn-secondary mt-2 m-2" type="button"
                                    onclick="resetPatrlloingMapFilters()"> Reset</button>
                            </div>

                        </div>



                    </div>
                </div>
            </form>


            <div class="row">


                {{-- MAP --}}
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
                    <div class="card">

                        <div class="card-header d-flex justify-content-between ">
                            <p class="mb-0">{{ __('messages.Patrolling') }}</p>
                            <div class="d-flex ml-auto">
                                {{-- <button class="btn text-white  btn-sm mr-4" type="button" data-toggle="collapse"
                                    style="background-color: #708090" data-target="#collapseQr" aria-expanded="false"
                                    aria-controls="collapseQr">
                                    QR Patrolling
                                </button> --}}
                            </div>
                        </div>


                        <div class="card-body" id="detail-card">
                            <div class="text-right mb-4">

                            </div>



                            <div class="table-responsive add-substation" id="add-substation">
                                <table id="" class="table table-bordered  table-hover data-table">


                                    <thead style="background-color: #E4E3E3 !important">
                                        <tr>
                                            <th class="text-center">WP NAME</th>
                                            <th class="text-center">BA</th>
                                            <th class="text-center">CYCLE</th>
                                            <th class="text-center">READING START</th>
                                            <th class="text-center">READING END</th>
                                            <th class="text-center">TOATL PATROLLING (KM)</th>
                                            <th class="text-center">PATROLLING DATE</th>
                                            <th class="text-center">PATROLLING TIME</th>
                                            <th>STATUS</th>

                                            <th class="tex-center">IMAGE READING START</th>
                                            <th class="tex-center">IMAGE READING END</th>
                                            @if (Auth::user()->ba !== '')
                                            <th >QA Status</th>
                                            @endif

                                            {{-- <th class="text-center">PATROLLING PATH START</th>
                                        <th class="text-center">PATROLLING PATH END</th> --}}

                                            <th class="text-center">PATROLLING PATH</th>




                                        </tr>

                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>






                        </div>
                    </div>



                </div>
                <!-- END MAP  DIV -->
                <!-- <div id="wg" class="windowGroup">

          </div>

          <div id="wg1" class="windowGroup">

          </div> -->


            </div>

        </div>
    </section>
@endsection


@section('script')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>


    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/patrolling.js') }}"></script>

    @include('partials.map-js')



{{-- some javascript code is in public/assets/js/patrolling.js --}}

    <script type="text/javascript">
        var lang = "{{ app()->getLocale() }}";
        var url = "patrolling";
        var auth_ba = "{{Auth::user()->ba}}";

            //this function just add and remove boundary
        function addRemoveBundary(param, paramY, paramX)
        {

            var q_cql = "ba ILIKE '%" + param + "%' "
            if (from_date != '') {
                q_cql = q_cql + "AND visit_date>=" + from_date;
            }
            if (to_date != '') {
                q_cql = q_cql + "AND visit_date<=" + to_date;
            }
            if (boundary !== '') {
                map.removeLayer(boundary)
            }


            boundary = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
                layers: 'cite:ba',
                format: 'image/png',
                cql_filter: "station ILIKE '%" + param + "%'",
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


            if (patroling !== '') {
                map.removeLayer(patroling)
            }


            patroling = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
                layers: 'cite:patroling_lines',
                format: 'image/png',
                cql_filter: q_cql,
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })
            map.addLayer(patroling)
            patroling.bringToFront()

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
            // map.addLayer(pano_layer);


            addpanolayer();
            addGroupOverLays()

            if (patrol) {
                for (let i = 0; i < patrol.length; i++) {
                    if (patrol[i] != '') {
                        map.removeLayer(patrol[i])
                    }
                }
            }

        }



            // add group over layes
        function addGroupOverLays()
        {
            if (layerControl != '') {
                map.removeControl(layerControl);
            }

            groupedOverlays = {
                "POI": {
                    'Boundary': boundary,
                    'Patrolling': patroling,
                    'Pano': pano_layer
                }
            };
            //add layer control on top right corner of map
            layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
                collapsed: true,
                position: 'topright'
                // groupCheckboxes: true
            }).addTo(map);
        }

    </script>




{{-- DATA TABLE --}}
    <script>



        var table = '';



        $(function() {

            var columns =  [{
                        data: 'wp_name',
                        name: 'wp_name'
                    },
                    {
                        data: 'ba',
                        name: 'ba'
                    },
                    {
                        data: 'cycle',
                        name: 'cycle'
                    },
                    {
                        data: 'reading_start',
                        name: 'reading_start'
                    },
                    {
                        data: 'reading_end',
                        name: 'reading_end'
                    },
                    {
                        data: 'km',
                        name: 'km',
                        render: function(data, type, row) {
                            if (data === null || data === '') {
                                return '';
                            }

                            return parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'time',
                        name: 'time',
                        render: function(data, type, row) {
                            if (data === null || data === '') {
                                return '';
                            }
                            let time = data.split(" ");
                            return time[0];
                        }
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        render: function(data, type, full) {
                            if (full.image_reading_start !== '') {
                                return ` <a href="{{ config('globals.APP_IMAGES_URL').'${full.image_reading_start}'}}" data-lightbox="roadtrip">
                                    <img height="70" src="{{ config('globals.APP_IMAGES_URL').'${full.image_reading_start}' }}" ></a>`;
                            }
                            return `<td></td>`;
                        },
                        name: 'image_reading_start'
                    },
                    {
                        render: function(data, type, full) {
                            if (full.image_reading_end !== '') {
                                return ` <a href="{{ config('globals.APP_IMAGES_URL').'${full.image_reading_end}' }}" data-lightbox="roadtrip">
                                    <img height="70" src="{{ config('globals.APP_IMAGES_URL').'${full.image_reading_end}'}}" ></a>`;
                            }
                            return `<td></td>`;
                        },
                        name: 'image_reading_end'
                    }
                ];

    //             if (auth_ba !== '') {
    //     columns.push({ data: null, render: renderQaStatus });
    // }

    columns.push({ data: null, render: renderDropDownActions });

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                orderable: true,

                ajax: {
                    url: '{{ route('patrolling-paginate', app()->getLocale()) }}',
                    type: "GET",
                    data: function(d) {
                        if (from_date) {
                            d.from_date = from_date;
                        }

                        if (excel_ba) {
                            d.ba = excel_ba;
                        }

                        if (to_date) {
                            d.to_date = to_date;
                        }
                    }
                },
                columns: columns,
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(1)').addClass('text-center');
                    $(row).find('td:eq(2)').addClass('text-center');
                    $(row).find('td:eq(3)').addClass('text-center');
                    $(row).find('td:eq(4)').addClass('text-center');
                    $(row).find('td:eq(5)').addClass('text-center');
                    $(row).find('td:eq(10)').addClass('text-center');




                }
            });

        });
    </script>


{{-- END DATA TABLE --}}
@endsection
