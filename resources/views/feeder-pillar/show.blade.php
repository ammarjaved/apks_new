@extends('layouts.app')

@section('css')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />

    {{-- <link rel="stylesheet" href="{{ URL::asset('assets/test/css/style.css') }}" /> --}}
    <style>
        input[type='checkbox'],
        input[type='radio'] {
            min-width: 16px !important;
            margin-right: 12px;
        }

        .error {
            color: red;
        }

        label {
            margin-bottom: 0px !important;
            margin-top: 1rem;
        }

        input,
        select {
            /* color: black !important; */
            margin-bottom: 0px !important;
            margin-top: 1rem;
        }

        .adjust-height {
            height: 70px;
        }

        .form-input {
            border: 0
        }
    </style>
@endsection


@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row  " style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{ __('messages.feedar_piller') }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item text-lowercase"><a
                                href="{{ route('feeder-pillar.index', app()->getLocale()) }}">{{ __('messages.index') }}</a>
                        </li>
                        <li class="breadcrumb-item text-lowercase active">{{ __('messages.show') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class=" ">

        <div class="container">

            <div class="">

                <div class=" card col-md-12 p-4 ">
                    <div class=" form-input ">
                        <h3 class="text-center p-2"></h3>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="zone">QA Status</label>
                            </div>
                            <div class="col-md-4">



                                @if ($data->visit_date != '' && $data->feeder_pillar_image_1 != '')
                                    <button type="button"
                                        class="btn  text-left form-control {{ $data->qa_status == 'Accept' ? 'btn-success' : ($data->qa_status == 'Reject' ? 'btn-danger' : 'btn-primary') }} "
                                        data-toggle="dropdown">
                                        {{ $data->qa_status }}

                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        @if ($data->qa_status != 'Accept')
                                            <a href="/{{ app()->getLocale() }}/feeder-pillar-update-QA-Status?status=Accept&&id={{ $data->id }}"
                                                onclick="return confirm('are you sure?')">
                                                <button type="submit"
                                                    class="dropdown-item pl-3 w-100 text-left">Accept</button>
                                            </a>
                                        @endif

                                        @if ($data->qa_status != 'Reject')
                                            <button type="button" class="btn btn-primary dropdown-item"
                                                data-id="{{ $data->id }}" data-toggle="modal"
                                                data-target="#rejectReasonModal">
                                                Reject
                                            </button>
                                        @endif



                                    </div>
                                @else
                                    <button type="button" class="btn  text-left form-control"
                                        style="background: orange ; color:white">
                                        <strong>Unsurveyed</strong>


                                    </button>
                                @endif
                                {{-- <select name="qa_status" id="qa_status" class="form-control" ></select> --}}
                            </div>
                        </div>

                        @if ($data->qa_status == 'Reject')
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="zone">Reason</label>
                                </div>
                                <div class="col-md-4">
                                    <textarea name="" id="" cols="10" rows="4" disabled class="form-control">{{ $data->reject_remarks }}</textarea>
                                </div>
                            </div>
                        @endif

                        @include('feeder-pillar.partials.form')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-reject-modal />

@endsection

@section('script')
    <script>
        $(function() {
            $('#rejectReasonModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');

                $('#reject-foam').attr('action',
                    `/{{ app()->getLocale() }}/feeder-pillar-update-QA-Status`)
                $('#reject-id').val(id);
            });
        })
    </script>
@endsection
