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


        span.relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.cursor-default.leading-5 {
            background: #007BFF !important;
            color: white !important;
        }

        .collapse {
            visibility: visible;
        }

        /* .table-responsive::-webkit-scrollbar {
                        display: none;
                    } */

        table.dataTable>thead>tr>th:not(.sorting_disabled),
        table.dataTable>thead>tr>td:not(.sorting_disabled) {
            padding-right: 14px;
        }

        .lower-header,
        td {
            font-size: 14px !important;
            padding: 5px !important;
        }

        th {
            font-size: 15px !important;

        }

        thead {
            background-color: #E4E3E3 !important;
        }

        .nowrap,
        th {
            white-space: nowrap;
        }
    </style>
@endsection



@section('content')
    <section class="content-header pb-0">
        <div class="container-  ">
            <div class="row  mb-0 pb-0" style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{ __('messages.substation') }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a
                                href="/{{ app()->getLocale() }}/dashboard">{{ __('messages.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.index') }} </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <section class="content-">
        <div class="container-fluid">
            @include('components.message')







            <div class="row">
                @include('components.lks-filter', ['url' => 'generate-substation-lks'])



                <div class="col-12-">
                    <div class="card">



                        <div class="card-body" id="lks_dat">








                            {{-- <table id="pagination" class="table table-bordered table-hover"> --}}


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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

        function resetIndex(params) {

        }
        var lang = "{{ app()->getLocale() }}";
        var url = "substation"
        var auth_ba = "{{ Auth::user()->ba }}"


    //     $(document).ready(function() {


    //         var options = {
    //     success: callbackSuccess
    // };

    // // Submit form using ajax
    // $jq('#generate-lks-form').ajaxForm(options);

    // function callbackSuccess(response) {
    //     var pdfPath = response.pdfPath;
    //     console.log(pdfPath);
    //     const link = document.createElement('a');
    //         link.setAttribute('href', '/temp/'+pdfPath);
    //         link.setAttribute('download', pdfPath); // Need to modify filename ...
    //         link.click();

    //     link.remove();

    // }


    //     });




        function generateLKS(){
            var from_date=$('#excel_from_date').val()
          var to_date=$('#excel_to_date').val()
                $.ajax({
                            url: '/{{ app()->getLocale() }}/get-substation-lks?ba='+auth_ba+'&from_date='+from_date+'&to_date='+to_date,
                            dataType: 'JSON',
                            //data: data,
                            method: 'GET',
                            async: false,
                            success: function callback(data) {
                                $("#lks_dat").html('');
                            for(var i=0;i<data.length;i++){
                                str='<table class="table table-bordered">'+
                                    '<tr>'+
                                    '<th>ZONE</th>'+
                                    '<th>BA</th>'+
                                    '<th>Team</th>'+
                                    '<th>VISIT DATE</th>'+
                                    '<th>VISIT Time</th>'+
                                    '<th>FL</th>'+
                                    '</tr>'+
                                    '<tr>'+
                                    '<td>'+data[i].zone+'</td>'+
                                    '<td>'+data[i].ba+'</td>'+
                                    '<td>'+data[i].team+'</td>'+
                                    '<td>'+data[i].visit_date+'</td>'+
                                    '<td>'+data[i].patrol_time+'</td>'+
                                    '<td>'+data[i].fl+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                    '<th>Substation Image 1</th>'+
                                    '<th>Substation Image 2</th>'+
                                    '</tr>'+
                                    '<tr>'+
                                    '<td><img width="250px" height="250px" src="/'+data[i].substation_image_1+'"></td>'+
                                    '<td><img width="250px" height="250px" src="/'+data[i].substation_image_2+'"></td>'+
                                    '</tr>'+
                                    '</table>';


                                    $("#lks_dat").append(str);
                            }
                                // $.each(data, function(i, str) {



                                // });



                                console.log(data);
                                var opt = {
                                    margin:       1,
                                    filename:     'myfile.pdf',
                                    image:        { type: 'jpeg', quality: 0.5 },
                                    html2canvas:  { dpi: 96, letterRendering: true },
		                            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
                                }
                                setTimeout(() => {
                                    var element = document.getElementById('lks_dat');
                               //  html2pdf(element, opt);
                                 html2pdf()
                                .set(opt)
                                .from(element)
                                .outputPdf('blob')
                                .then((pdf) => {
                                const link = document.createElement('a');
                                link.href = window.URL.createObjectURL(pdf);
                                link.download = 'downloaded.pdf';
                                link.click();
                                });



                                }, 3000);

                            }
                        })

        }

        function printLKS(){

            window.print();

        }


    </script>
@endsection
