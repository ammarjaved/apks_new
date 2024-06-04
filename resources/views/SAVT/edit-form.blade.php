@extends('layouts.map_layout')

@section('css')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('assets/test/css/style.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        input[type='radio'] {
            border-radius: 50% !important;
        }

        .fw-400 {
            font-weight: 400 !important;
        }

        input[type='checkbox'],
        input[type='radio'] {
            min-width: 16px !important;
            margin-right: 12px;
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

        th {font-size: 14px !important;}
        th,td {  padding: 6px 16px !important}
        table,
        input[type='file'] {
            width: 90% !important;
        }



        table input[type="file"] {
            font-size: 11px !important;
            height: 33px !important;
        }

        td.d-flex {
            border-bottom: 0px !important;
            border-left: 0px !important;
        }

        .defects input[type="file"] { margin-bottom: 5px !important; }
        textarea { border: 1px solid #999999 !important; }
        .form-input .card { border-radius: 0px !important; }
        span.number { display: none;}
    </style>
@endsection


@section('content')


    <section class="container- ms-auto">

        <div class=" card col-md-12 p-3 ">
            <h3 class="text-center p-2">{{ __('messages.savt') }}</h3>
                        <form  action="/{{app()->getLocale()}}/update-savt-map-edit/{{$data->id}}"
                            enctype="multipart/form-data" method="POST">
                            @csrf

                            {{-- BA --}}
                            <div class="row">
                                <div class="col-md-4"><label for="ba">ID</label></div>
                                <div class="col-md-4">
                                    <input type="text"  readonly class="form-control" value="{{$data->id}}">
                                    {{-- <input type="hidden" name="ba"   class="form-control" value="{{$data->ba}}"> --}}

                                </div>
                            </div>

                            @include("SAVT.partials.form")


                            <fieldset class="form-input">
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


 

                            </fieldset>

                            <div class="text-center py-4">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRecord({{ $data->id }})">Remove</button>
                                <button class="btn btn-sm btn-success" type="submit"  >UPDATE</button>
                            </div>


                        </form>

                    </div>
        </section>
        <x-reject-modal />

    @endsection

@section('script')
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    {{-- <script src="{{ URL::asset('assets/test/js/jquery.steps.js') }}"></script> --}}

    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>

    <script>
        // var form = $("#framework-wizard-form").show();
        // form.steps(
        //     {
        //         headerTag: "h3",
        //         bodyTag: "fieldset",
        //         transitionEffect: "slideLeft",
        //         onStepChanging: function(event, currentIndex, newIndex) {
        //             if (currentIndex > newIndex) {
        //                 return true;
        //             }
        //             form.validate().settings.ignore = ":disabled,:hidden";
        //             return form.valid();
        //         },
        //         onFinished: function(event, currentIndex) {
        //             form.submit();
        //         },
        //         // autoHeight: true,
        //     })







    </script>




    <script>

        $(document).ready(function()
        {

            $('input[type="file"]').on('change', function() {
                // showUploadedImage(this)
            })

        });

        // Function to remove a record
        function removeRecord(paramId) {
            var confrim = confirm('Are you sure?')
            if (confrim) {
                $.ajax({
                    url: `/{{ app()->getLocale() }}/remove-savt/${paramId}`,
                    dataType: 'JSON',
                    method: 'GET',
                    success: function(response) {
                        // Send message to parent window to close modal after successful removal
                        window.parent.postMessage('closeModal', '*');
                    }
                });
            }
        }

        function onChangeQa(status) {
            if (status === 'Accept') {
                $('#reject-reason').addClass('d-none');
            } else if (status === 'Reject') {
                $('#reject-reason').removeClass('d-none');
            }
        }

        // DISPALY UPLOADED IMAGE
        function showUploadedImage(param)
        {
            const file = param.files[0];
            const id = $(`#${param.id}_div`);

            if (file) {
                id.empty()
                const reader = new FileReader();
                reader.onload = function(e) {
                    var img = `<a class="text-right" type="button"  href="${e.target.result}" data-lightbox="roadtrip"><span class="close-button" onclick="removeImage('${param.id}')">X</span><img src="${e.target.result}" style="height:50px;"/></a>`;
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







        function getVoltan(param){
            if (param == '11kv') {

                    $('.voltan_11').css('display','block')

            }else{
                $('.voltan_11').css('display','none')

            }
        }




    </script>
@endsection
