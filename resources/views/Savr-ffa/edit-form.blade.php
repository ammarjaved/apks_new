@extends('layouts.map_layout', ['page_title' => 'Tiang Savr'])


@section('css')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />

    <link rel="stylesheet" href="{{ URL::asset('assets/test/css/style.css') }}" />
    <style>
        input[type='checkbox'],
        input[type='radio'] {
            min-width: 16px !important;
            margin-right: 12px;
        }

        input[type='radio'] {
            border-radius: 50% !important;
        }

        .fw-400 {
            font-weight: 400 !important;
        }

        input[type='file'],
        table input {
            margin: 0px !important;
        }

        table label {
            font-size: 14px !important;
            font-weight: 400 !important;
            margin-left: 10px !important;
            margin-bottom: 0px !important
        }

        th {
            font-size: 14px !important;
        }

        th,
        td {
            padding: 6px 16px !important
        }

        table,
        input[type='file'] {
            width: 90% !important;
        }


        table input[type="file"] {
            font-size: 11px !important;
            height: 33px !important;
        }

        /* td.d-flex {
                    border-bottom: 0px !important;
                    border-left: 0px !important;
                    border-right: 0px !important;
                } */

        textarea {
            border: 1px solid #999999 !important;
        }

        body::-webkit-scrollbar {
            display: none !important;
        }

        span.number {
            display: none
        }

        .navbar {
            display: none !important
        }
    </style>
@endsection


@section('content')
    <div class="bg-white ">

        <div class="container- m-2 bg-white">



                <div class=" card row bg-white   ">
                    <div class="form-input p-4">

                        <form id="framework-wizard-form"
                            action="/{{ app()->getLocale() }}/savr-ffa-map-edit/{{ $data->id }}"
                            enctype="multipart/form-data" method="POST">

                            @csrf

                                <div class="row ">
                                    <div class="col-md-4"><label for="id">ID </label></div>
                                    <div class="col-md-4">
                                        <input type="text" value="{{ $data->id }}" disabled
                                            class="form-control disabled">
                                    </div>
                                </div>


                             @include('Savr-ffa.partials.form')



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
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRecord({{ $data->id }})">Remove</button>
                                    <button class="btn btn-sm btn-success" type="submit" onclick="$('#framework-wizard-form').submit()">UPDATE</button>
                                </div>

                        </form>


                    </div>
                </div>


        </div>
    </div>
    <x-reject-modal />
@endsection

@section('script')
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>


    <script>
        // Function to handle changes in QA status
        function onChangeQa(status) {
            if (status === 'Accept') {
                $('#reject-reason').addClass('d-none');
            } else if (status === 'Reject') {
                $('#reject-reason').removeClass('d-none');
            }
        }


        // Function to remove a record
        function removeRecord(paramId) {
            var confrim = confirm('Are you sure?')
            if (confrim) {
                $.ajax({
                    url: `/{{ app()->getLocale() }}/remove-savr-ffa/${paramId}`,
                    dataType: 'JSON',
                    method: 'GET',
                    success: function(response) {
                        // Send message to parent window to close modal after successful removal
                        window.parent.postMessage('closeModal', '*');
                    }
                });
            }
        }

        $(document).ready(function() {


            $('input[type="file"]').on('change', function() {
                showUploadedImage(this)
            })

            $('#other').on('change', function() {
                getOther(this.value)
            })

        });

        // DISPALY UPLOADED IMAGE
        function showUploadedImage(param) {
            const file = param.files[0];
            const id = $(`#${param.id}_div`);

            if (file) {
                id.empty()
                const reader = new FileReader();
                reader.onload = function(e) {
                    var img =
                        `<a class="text-right"  href="${e.target.result}" data-lightbox="roadtrip"><span class="close-button" onclick="removeImage('${param.id}')">X</span><img src="${e.target.result}" style="height:50px;"/></a>`;
                    id.append(img)
                };

                reader.readAsDataURL(file);
            }
        }


        // REMOVE UPLOADED IMAGES
        function removeImage(id) {
            console.log(id);
            $(`#${id}`).val('');
            $(`#${id}_div`).empty();
        }



        function getOther(param) {
            console.log(param);
            if (param == 'Yes') {

                $('#other_name_div').removeClass('d-none')


            } else {
                $('#other_name_div').addClass('d-none')
                $('#other_name').val('')
            }
        }
    </script>
@endsection
