

@section('css')
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
                    <h3>{{__('messages.link_box_pelbagai_voltan')}}</h3>

                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="/{{app()->getLocale()}}/dashboard">{{__("messages.dashboard")}}</a></li>
                        <li class="breadcrumb-item active">{{__("messages.index")}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <section class="content">
        <div class="container-fluid">



            @include('components.message')


            <div class="row">
                @include('components.qr-filter',['url'=>"$excelUrl"])
                <div class="col-12">
                    <div class="card">

                            <div class="card-header d-flex justify-content-between ">
                                <div class="card-title">
                                    {{-- Link Box --}}
                                </div>
                                <div class="d-flex ml-auto">
                                <a href="/{{app()->getLocale()}}/".$url."/create"><button class="btn text-white btn-success  btn-sm mr-4"  >Add Link Box</button></a>

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
                                {{$slot}}
                            </div>






                        </div>
                    </div>



                </div>
            </div>
        </div>
    </section>
   <x-remove-confirm />
@endsection


@section('script')

    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{asset('assets/js/generate-qr.js')}}"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>

    <script>
        var from_date = $('#excel_from_date').val();
        var to_date = $('#excel_to_date').val();
        var excel_ba = $('#excelBa').val();
        $(document).ready(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,

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
                    }
                },
                columns: [{
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
                        render: function(data, type, full) {

                            var id = full.id;
                            return `<button type="button" class="btn  " data-toggle="dropdown">
                            <img
                                src="{{ URL::asset('assets/web-images/three-dots-vertical.svg') }}">
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <form action="/{{ app()->getLocale() }}/link-box-pelbagai-voltan/${id}" method="get">

                                <button type="submit" class="dropdown-item pl-3 w-100 text-left">Detail</button>
                            </form>
                            <form action="/{{ app()->getLocale() }}/link-box-pelbagai-voltan/${id}/edit" method="get">

                                <button type="submit" class="dropdown-item pl-3 w-100 text-left">Edit</button>
                            </form>
                            <button type="button" class="btn btn-primary dropdown-item" data-id="${id}" data-toggle="modal" data-target="#myModal">
                                Remove
                            </button>
                        </div>
                        `;
                        }
                    }

                ],
                order: [
                    [3, 'desc']
                ]
            })


            $('#excelBa').on('change', function() {
                excel_ba = $(this).val();
                table.ajax.reload(function() {
                    table.draw('page');
                });
            })


            $('#excel_from_date').on('change', function() {
                from_date = $(this).val();
                table.ajax.reload(function() {
                    table.draw('page');
                });
            })

            $('#excel_to_date').on('change', function() {
                to_date = $(this).val();
                table.ajax.reload(function() {
                    table.draw('page');
                });
            });

            $('#myModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var langs = '{{ app()->getLocale() }}';
                var modal = $(this);
                $('#remove-foam').attr('action', '/' + langs + '/link-box-pelbagai-voltan/' + id)
            });

        });
    </script>
    
@endsection
