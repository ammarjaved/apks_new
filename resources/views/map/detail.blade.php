@extends('layouts.app', ['page_title' => 'Index'])

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <style>
        span.relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.cursor-default.leading-5 {
            background: #007BFF !important;
            color: white !important;
        }
    </style>
@endsection



@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row mb-2" style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>Work Package</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="/{{app()->getLocale()}}/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">index</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <section class="content">
        <div class="container-fluid">



            @if (Session::has('failed'))
                <div class="alert {{ Session::get('alert-class', 'alert-secondary') }}" role="alert">
                    {{ Session::get('failed') }}

                    <button type="button" class="close border-0 bg-transparent" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
            @endif
            @if (Session::has('success'))
                <div class="alert {{ Session::get('alert-class', 'alert-success') }}" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="close border-0 bg-transparent" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif



            <div class="row mb-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Work Package
                            </div>

                        </div>

                        <div class="card-body">
                            <div class="text-right mb-4">

                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="table table-bordered table-hover">


                                    <thead style="background-color: #E4E3E3 !important">
                                        <tr>
                                            <th>Package Name</th>
                                            <th>Zone</th>

                                            <th>BA</th>
                                            <th>Status</th>
                                            <th>Created AT</th>
                                            <th>Updated AT</th>

                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($datas as $data)
                                            <tr>
                                                <td class="align-middle"><button class="dropdown-item"
                                                        value="{{ $data->id }}" type="button"
                                                        onclick="getRoads(this)">{{ $data->package_name }}</button></td>
                                                <td class="align-middle">
                                                    {{ $data->zone }}

                                                </td>

                                                <td class="align-middle ">
                                                    {{ $data->ba }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if ($data->wp_status == 'approved')
                                                        <span class="badge badge-success">Approved</span>
                                                    @elseif($data->wp_status == 'rejected')
                                                        <span class="badge badge-danger">Rejected</span>
                                                    @elseif($data->wp_status == 'pending')
                                                        <span class="badge badge-secondary">Pending </span>
                                                    @else
                                                        <a href="/{{app()->getLocale()}}/send-to-tnbes/{{ $data->id }}"><button
                                                                class="btn btn-sm btn-primary">Send to SBUM</button></a>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    @php
                                                        $date = new DateTime($data->created_at);
                                                        $datePortion = $date->format('Y-m-d');
                                                    @endphp

                                                    {{ $datePortion }}

                                                </td>
                                                <td class="text-center">{{ $data->updated_at }} </td>
                                                <td class="text-center">

                                                    <button type="button" class="btn  " data-toggle="dropdown">
                                                        <img
                                                            src="{{ URL::asset('assets/web-images/three-dots-vertical.svg') }}">
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">

                                                        <a class="dropdown-item"
                                                            href="/{{app()->getLocale()}}/get-work-package-detail/{{ $data->id }}">Detail</a>


                                                        <a class="dropdown-item"
                                                            href="/{{app()->getLocale()}}/remove-work-package/{{ $data->id }}"
                                                            onclick="return confirm('Are you sure?')">Remove</a>


                                                        </a>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>






                        </div>
                    </div>




                    <div class="card my-5">
                        <div class="card-header">
                            <div class="card-title">
                                Roads
                            </div>

                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-between ">
                                <div class="">
                                    <h3 ><span id="raods-header">Unpatroled</span> Roads</h3>
                                </div>
                                <div class="dd-flex justify-content-end ">
                                <button
                                    class="btn-sm btn-success m-3" value="Patroled" onclick="getRoadStatus(this)">Patroled</button><button
                                    class="btn-sm btn-success m-3"  value="Unpatroled" onclick="getRoadStatus(this)">UnPatroled</button></div></div>
                            <div class="table-responsive" id="add-roads">
                                @include('map.pagination.roads-pagination')
                            </div>

                        </div>
                    </div>



                </div>
            </div>
        </div>
    </section>
@endsection


@section('script')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>


    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>

    <script>
        var packName = '';
        var patrolStatus = '';
        var packID = '';
        $(document).ready(function() {
            $('#myTable').DataTable({
                aaSorting: [
                    [0, 'asc']
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
            });
            // $('#roads').DataTable();


            $(document).on('click', 'span a', function(event)

                {
                    $('span a').removeClass('active')

                    $(this).addClass('active');

                    event.preventDefault();

                    var page = $(this).attr('href').split('page=')[1];
                    $.ajax({
                        url: `/{{app()->getLocale()}}/test-pagination/${packID}/${patrolStatus}?page=${page}`,
                        dataType: 'html',
                        method: 'GET',
                        async: false,
                    }).done(function(data) {

                        $("#add-roads").empty().html(data);


                        $('.work_pakcage_name').html(packName)

                    })

                })
        });

        function getRoadStatus(param){

            patrolStatus = param.value;
            $('#raods-header').html(patrolStatus)
            $.ajax({
                url: `/{{app()->getLocale()}}/test-pagination/${packID}/${patrolStatus}`,
                dataType: 'html',
                method: 'GET',
                async: false,
            }).done(function(data) {

                $("#add-roads").empty().html(data);
                $('.work_pakcage_name').html(packName)

            })
        }


        function getRoads(param) {

            packID = param.value;
            patrolStatus = 'Unpatroled';
            $('#raods-header').html(patrolStatus)

            packName = $(param).text()
            $.ajax({
                url: `/{{app()->getLocale()}}/test-pagination/${packID}/${patrolStatus}`,
                dataType: 'html',
                method: 'GET',
                async: false,
            }).done(function(data) {

                $("#add-roads").empty().html(data);
                $('.work_pakcage_name').html(packName)

            })
        }
    </script>
@endsection
