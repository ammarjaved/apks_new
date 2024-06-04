@extends('layouts.app')

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
        a[href='#finish'] {
            display: none !important;
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
    <section class="content-header ">
        <div class="container-  ">
            <div class="row  " style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{ __('messages.savt') }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('savt.index', app()->getLocale()) }}">{{ __('messages.index') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('messages.index') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="container ms-auto">

        <div class=" card col-md-12 p-3 ">
            <h3 class="text-center p-2">{{ __('messages.savt') }}</h3>
                        <form id="framework-wizard-form" action="#"
                            enctype="multipart/form-data" style="display: none" method="POST">
                            @csrf
                            @method('PATCH')

                            @include("SAVT.partials.form")





                        </form>

                    </div>
                </section>


    <x-reject-modal />

            @endsection
            
            @section('script')
                <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
                <script src="{{ URL::asset('assets/test/js/jquery.steps.js') }}"></script>
            
                <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script> 
            
                <script>
                    var form = $("#framework-wizard-form").show();
                    form.steps(
                        {
                            headerTag: "h3",
                            bodyTag: "fieldset",
                            transitionEffect: "slideLeft",
                        })



                    $(function(){
                        $('#rejectReasonModal').on('show.bs.modal', function(event) {
                            var button = $(event.relatedTarget);
                            var id = button.data('id');

                            $('#reject-foam').attr('action', `/{{app()->getLocale()}}/savt-update-QA-Status`)
                            $('#reject-id').val(id);
                        });
 
                        getVoltan($('#voltan_kv').val())
       
   
                    })
 
                    
            
                    function getVoltan(param)
                    {
                        if (param == '11kv') 
                        {        
                            $('.voltan_11').css('display','block')
                        }
                        else
                        {
                            $('.voltan_11').css('display','none')
                        }
                    }
            
                    
            
            
                </script>
            @endsection
            