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
                    <h3>Users</h3>
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


    <section class="content">
        <div class="container-fluid">


            @include('components.message')




            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title  ">
                                <div class="text-right"><button class="btn btn-sm btn-success" data-toggle="modal"
                                        data-target="#teamModal" type="button">Add User</button></div>
                            </div>

                        </div>

                        <div class="card-body">
                            <div class="text-right mb-4">

                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="table table-bordered table-hover">


                                    <thead style="background-color: #E4E3E3 !important">
                                        <tr>
                                            <th>USER NAME</th>
                                            <th>ZONE</th>
                                            <th>BA</th>
                                            <th>USER EMAIL</th>
                                            <th>TEAM NAME</th>
                                            <th>TEAM TYPE</th>
                                            <th>ACTION</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($users as $data)
                                            <tr>
                                                <td class="align-middle">{{ $data->name }}</td>

                                                <td class="align-middle">{{ $data->zone }}</td>

                                                <td class="align-middle">{{ $data->ba }}</td>

                                                <td class="align-middle">{{ $data->email }}</td>

                                                <td>{{ $data->userTeam->team_name }}</td>
                                                <td>{{ $data->userTeam->team_type }}</td>
                                                <td class="text-center">

                                                    <button type="button" class="btn  " data-toggle="dropdown">
                                                        <img
                                                            src="{{ URL::asset('assets/web-images/three-dots-vertical.svg') }}">
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">




                                                        <button type="button" class="btn btn-primary dropdown-item"
                                                            data-id="{{ $data->id }}" data-toggle="modal"
                                                            data-target="#myModal">
                                                            Remove
                                                        </button>


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
                    <h4 class="modal-title">Remove Recored</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="" id="remove-foam" method="POST">
                    @method('DELETE')
                    @csrf

                    <div class="modal-body">
                        Are You Sure ?
                        <input type="hidden" name="id" id="modal-id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button type="submit" class="btn btn-danger">Remove</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <div class="modal fade" id="teamModal">
        <div class="modal-dialog">
            <div class="modal-content ">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title">Add User</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('team-users.store', app()->getLocale()) }}" id="addUser" method="POST">

                    @csrf

                    <div class="modal-body form-input">

                        <div class="row">
                            <div class="col-md-4">
                                <label for="name">Username</label>
                            </div>

                            <div class="col-md-8"> <input type="text" name="name" id="name" class=" form-control"
                                    required></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="email">Email</label>

                            </div>
                            <div class="col-md-8">
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-4">
                                <label for="team-name">Team</label>

                            </div>
                            <div class="col-md-8">
                                <select name="id_team" id="team-id" class="form-control " required>
                                    <option value="" hidden>Select team</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="zone">{{ __('messages.zone') }}</label>

                            </div>
                            <div class="col-md-8">
                                <select name="zone" id="search_zone" class="form-control" required>

                                    <option value="" hidden>select zone</option>
                                    <option value="W1">W1</option>
                                    <option value="B1">B1</option>
                                    <option value="B2">B2</option>
                                    <option value="B4">B4</option>


                                </select>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="ba">{{ __('messages.ba') }}</label>

                            </div>
                            <div class="col-md-8">
                                <select name="ba" id="ba" class="form-control" required>
                                    <option value="" hidden>select BA</option>

                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-4">
                                <label for="email">Password</label>

                            </div>
                            <div class="col-md-8">
                                <input type="text" name="password" id="password" class="form-control" required>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                </form>

            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>


    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>

    <script>
        const b10ptions = [
            ['W1', 'KUALA LUMPUR PUSAT', 3.14925905877391, 101.754098819705],
            ['B1', 'PETALING JAYA', 3.1128074178475, 101.605270457169],
            ['B1', 'RAWANG', 3.47839445121726, 101.622905486475],
            ['B1', 'KUALA SELANGOR', 3.40703209426401, 101.317426926947],
            ['B2', 'KLANG', 3.08428642705789, 101.436185279023],
            ['B2', 'PELABUHAN KLANG', 2.98188527916042, 101.324234779569],
            ['B4', 'CHERAS', 3.14197346621987, 101.849883983416],
            ['B4', 'BANTING', 2.82111390453244, 101.505890775541],
            ['B4', 'BANGI', 2.965810949933260, 101.81881303103104],
            ['B4', 'PUTRAJAYA & CYBERJAYA', 2.92875032271019, 101.675338316575]
        ];
        $(document).ready(function() {

            $("#myForm").validate();
            $('#myTable').DataTable();

            $('#search_zone').on('change', function() {
                const selectedValue = this.value;
                const areaSelect = $('#ba');

                // Clear previous options
                areaSelect.empty();
                areaSelect.append(`<option value="" hidden>Select ba</option>`)

                b10ptions.forEach((data) => {
                    if (selectedValue == data[0]) {
                        areaSelect.append(`<option value="${data[1]}">${data[1]}</option>`);
                    }
                });

                $('#search_wp').empty();
                $('#search_wp').append(`<option value="" hidden>Select Work Package</option>`);

            })


            $('#myModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                $('#remove-foam').attr('action', '/{{ app()->getLocale() }}/admin/team-users/' + id)
            });



        });
    </script>
@endsection
