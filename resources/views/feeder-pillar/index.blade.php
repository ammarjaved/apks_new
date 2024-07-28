@extends('layouts.app', ['page_title' => 'Index'])

@section('css')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
<script src="https://malsup.github.io/jquery.form.js"></script>
<script>
    var $jq = $.noConflict(true);
</script>
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <style>
        div#myTable_length,
        div#roads_length {
            display: none;
        }

        .collapse {
            visibility: visible;
        }
    </style>
@endsection



@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row mb-2" style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{ __('messages.feeder_pillar') }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item text-lowercase "><a
                                href="/{{ app()->getLocale() }}/dashboard">{{ __('messages.dashboard') }}</a></li>
                        <li class="breadcrumb-item text-lowercase active">{{ __('messages.index') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <section class="content">
        <div class="container-fluid">



            @include('components.message')


            <div class="row">
                @include('components.qr-filter', ['url' => 'generate-feeder-pillar-excel'])
                <div class="col-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between ">
                            <p class="mb-0">{{ __('messages.feeder_pillar') }}</p>
                            <div class="d-flex ml-auto">
                                <a href="{{ route('feeder-pillar.create', app()->getLocale()) }}"><button
                                        class="btn text-white btn-success  btn-sm mr-4">Add Fedder Pillar</button></a>

                                <button class="btn text-white  btn-sm mr-4" type="button" data-toggle="collapse"
                                    style="background-color: #708090" data-target="#collapseQr" aria-expanded="false"
                                    aria-controls="collapseQr">
                                    QR Feeder Pillar
                                </button>
                                {{-- <a href="{{route('generate-feeder-pillar-excel',app()->getLocale())}}"> <button class="btn text-white  btn-sm mr-4" style="background-color: #708090">QR Feeder Pillar</button></a> --}}
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="text-right mb-4">

                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="table table-bordered table-hover data-table">


                                    <thead style="background-color: #E4E3E3 !important">
                                        <tr>
                                            <th>ID</th>
                                            <th>BA</th>
                                            <th>VISIT DATE</th>
                                            <th>UNLOCKED</th>
                                            <th>DAMAGED</th>
                                            <th>OTHER</th>
                                            <th>VANDALISM</th>
                                            <th>LEANING</th>
                                            <th>RUST</th>
                                            <th>ADVERTISE POSTER</th>
                                            <th>TOTAL DEFECTS</th>
                                            {{-- @if (Auth::user()->ba !== '') --}}
                                            <th >QA Status</th>
                                            {{-- @endif --}}
                                            <th>ACTION</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>



                </div>
            </div>
        </div>
    </section>


    <x-remove-confirm />
    <x-reject-modal />

@endsection


@section('script')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/generate-qr.js') }}"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>

    <script>
        var lang = "{{ app()->getLocale() }}";
        var url = "feeder-pillar"
 var auth_ba = "{{Auth::user()->ba}}"


        $(document).ready(function() {


            var columns = [{
                        data: 'feeder_pillar_id',
                        name: 'feeder_pillar_id'
                    },
                    {
                        data: 'ba',
                        name: 'ba',
                        orderable: true
                    },
                    {
                        data: 'visit_date',
                        name: 'visit_date'
                    },

                    {
                        data: 'unlocked',
                        name: 'unlocked',
                    }, {
                        data: 'demaged',
                        name: 'demaged',
                    },
                    {
                        data: 'other_gate',
                        name: 'other_gate'
                    },
                    {
                        data: 'vandalism_status',
                        name: 'vandalism_status'
                    }, {
                        data: 'leaning_staus',
                        name: 'leaning_staus'
                    },
                    {
                        data: 'rust_status',
                        name: 'rust_status'
                    },
                    {
                        data: 'advertise_poster_status',
                        name: 'advertise_poster_status'
                    },
                    {
                        data: 'total_defects',
                        name: 'total_defects'
                    },

                ];
                // if (auth_ba !== '') {
        columns.push({ data: null, render: renderQaStatus });
    // }

    columns.push({ data: null, render: renderDropDownActions });

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,


                ajax: {
                    url: '{{ route('feeder-pillar.index', app()->getLocale()) }}',
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
                        if (f_status) {
                            d.status = f_status;
                            d.image = 'feeder_pillar_image_1';
                        }
                        if (qa_status) {
                            d.qa_status = qa_status;
                        }
                        if(cycle){
                            d.cycle = cycle;
                        }
                    }
                },
                columns : columns,
                order: [
                    [0, 'desc']
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:not(:first-child)').addClass('text-center');
                }
            })




        });
    </script>
@endsection
