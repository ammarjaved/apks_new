@extends('layouts.map_layout', ['page_title' => 'Substation'])


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

        .form-input {
            border: 0
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

        .navbar {
            display: none !important
        }
    </style>
@endsection


@section('content')
    <div class=" ">

        <div class="container-">

            <div class=" ">

                <div class=" card col-md-12 p-4 ">
                    <div class="form-input ">
                        <h3 class="text-center p-2">Substation</h3>

                        <form action="{{ route('update-substation-map-edit', [app()->getLocale(), $data->id]) }} "
                            id="myForm" method="POST" enctype="multipart/form-data">

                            @csrf

                            <div class="row ">
                                <div class="col-md-4"><label for="id">ID </label></div>
                                <div class="col-md-4">
                                    <input type="text" value="{{ $data->id }}" disabled
                                        class="form-control disabled">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-4"><label for="id">Created By </label></div>
                                <div class="col-md-4">
                                    <input type="text" value="{{ $data->created_by }}" disabled
                                        class="form-control disabled">
                                </div>
                            </div>

                            @include('substation.partials.form')


                            <div class="row  ">
                                <div class="col-md-4">
                                    <label for="zone">QA Status</label>
                                </div>
                                <div class="col-md-4">

                                    <select name="qa_status" id="qa_status" class="form-control"
                                        onchange="onChangeQa(this.value)">
                                        <option value="{{ $data->qa_status }}" hidden>{{ $data->qa_status }}</option>
                                        <option value="Accept">Accept</option>
                                        <option value="Reject">Reject</option>
                                        <option value="KIV">KIV</option>
                                    </select>
                                </div>
                            </div>


                            <div class=" row {{ $data->qa_status != 'Reject' ? 'd-none' : '' }} " id="reject-reason">

                                <div class="col-md-4">
                                    <label for="zone">Reason</label>
                                </div>
                                <div class="col-md-4">
                                    <textarea name="reject_remakrs" id="reject_remakrs" cols="10" rows="4" class="form-control">{{ $data->reject_remarks }}</textarea>
                                </div>
                            </div>


                            <div class="text-center py-4">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRecord({{ $data->id }})">{{ __('messages.remove') }}</button>
                                <button class="btn btn-sm btn-success" type="submit" >{{ __('messages.update') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    <script>

        $(document).ready(function() {
            $("#myForm").validate();
        });


        function onChangeQa(status)
        {
            if (status === 'Reject') {
                $('#reject-reason').removeClass('d-none');

            } else {

                $('#reject-reason').addClass('d-none');
            }
        }


        function getStatus(event)
        {
            var val = event.value;

            if (!$('#gate_status_other').hasClass('d-none')) {
                $('#gate_status_other').addClass('d-none')
            } else {
                $('#gate_status_other').removeClass('d-none')
            }
        }


        function bulidingStatus(event)
        {
            var val = event.value;

            if (!$('#other_building_defects').hasClass('d-none')) {
                $('#other_building_defects').addClass('d-none')
            } else {
                $('#other_building_defects').removeClass('d-none')
            }
        }


         // Function to remove a record
         function removeRecord(paramId)
         {
            var confrim = confirm('Are you sure?')
            if (confrim) {
                $.ajax({
                    url: `/{{ app()->getLocale() }}/remove-substation/${paramId}`,
                    dataType: 'JSON',
                    method: 'GET',
                    success: function(response) {
                        // Send message to parent window to close modal after successful removal
                        window.parent.postMessage('closeModal', '*');
                    }
                });
            }
        }


    </script>
@endsection
