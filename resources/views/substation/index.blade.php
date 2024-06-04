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


        span.relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.cursor-default.leading-5 {
            background: #007BFF !important;
            color: white !important;
        }

        .collapse {
            visibility: visible;
        }

        /* .table-responsive::-webkit-scrollbar {
                        display: none;
                    } */

        table.dataTable>thead>tr>th:not(.sorting_disabled),
        table.dataTable>thead>tr>td:not(.sorting_disabled) {
            padding-right: 14px;
        }

        .lower-header,
        td {
            font-size: 14px !important;
            padding: 5px !important;
        }

        th {
            font-size: 15px !important;

        }

        thead {
            background-color: #E4E3E3 !important;
        }

        .nowrap,
        th {
            white-space: nowrap;
        }
    </style>
@endsection



@section('content')
    <section class="content-header pb-0">
        <div class="container-  ">
            <div class="row  mb-0 pb-0" style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{ __('messages.substation') }}</h3>
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


    <section class="content-">
        <div class="container-fluid">

            @include('components.message')

            <div class="row">
                @include('components.qr-filter', ['url' => 'generate-substation-excel'])

                <div class="col-12-">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between ">
                            <p class="mb-0">{{ __('messages.substation') }}</p>
                            <div class="d-flex ml-auto">
                                <a href="{{ route('substation.create', app()->getLocale()) }}"><button
                                        class="btn text-white btn-success  btn-sm mr-4">Add Substation</button></a>


                                <button class="btn text-white  btn-sm mr-4" type="button" data-toggle="collapse"
                                    style="background-color: #708090" data-target="#collapseQr" aria-expanded="false"
                                    aria-controls="collapseQr">
                                    QR Substation
                                </button>

                            </div>
                        </div>


                        <div class="card-body">
                            <div class="text-right mb-4">

                            </div>



                            {{-- <table id="pagination" class="table table-bordered table-hover"> --}}

                            <div class="table-responsive add-substation" id="add-substation">
                                <table id="" class="table table-bordered  table-hover data-table">


                                    <thead>
                                        <tr>
                                            <th rowspan="2">ID</th>
                                            <th rowspan="2">{{ __('messages.name') }}</th>
                                            <th rowspan="2">{{ __('messages.visit_date') }} </th>
                                            <th rowspan="2">asdas</th>
                                            <th colspan="3" class="text-center" style="border-bottom: 0px">
                                                {{ __('messages.gate') }}</th>
                                            <th colspan="2" class="text-center" style="border-bottom: 0px">
                                                {{ __('messages.tree') }}</th>
                                            <th colspan="4" class="text-center" style="border-bottom: 0px">
                                                {{ __('messages.building_defects') }}
                                            </th>
                                            <th class="nowrap" style="border-bottom: 0px">{{ __('messages.add_clean_up') }}
                                            </th>
                                            <th rowspan="2">{{ __('messages.total_defects') }} </th>
                                            {{-- @if (Auth::user()->ba !== '') --}}
                                                <th rowspan="2">QA Status</th>
                                            {{-- @endif --}}


                                            <th rowspan="2">ACTION</th>

                                        </tr>
                                        <tr class="lower-header">
                                            <th>{{ __('messages.unlocked') }}</th>
                                            <th>{{ __('messages.demaged') }}</th>
                                            <th>{{ __('messages.others') }} </th>
                                            <th class="nowrap">{{ __('messages.long_grass') }} </th>
                                            <th class="nowrap">{{ __('messages.tree_branches_in_PE') }} </th>
                                            <th class="nowrap">{{ __('messages.broken_roof') }} </th>
                                            <th>{{ __('messages.broken_gutter') }} </th>
                                            <th>{{ __('messages.broken_base') }} </th>
                                            <th>{{ __('messages.others') }} </th>
                                            <th>{{ __('messages.cleaning_illegal_ads_banners') }} </th>
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
        var url = "substation"
        var auth_ba = "{{ Auth::user()->ba }}"


        $(document).ready(function() {

            var columns = [
            {
                name:'substation_id',
                data:'substation_id',
            },
            {
                    render: function(data, type, full) {
                        return `<a href="/{{ app()->getLocale() }}/substation/${full.id}/edit" class="text-decoration-none text-dark">${full.name}</a>`;
                    },
                    name: 'name'
                },
                {
                    data: 'visit_date',
                    name: 'visit_date',
                    orderable: true
                },
                {
                    data: 'id',
                    name: 'id',
                    visible: false,
                },
                {
                    data: 'unlocked',
                    name: 'unlocked'
                },
                {
                    data: 'demaged',
                    name: 'demaged'
                },

                {
                    data: 'other_gate',
                    name: 'other_gate'
                },
                {
                    data: 'grass_status',
                    name: 'grass_status'
                },
                {
                    data: 'tree_branches_status',
                    name: 'tree_branches_status'
                },
                {
                    data: 'broken_roof',
                    name: 'broken_roof'
                },
                {
                    data: 'broken_gutter',
                    name: 'broken_gutter'
                },
                {
                    data: 'broken_base',
                    name: 'broken_base'
                },
                {
                    data: 'building_other',
                    name: 'building_other'
                },

                {
                    data: 'advertise_poster_status',
                    name: 'advertise_poster_status'
                },
                {
                    data: 'total_defects',
                    name: 'total_defects'
                }
            ];
            // if (auth_ba !== '') {
                columns.push({
                    data: null,
                    render: renderQaStatus
                });
            // }

            columns.push({
                data: null,
                render: renderDropDownActions
            });






             table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,


                ajax: {
                    url: '{{ route('substation.index', app()->getLocale()) }}',
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
                            d.image = 'substation_image_1';
                        }
                        if (qa_status) {
                            d.qa_status = qa_status;
                        }
                        if(cycle){
                            d.cycle = cycle;
                        }
                    }
                },
                columns: columns,
                order: [
                    [1, 'desc'],
                    [0, 'desc']

                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:not(:first-child)').addClass('text-center');
                }
            })



        });




    </script>
@endsection
