@extends('layouts.app')
@section('css')
    @include('partials.map-css')
    {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> --}}
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        h3 {
            font-weight: 600
        }

        .collapse .card-body {
            padding: 0px !important
        }

        h3 {
            color: #7379AE;
            font-size: 20px !important;
        }

        .accordion .card {
            background: #d1cfcf14;
        }

        .dashboard-counts h3 {
            font-size: 1rem !important
        }

        .dashboard-counts p {
            font-weight: 600;
            color: slategrey;
        }

        .form-input {
            padding: 0 10px 0 0;

            border: 0px;
        }
    </style>
@endsection

@section('content')

    <div class=" px-4 mt-2">
        <div class="row dashboard-counts">

                {{-- PATROLLING START --}}
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">{{ __('messages.patroling') }}</div>
                    <div class="card-body">
                        <div class="row">

                            {{-- TOTAL PATROLLING DONE --}}
                            <div class="col-md-4">
                                <div class="card p-3">
                                    <h3 class="text-center"> {{ __('messages.total_patrollig_done') }}</h3>
                                    <p class="text-center mb-0 pb-0"><span id="total_km"></span> KM </p>
                                </div>
                            </div>

                            {{-- TOTAL NOTICE GENERATED --}}
                            <div class="col-md-4">
                                <div class="card p-3">
                                    <h3 class="text-center">{{ __('messages.total_notice_generated') }} </h3>
                                    <p class="text-center mb-0 pb-0"><span id="total_notice"></span></p>
                                </div>
                            </div>

                            {{-- TOTAL SUPERVISION --}}
                            <div class="col-md-4">
                                <div class="card p-3">
                                    <h3 class="text-center"> {{ __('messages.total_supervision') }} </h3>
                                    <p class="text-center mb-0 pb-0"><span id="total_supervision"></span></p>
                                </div>
                            </div>

                            {{-- PATROLLING GRAPH --}}
                            <div class="col-md-12">
                                <div class="card p-3">
                                    <div id="patrolling-container" style="width:100%; height: 400px; margin: 0 auto"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                {{-- PATROLLING END --}}



                {{-- SUBSATION START --}}
            <div class="col-md-12">
                <div class="card card-warning">
                    <div class="card-header text-white">{{ __('messages.substation') }}</div>
                    <div class="card-body">
                        <div class="row">

                            {{-- TOTAL SUBSTATION VISITED --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <h3 class="text-center"> {{ __('messages.total_substation_visited') }}</h3>
                                    <p class="text-center mb-0 pb-0"> <span id="substation"></span> </p>
                                </div>
                            </div>

                            {{-- SUM SUBSTATION DEFECTS --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <h3 class="text-center"> {{ __('messages.total_substation_defects') }}</h3>
                                    <p class="text-center mb-0 pb-0"> <span id="substation_defects"></span> </p>
                                </div>
                            </div>

                            {{-- TOTAL SUBSTATION VISITED GRAPH --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <div id="suryed_substation-container" style="width:100%; height: 400px; margin: 0 auto"></div>
                                </div>
                            </div>

                            {{-- SUM SUBSTATION DEFECTS GRAPH --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <div id="substation-container" style="width:100%; height: 400px; margin: 0 auto">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
                {{-- SUBSATION END --}}


                {{-- FEEDER PILLAR START --}}
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">{{ __('messages.feeder_pillar') }}</div>
                    <div class="card-body">
                        <div class="row">
                            {{-- TOTAL VISITED --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <h3 class="text-center">{{ __('messages.total_feeder_pillar_visited') }}</h3>
                                    <p class="text-center mb-0 pb-0"><span id="feeder_pillar"> </span></p>
                                </div>
                            </div>

                            {{-- DEFECTS SUM --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <h3 class="text-center"> {{ __('messages.total_feeder_pillar_defects') }}</h3>
                                    <p class="text-center mb-0 pb-0"><span id="feeder_pillar_defect"></span></p>
                                </div>
                            </div>

                            {{-- TOTAL VISITED GRAPH --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <div id="suryed_feeder_pillar-container" style="width:100%; height: 400px; margin: 0 auto"></div>
                                </div>
                            </div>

                            {{-- DEFECTS GRAPH --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <div id="feeder_pillar-container" style="width:100%; height: 400px; margin: 0 auto">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
                {{-- FEEDER PILLAR END --}}

                {{-- TIANG SATRT --}}
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">{{ __('messages.tiang') }}</div>
                    <div class="card-body">
                        <div class="row">

                            {{-- TOTAL VISITED --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <h3 class="text-center">{{ __('messages.total_tiang_visited') }} </h3>
                                    <p class="text-center mb-0 pb-0"><span id="tiang"> </span></p>
                                </div>
                            </div>

                            {{-- DEFECTS SUM --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <h3 class="text-center"> {{ __('messages.total_tiang_defects') }}</h3>
                                    <p class="text-center mb-0 pb-0"><span id="tiang_defect"> </span></p>
                                </div>
                            </div>

                            {{-- VISITED GRAPH --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <div id="suryed_tiang-container" style="width:100%; height: 400px; margin: 0 auto"></div>
                                </div>
                            </div>

                            {{-- DEFECTS GRAPH --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <div id="tiang-container" style="width:100%; height: 400px; margin: 0 auto"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
                {{-- TIANG END --}}


                {{-- LINK BOX START --}}
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">{{ __('messages.link_box') }}</div>
                    <div class="card-body">
                        <div class="row">

                            {{-- TOTAL VISITED --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <h3 class="text-center"> {{ __('messages.total_link_box_visited') }} </h3>
                                    <p class="text-center mb-0 pb-0"><span id="link_box"></span></p>
                                </div>
                            </div>

                            {{-- DEFECTS SUM --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <h3 class="text-center"> {{ __('messages.total_link_box_defects') }} </h3>
                                    <p class="text-center mb-0 pb-0"><span id="link_box_defect"> </span></p>
                                </div>
                            </div>

                            {{-- VISITED GRAPH --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <div id="suryed_link_box-container" style="width:100%; height: 400px; margin: 0 auto"></div>
                                </div>
                            </div>

                            {{-- DEFECTS GRAPH --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <div id="link_box-container" style="width:100%; height: 400px; margin: 0 auto"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
                {{-- LINK BOX END --}}


                {{-- CABLE BRIDGE START --}}
            <div class="col-md-12">
                <div class="card card-danger">
                    <div class="card-header"> {{ __('messages.cable_bridge') }}</div>
                    <div class="card-body">
                        <div class="row">

                            {{-- TOTAL VISITED --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <h3 class="text-center"> {{ __('messages.total_cable_bridge_visited') }}</h3>
                                    <p class="text-center mb-0 pb-0"><span id="cable_bridge"></span></p>
                                </div>
                            </div>

                            {{-- DEFECTS SUM --}}
                            <div class="col-md-6">
                                <div class="card p-3">

                                    <h3 class="text-center"> {{ __('messages.total_cable_bridge_defects') }} </h3>
                                    <p class="text-center mb-0 pb-0"><span
                                            id="cable_bridge_defect"> </span></p>

                                </div>
                            </div>

                            {{-- VISITED GRAPH --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <div id="suryed_cable_bridge-container"
                                        style="width:100%; height: 400px; margin: 0 auto"></div>
                                </div>
                            </div>

                            {{-- DEFECTS GRAPH --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <div id="cable_bridge-container" style="width:100%; height: 400px; margin: 0 auto">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
                {{-- CABLE BRIDGE END --}}


                {{-- SAVT START --}}
            <div class="col-md-12">
                <div class="card card-danger">
                    <div class="card-header"> SAVT</div>
                    <div class="card-body">
                        <div class="row">

                            {{-- TOTAL VISITED --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <h3 class="text-center"> {{ __('messages.total_savt_visited') }}</h3>
                                    <p class="text-center mb-0 pb-0"><span id="savt"></span></p>
                                </div>
                            </div>

                            {{-- DEFECTS SUM --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <h3 class="text-center"> {{ __('messages.total_savt_defects') }} </h3>
                                    <p class="text-center mb-0 pb-0"><span id="savt_defect"></span></p>
                                </div>
                            </div>

                            {{-- VISITED GRAPH --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <div id="suryed_savt-container" style="width:100%; height: 400px; margin: 0 auto"></div>
                                </div>
                            </div>

                            {{-- DEFECTS GRAPH --}}
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <div id="savt-container" style="width:100%; height: 400px; margin: 0 auto">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
            {{-- SAVT END --}}
    </div>
@endsection


@section('script')
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>




    <script>


        var from_date = $('#excel_from_date').val();
        var to_date = $('#excel_to_date').val();
        var excel_ba = $('#search_ba').val();






        $(function() {
            // $('#stats_table').DataTable()
            if ('{{ Auth::user()->ba }}' == '') {
                getAllStats()
            }

            $('#excel_from_date , #excel_to_date').on('change', function() {
                var ff_ba = $('#excelBa').val() ?? '';
                from_date = $('#excel_from_date').val() ?? null;
                to_date = $('#excel_to_date').val() ?? null;

                onChangeBA();
                callLayers(ff_ba)

            })


        })
    </script>









    {{-- Charts Start --}}

    <script>
        function onChangeBA(param) {
            // console.log(data['patrolling']);
            $("#patrolling-container").html('')
            $("#substation-container").html('')
            $("#feeder_pillar-container").html('')
            $("#link_box-container").html('')
            $("#link_box-container").html('')
            $("#cable_bridge-container").html('')
            $("#tiang-container").html('')

            // $("#suryed_patrolling-container").html('')
            $("#suryed_substation-container").html('')
            $("#suryed_feeder_pillar-container").html('')
            $("#suryed_link_box-container").html('')
            $("#suryed_link_box-container").html('')
            $("#suryed_cable_bridge-container").html('')
            $("#suryed_tiang-container").html('')

            getDateCounts();
            getAllStats();
            callLayers(param);
        }


        function mainBarChart(cat, series, id, tName) {
            var barName = '';
            var titleName = 'Total ' + tName;
            if (id == "patrolling-container") {
                barName = 'KM'
                titleName = 'KM Patrol'
            }
            Highcharts.chart(id, {
                chart: {
                    type: 'column'
                },
                credits: false,

                title: {
                    text: 'Total ' + tName
                },
                subtitle: {
                    text: 'Source:Aerosynergy'
                },
                xAxis: {
                    categories: cat,
                    min: 0,
                    max: 3,
                    scrollbar: {
                        enabled: true
                    },

                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: titleName
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: `<tr><td style="color:{series.color};padding:0">{series.name}: </td>` +
                        `<td style="padding:0"><b>{point.y:f}</b>${barName}</td></tr>`,
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: series
            });
        }




        function getDateCounts() {

            var cu_ba = $('#excelBa').val() ?? 'null';
            var from_datee = $('#excel_from_date').val() ?? '';
            var to_datee = $('#excel_to_date').val() ?? '';



            $.ajax({
                url: `/{{ app()->getLocale() }}/patrol_graph?ba_name=${cu_ba}&from_date=${from_datee}&to_date=${to_datee}`,

                dataType: 'JSON',
                method: 'GET',
                async: false,
                success: function callback(data) {


                    if (data && data['patrolling'] != '') {
                        makeArray(data['patrolling'], 'patrolling-container', 'Defects')
                    }

                    if (data && data['substation'] != '') {
                        makeArray(data['substation'], 'substation-container', 'Defects')
                    }

                    if (data && data['feeder_pillar'] != '') {
                        makeArray(data['feeder_pillar'], 'feeder_pillar-container', 'Defects')
                    }

                    if (data && data['link_box'] != '') {
                        makeArray(data['link_box'], 'link_box-container', 'Defects')
                    }

                    if (data && data['cable_bridge'] != '') {
                        makeArray(data['cable_bridge'], 'cable_bridge-container', 'Defects')
                    }

                    if (data && data['tiang'] != '') {
                        makeArray(data['tiang'], 'tiang-container', 'Defects')
                    }

                    if (data && data['savt'] != '') {
                        makeArray(data['savt'], 'savt-container', 'Defects')
                    }

                    // if (data && data['suryed_patrolling'] != '') {
                    //     makeTotalArray(data['suryed_patrolling'] , 'suryed_patrolling-container'  )
                    // }

                    if (data && data['suryed_substation'] != '') {
                        makeArray(data['suryed_substation'], 'suryed_substation-container', 'Visited')
                    }

                    if (data && data['suryed_feeder_pillar'] != '') {
                        makeArray(data['suryed_feeder_pillar'], 'suryed_feeder_pillar-container', 'Visited')
                    }

                    if (data && data['suryed_link_box'] != '') {
                        makeArray(data['suryed_link_box'], 'suryed_link_box-container', 'Visited')
                    }

                    if (data && data['suryed_cable_bridge'] != '') {
                        makeArray(data['suryed_cable_bridge'], 'suryed_cable_bridge-container', 'Visited')
                    }

                    if (data && data['suryed_tiang'] != '') {
                        makeArray(data['suryed_tiang'], 'suryed_tiang-container', 'Visited')
                    }
                    if (data && data['suryed_savt'] != '') {
                        makeArray(data['suryed_savt'], 'suryed_savt-container', 'Visited')
                    }
                }
            });



            $.ajax({
                url: `/{{ app()->getLocale() }}/get-all-counts?ba=${cu_ba}&from_date=${from_datee}&to_date=${to_datee}`,
                dataType: 'JSON',
                method: 'GET',
                async: false,
                success: function callback(data) {

                    for (var key in data) {
                        $("#" + key).html(data[key]);
                    }
                }
            });


        }

        function makeTotalArray(arr, id) {

            console.log(arr);
            var cate = arr.map(item => item.ba);
            var seriesD = arr.map(item => item.count);

            var series = [{
                name: 'Count',
                data: seriesD
            }];

            console.log(series);
            mainBarChart(cate, series, id, 'Counts');


        }


        function makeArray(data, id, tName) {


            var series = [];
            var temp = [];
            var cat = [];
            for (var k = 0; k < data.length; k++) {
                if (cat.includes(data[k].visit_date) == false) {
                    cat.push(data[k].visit_date)
                }
            }
            for (var i = 0; i < data.length; i++) {
                // if(cat.includes(data[i].updated_at)==false){
                //     cat.push(data[i].updated_at)
                // }
                var username = data[i].ba;
                if (temp.includes(username) == true) {
                    continue;
                } else {
                    temp.push(username);
                    var obj = {};
                    obj.name = username;
                    var arr = []
                    for (var j = 0; j < data.length; j++) {
                        if (data[j].ba == username) {
                            var len = 0;
                            if (arr.length > 0) {
                                len = arr.length;
                            }
                            //if(data[j].updated_at==cat[len]){
                            var index = cat.indexOf(data[j].visit_date);
                            if (index > len) {
                                for (g = len; g < index; g++) {
                                    arr.push(0)
                                }
                                arr.push(parseInt(data[j].bar));
                            } else {
                                arr.push(parseInt(data[j].bar));
                            }
                            // }else{
                            //     arr.push(0)
                            // }
                        }

                    }
                    obj.data = arr;
                    series.push(obj)
                }

            }
            // console.log(series);
            mainBarChart(cat, series, id, tName)


        }
    </script>

    {{-- CHARTS END --}}


    {{-- COUNTS START --}}

    <script>
        function getAllStats() {
            // let todaydate = '{{ date('Y-m-d') }}';



            // var cu_ba = $('#excelBa').val() ?? 'null';
            // if ($('#excel_from_date').val() == '') {
            //     var from_datee = '1970-01-01'
            // } else {
            //     var from_datee = $('#excel_from_date').val();
            // }
            // if ($('#excel_to_date').val() == '') {
            //     var to_datee = todaydate
            // } else {
            //     var to_datee = $('#excel_to_date').val();
            // }

            $.ajax({
                url: `/{{ app()->getLocale() }}/statsTable`,
                dataType: 'JSON',
                method: 'GET',
                async: false,
                success: function callback(data) {
                    if ($.fn.DataTable.isDataTable('#stats_table_1')) {
                        $('#stats_table_1').DataTable().destroy();
                    }

                    var str = '';
                    var totals = {
                        patroling: 0,
                        substation: 0,
                        feeder_pillar: 0,
                        tiang: 0,
                        link_box: 0,
                        cable_bridge: 0,
                        savt: 0
                    };

                    for (var i = 0; i < data.length; i++) {
                        str += `<tr>
                                    <td>${data[i].ba}</td>
                                    <td>${data[i].patroling}</td>
                                    <td>${data[i].substation}</td>
                                    <td>${data[i].feeder_pillar}</td>
                                    <td>${data[i].tiang}</td>
                                    <td>${data[i].link_box}</td>
                                    <td>${data[i].cable_bridge}</td>
                                </tr>`;

                        totals.patroling += parseFloat(data[i].patroling) || 0;
                        totals.substation += parseInt(data[i].substation) || 0;
                        totals.feeder_pillar += parseInt(data[i].feeder_pillar) || 0;
                        totals.tiang += parseInt(data[i].tiang) || 0;
                        totals.link_box += parseInt(data[i].link_box) || 0;
                        totals.cable_bridge += parse(data[i].cable_bridge) || 0;
                        totals.savt += parse(data[i].savt) || 0;

                    }

                    $('#stats_table').html(str);



                    var str2 = '<tr><th>Total</th>';

                    for (var key in totals) {
                        str2 += '<th>' + parseFloat(totals[key]).toFixed(2) + '</th>';
                    }

                    str2 += '</tr>';

                    $('#stats_table_footer').html(str2);
                    // Destroy existing DataTable instance (if any)

                    // Reinitialize DataTable with new options
                    $('#stats_table_1').DataTable({
                        searching: false, // Disable search bar
                        paging: false // Disable pagination
                    });


                }

            });
        }


        function resetDashboard() {
            $('#excelBa').empty();
            $('#excel_from_date, #excel_to_date ').val('');
            onChangeBA();
            from_date = '';
            to_date = '';

            if (ba == '') {
                addRemoveBundary('', 2.75101756479656, 101.304931640625)
            } else {
                callLayers(ba);
            }
            // $("#excelBa").val($("#excelBa option:first").val());
        }


        setTimeout(() => {
            getDateCounts();
        }, 1000);
    </script>

    {{-- COUNTS END --}}
@endsection
