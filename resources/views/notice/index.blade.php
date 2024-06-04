@extends('layouts.app', ['page_title' => 'Index'])

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">


    <style>
        div#myTable_length,
        div#roads_length {
            display: none;
        }
    </style>
@endsection



@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row mb-2" style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{__('messages.notice')}}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item text-lowercase" ><a href="/{{app()->getLocale()}}/dashboard">{{__('messages.dashboard')}}</a></li>
                        <li class="breadcrumb-item text-lowercase active">{{__('messages.index')}} </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <section class="content">
        <div class="container-fluid">



            @include('components.message')


            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between ">
                            <div class="card-title">
                                {{__('messages.notice')}}
                            </div>
                            <div class="d-flex ml-auto">
                                {{-- <a href="{{ route('third-party-digging.create', app()->getLocale()) }}"><button
                                        class="btn text-white btn-success  btn-sm mr-4">{{__('messages.add_notice')}}</button></a> --}}

                                {{-- <a href="{{ route('generate-third-party-digging-excel', app()->getLocale()) }}"> <button
                                        class="btn text-white  btn-sm mr-4" style="background-color: #708090">QR Notice</button></a> --}}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="text-right mb-4">

                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="table table-bordered table-hover">


                                    <thead style="background-color: #E4E3E3 !important">
                                        <tr>
                                            <th>WP NAME</th>
                                            <th>ZONE</th>
                                            <th>BA</th>
                                            <th>TEAM</th>
                                            <th>SURVEY DATE</th>
                                            <th>NOTICE</th>
                                            <th>ACTION</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($datas as $data)
                                            <tr>
                                                <td class="align-middle">{{ $data->wp_name }}</td>
                                                <td class="align-middle">{{ $data->zone }}</td>
                                                <td>{{ $data->ba }}</td>
                                                <td class="align-middle text-center">{{ $data->team_name }}</td>
                                                <td class="align-middle text-center">
                                                    @php
                                                        $date = new DateTime($data->survey_date);
                                                        $datePortion = $date->format('Y-m-d');

                                                    @endphp
                                                    {{ $datePortion }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($data->digging_notice)
                                                       <a href="/{{app()->getLocale()}}/download-notice/{{$data->id}}" class="btn btn-primary btn-sm">Download</a>
                                                    @endif

                                                    {{-- <a href="/{{app()->getLocale()}}/generate-third-party-pdf/{{$data->id}}" target="_blank" rel="noopener noreferrer">
                                                        <button class="btn-sm btn-success">Generate Notice</button>
                                                    </a> --}}

                                                </td>

                                                <td class="text-center">

                                                    <button type="button" class="btn  " data-toggle="dropdown">
                                                        <img
                                                            src="{{ URL::asset('assets/web-images/three-dots-vertical.svg') }}">
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                        <button type="button" class="dropdown-item pl-3 w-100 text-left" data-id="{{ $data->id }}" data-toggle="modal" data-wp_name="{{$data->wp_name}}"
                                                            data-target="#myModal">Upload/Generate Notice</button>
                                                        <form action="{{ route('third-party-digging.show',[app()->getLocale(), $data->id]) }}"
                                                            method="get">
                                                            <button type="submit"
                                                                class="dropdown-item pl-3 w-100 text-left">Detail</button>
                                                        </form>






                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>






                        </div>
                    </div>



                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content ">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title">Upload Notice</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="/{{ app()->getLocale() }}/upload-notice" id="myForm" method="POST" enctype="multipart/form-data">

                    @csrf
                    <div class="modal-body form-input">
                    <input type="hidden" name="id" id="modal_id">
                        <div class="row mb-2">
                            <div class="col-md-4"><label for="wp_name">WP Name </label></div>
                            <div class="col-md-8"><input type="text" name="wp_name" id="wp_name"  readonly  class="form-control" required></div>
                        </div>


                        <div class="row mb-2">
                            <div class="col-md-4"><label for="upload_notice">Upload Notice</label></div>
                            <div class="col-md-8"><input type="file" name="upload_notice" id="upload_notice" class="form-control" required></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><label for="upload_notice">Generate Notice</label></div>
                            <div class="col-md-8"><a href="" target="_blank" id="generate-notice-button" rel="noopener noreferrer" >
                                <button type="button" class="btn-sm text-white btn" style="background-color: #708090">Generate Notice</button>
                            </a></div>
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>

                        <button type="submit" class="btn btn-success btn-sm" >Upload</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection


@section('script')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#myForm").validate();
            $('#myTable').DataTable({
                aaSorting: [
                    [0, 'asc']
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
            });
            $('#myModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var langs = '{{ app()->getLocale() }}'
                var id = button.data('id');
                $('#modal_id').val(id);
                $('#wp_name').val(button.data('wp_name'))
                $('#generate-notice-button').attr('href' , '/{{app()->getLocale()}}/generate-third-party-pdf/'+id)

                var modal = $(this);
                $('#remove-foam').attr('action', '/' + langs + '/third-party-digging/' + id)
            });

        });
    </script>
@endsection
