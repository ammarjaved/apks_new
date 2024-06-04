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
                    <h3>{{ __('messages.link_box_pelbagai_voltan') }}</h3>

                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a
                                href="/{{ app()->getLocale() }}/dashboard">{{ __('messages.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.index') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <section class="content">
        <div class="container-fluid">



            @include('components.message')


            <div class="row">
                @include('components.qr-filter', ['url' => 'generate-link-box-excel'])
                <div class="col-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between ">
                            <div class="card-title">
                                {{-- Link Box --}}
                            </div>
                            <div class="d-flex ml-auto">
                                <a href="{{ route('link-box-pelbagai-voltan.create', app()->getLocale()) }}"><button
                                        class="btn text-white btn-success  btn-sm mr-4">Add Link Box</button></a>

                                <button class="btn text-white  btn-sm mr-4" type="button" data-toggle="collapse"
                                    style="background-color: #708090" data-target="#collapseQr" aria-expanded="false"
                                    aria-controls="collapseQr">
                                    QR Link Box
                                </button>
                                {{-- <a href="{{route('generate-link-box-excel', app()->getLocale())}}"> <button class="btn text-white  btn-sm mr-4" style="background-color: #708090">QR Link Box</button></a> --}}
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
                                            <th>ZONE</th>
                                            <th>BA</th>
                                            <th>TEAM</th>
                                            <th>VISIT DATE</th>
                                            <th>TOTAL DEFECTS</th>
                                            {{-- @if (Auth::user()->ba !== '') --}}
                                                <th>QA Status</th>
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
        var url = "link-box-pelbagai-voltan"
        var auth_ba = "{{ Auth::user()->ba }}"

        $(document).ready(function() {

            var columns = [{
                    data: "link_box_id",
                    name: "link_box_id"
                },
                {
                    data: 'zone',
                    name: 'zone'
                },
                {
                    data: 'ba',
                    name: 'ba',
                    orderable: true
                },
                {
                    data: 'team',
                    name: 'team'
                },
                {
                    data: 'visit_date',
                    name: 'visit_date'
                },
                {
                    data: 'total_defects',
                    name: 'total_defects'
                },
            ]
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
                    url: '{{ route('link-box-pelbagai-voltan.index', app()->getLocale()) }}',
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
                            d.image = 'link_box_image_1';
                        }
                        if (qa_status) {
                            d.qa_status = qa_status;
                        }
                        if(cycle){
                            d.cycle = cycle;
                        }
                        if(cycle){
                            d.cycle = cycle;
                        }
                    }
                },
                columns: columns,
                order: [
                    [0, 'desc']
                ]
            })



        });
    </script>
@endsection
