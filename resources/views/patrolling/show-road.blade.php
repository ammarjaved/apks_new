@extends('layouts.app')

@section('css')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />

    @include('partials.map-css')
    <style>
        .error {
            color: red;
        }

        label {
            margin-bottom: 0px !important;
            margin-top: 1rem;
        }

        input,
        select {
            color: black !important;
            margin-bottom: 0px !important;
            margin-top: 1rem;
        }

        #map {
            margin: 30px;
            height: 400px;
            padding: 20px;
        }
    </style>
@endsection


@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row  " style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{__('messages.patrolling')}}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="/{{app()->getLocale()}}/get-all-work-packages">{{__('messages.index')}}</a></li>
                        <li class="breadcrumb-item active">{{__('messages.deatil')}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class=" ">

        <div class="container">

            <div class=" ">

                <div class=" card col-md-12 p-4 ">
                    <div class=" ">
                        <h3 class="text-center p-2"></h3>



                            <div class="row">
                                <div class="col-md-4"><label for="zone">{{__('messages.zone')}}</label></div>
                                <div class="col-md-4">
                                    <input type="text" readonly value="{{$road->zone}}" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="ba">{{__('messages.ba')}}</label></div>
                                <div class="col-md-4"><input type="text" readonly value="{{$road->ba}}" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="search_wp">{{__('messages.Work_Package_Name')}}</label></div>
                                <div class="col-md-4">
                                    <input type="text" readonly value="{{$road->workPackage->package_name}}" class="form-control">
                                </div>
                            </div>




                            <div class="row">
                                <div class="col-md-4"><label for="road_name">{{__('messages.Road_Name')}}</label></div>
                                <div class="col-md-4">
                                    <input type="text" readonly value="{{$road->road_name}}" class="form-control">
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-4"><label for="date_patrol">{{__('messages.Patrolling_Date')}}</label></div>
                                <div class="col-md-4"> <input type="text" readonly value="{{$road->date_patrol}}" class="form-control"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="time_petrol">{{__('messages.patrolling_time')}}</label></div>
                                <div class="col-md-4"> <input type="text" readonly value="{{$road->time_petrol}}" class="form-control"></div>
                            </div>



                            <div class="row">
                                <div class="col-md-4"><label for="fidar">{{__('messages.Feeder')}}</label></div>
                                <div class="col-md-4"> <input type="text" readonly value="{{$road->fidar}}" class="form-control"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="name_project">{{__('messages.Project_Name')}}</label></div>
                                <div class="col-md-4"> <input type="text" readonly value="{{$road->name_project}}" class="form-control"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="km">{{__('messages.Km_Plan')}}</label></div>
                                     <div class="col-md-4"> <input type="text" readonly value="{{ number_format($road->km , 2)}}" class="form-control"></div>

                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="actual_km">{{__('messages.km_actual')}}</label></div>
                                <div class="col-md-4"> <input type="text" readonly value="{{ number_format($road->actual_km ,2)}}" class="form-control"></div>

                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="digging">{{__('messages.Total_Digging')}}</label></div>
                                <div class="col-md-4"> <input type="text" readonly value="{{$road->total_digging}}" class="form-control"></div>

                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="notice">{{__('messages.Total_Notice')}}</label></div>
                                <div class="col-md-4"> <input type="text" readonly value="{{$road->total_notice}}" class="form-control"></div>

                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="supervision">{{__('messages.Total_Supervision')}}</label></div>
                                <div class="col-md-4"> <input type="text" readonly value="{{$road->total_supervision}}" class="form-control"></div>

                            </div>




















                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
