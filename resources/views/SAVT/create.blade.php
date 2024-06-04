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

        #map {
            margin: 30px;
            height: 400px;
            padding: 20px;
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
                        <li class="breadcrumb-item active">{{ __('messages.create') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="container ms-auto">

        <div class=" card col-md-12 p-3 ">
            <h3 class="text-center p-2">{{ __('messages.savt') }}</h3>
                        <form id="framework-wizard-form" action="{{ route('savt.store', app()->getLocale()) }}"
                            enctype="multipart/form-data" style="display: none" method="POST"
                            onsubmit="return submitFoam()">
                            @csrf

                            <h3>{{ __('messages.info') }}</h3>

                            {{-- START Info (1) --}}
                            <fieldset class=" form-input">

                                {{-- BA --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="ba">{{ __('messages.ba') }}</label></div>
                                    <div class="col-md-4">
                                        <select name="ba_s" id="ba_s" class="form-control" onchange="getWp(this)" required>
                                            @if (Auth::user()->ba == '')
                                                <option value="" hidden>Select ba</option>

                                                <optgroup label="W1">
                                                    <option value="KL PUSAT,KUALA LUMPUR PUSAT, 3.14925905877391, 101.754098819705">KL PUSAT</option>
                                                </optgroup>

                                                <optgroup label="B1">
                                                    <option value="PJ,PETALING JAYA, 3.1128074178475, 101.605270457169">PETALING JAYA</option>
                                                    <option value="RAWANG,RAWANG, 3.47839445121726, 101.622905486475">RAWANG</option>
                                                    <option value="K.SELANGOR,KUALA SELANGOR, 3.40703209426401, 101.317426926947">KUALA SELANGOR</option>
                                                </optgroup>

                                                <optgroup label="B2">
                                                    <option value="KLANG,KLANG, 3.08428642705789, 101.436185279023">KLANG</option>
                                                    <option value="PORT KLANG,PELABUHAN KLANG, 2.98188527916042, 101.324234779569">PELABUHAN KLANG</option>
                                                </optgroup>

                                                <optgroup label="B4">
                                                    <option value="CHERAS,CHERAS, 3.14197346621987, 101.849883983416">CHERAS</option>
                                                    <option value="BANTING/SEPANG,BANTING, 2.82111390453244, 101.505890775541">BANTING</option>
                                                    <option value="BANGI,BANGI,2.965810949933260,101.81881303103104">BANGI</option>
                                                    <option value="PUTRAJAYA/CYBERJAYA/PUCHONG,PUTRAJAYA & CYBERJAYA, 2.92875032271019, 101.675338316575">PUTRAJAYA & CYBERJAYA</option>
                                                </optgroup>

                                            @else
                                            @endif
                                        </select>
                                        <input type="hidden" name="ba" id="ba">
                                    </div>
                                </div>

                                {{-- Pembekal --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="Pembekal"> {{ __('messages.Pembekal') }}   </label>
                                    </div>

                                </div>

                                {{-- PMU/PPU --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="supplier_pmu_ppu"> {{ __('messages.PMU_PPU') }}  </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="supplier_pmu_ppu" id="supplier_pmu_ppu" class="form-control" required>
                                    </div>
                                </div>

                                {{-- FEEDER NO --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="supplier_feeder_no"> {{ __('messages.Feeder_no') }}  </label></div>
                                    <div class="col-md-4">
                                        <input type="text" name="supplier_feeder_no"  id="supplier_feeder_no" class="form-control" required>
                                    </div>
                                </div>

                                {{-- FEEDER NO --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="road_name"> {{ __('messages.road_name') }}  </label></div>
                                    <div class="col-md-4">
                                        <input type="text" name="road_name"  id="road_name" class="form-control" required>
                                    </div>
                                </div>

                                {{-- SECTION --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="">{{ __('messages.Section') }} </label></div>
                                </div>

                                {{-- SECTION FROM --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="sec_from">{{ __('messages.from') }} </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="sec_from" id="sec_from" class="form-control">
                                    </div>
                                </div>

                                {{-- SECTION TO --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="sec_to">{{ __('messages.to') }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="sec_to" id="sec_to" class="form-control">
                                    </div>
                                </div>

                                {{-- TIANG NO --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="tiang_no">{{ __('messages.Tiang_No') }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="tiang_no" id="tiang_no" class="form-control" required>
                                    </div>
                                </div>

                                {{-- VOLTAN (KV) --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="voltan_kv">{{ __('messages.Voltan') }} (KV)</label>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="voltan_kv" id="voltan_kv" class="form-control" required onchange="getVoltan(this.value)">
                                            <option value="" hidden>select</option>
                                            <option value="11kv">11kv</option>
                                            <option value="33kv">33kv</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- VISIT DATE --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="review_date">{{ __('messages.visit_date') }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date" name="visit_date" value="{{ now('Asia/Kuala_Lumpur')->format('Y-m-d') }}" id="review_date" class="form-control" required>
                                    </div>
                                </div>

                                {{-- COORDINATES --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="cordinates">{{ __('messages.coordinate') }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="cordinates" id="cordinates" class="form-control" required readonly>
                                    </div>
                                </div>

                                <input type="hidden" name="lat" id="lat" required class="form-control">
                                <input type="hidden" name="log" id="log" class="form-control">

                                {{-- MAP ERROR DIV --}}
                                <div class="text-center">
                                    <strong> <span class="text-danger map-error"></span></strong>
                                </div>

                                {{-- MAP DIV --}}
                                <div id="map">

                                </div>

                            </fieldset>
                            {{-- END Info (1) --}}



                            {{-- IMAGES --}}
                            <h3>{{__('messages.images')}}</h3>
                            <fieldset class="form-input">

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="pole_image-1">{{ __('messages.savt') }} Image 1 </label>
                                    </div>
                                    <div class="col-md-4 p-2 pr-5">
                                        <input type="file" name="savt_image_1" id="savt_image_1" required accept="image/*" class="form-control">
                                    </div>
                                    <div class="col-md-4" id="savt_image_1_div"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="pole_image-2">{{ __('messages.savt') }} Image 2</label>
                                    </div>
                                    <div class="col-md-4 p-2 pr-5">
                                        <input type="file" name="savt_image_2" id="savt_image_2" required accept="image/*" class="form-control">
                                    </div>
                                    <div class="col-md-4" id="savt_image_2_div"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="pole_image-1">{{ __('messages.savt') }} Image 3 </label>
                                    </div>
                                    <div class="col-md-4 p-2 pr-5">
                                        <input type="file" name="savt_image_3" id="savt_image_3"  accept="image/*" class="form-control">
                                    </div>
                                    <div class="col-md-4" id="savt_image_3_div"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="pole_image-2">{{ __('messages.savt') }} Image 4</label>
                                    </div>
                                    <div class="col-md-4 p-2 pr-5">
                                        <input type="file" name="savt_image_4" id="savt_image_4"  accept="image/*" class="form-control">
                                    </div>
                                    <div class="col-md-4" id="savt_image_4_div"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="pole_image-1">{{ __('messages.savt_clean_banner') }} Image </label>
                                    </div>
                                    <div class="col-md-4 p-2 pr-5">
                                        <input type="file" name="savt_clean_banner_image" id="savt_clean_banner_image"  accept="image/*" class="form-control">
                                    </div>
                                    <div class="col-md-4" id="savt_clean_banner_image_div"></div>

                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="pole_image-1">{{ __('messages.savt_remove_creepers') }} Image </label>
                                    </div>
                                    <div class="col-md-4 p-2 pr-5">
                                        <input type="file" name="savt_remove_creepers_image" id="savt_remove_creepers_image"  accept="image/*" class="form-control">
                                    </div>
                                    <div class="col-md-4" id="savt_remove_creepers_image_div"></div>

                                </div>


                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="pole_image-1">{{ __('messages.savt_current_leakage') }} Image </label>
                                    </div>
                                    <div class="col-md-4 p-2 pr-5">
                                        <input type="file" name="savt_current_leakage_image" id="savt_current_leakage_image"  accept="image/*" class="form-control">
                                    </div>
                                    <div class="col-md-4" id="savt_current_leakage_image_div"></div>

                                </div>

                            </fieldset>

                            {{-- END IMAGES --}}




                             {{-- ASSET REGISTER --}}
                             <h3>{{__('messages.Asset_Register')}}</h3>
                             <fieldset class="form-input">
                                <div class="row" >

                                            {{-- ABC SAIZ (MMP) --}}
                                    <div class="col-md-6">
                                        <div class="card p-4">
                                            <label for="st7"> ABC {{ __('messages.saiz_mmp') }} </label>


                                                <div class=" col-md-12 row   ">

                                                    {{-- ABC SAIZ 3X70 --}}
                                                    <div class="col-md-4 voltan_11">
                                                        <div class="d-flex">
                                                            <input type="radio" name="abc_size_mmp" value="3x70" id="abc_saiz_mmp_3x70" class="  ">
                                                            <label for="abc_saiz_mmp_3x70" class="fw-400">3x70</label>
                                                        </div>
                                                    </div>

                                                    {{-- ABC SAIZ 3X150 --}}

                                                    <div class="d-flex col-md-4">
                                                        <input type="radio" name="abc_size_mmp" value="3x150" id="abc_saiz_mmp_3x150" class=" ">
                                                        <label for="abc_saiz_mmp_3x150" class="fw-400">3x150</label>
                                                    </div>

                                                    {{-- ABC SAIZ 3X240 --}}

                                                    <div class="  col-md-4 voltan_11">
                                                        <div class="d-flex">
                                                            <input type="radio" name="abc_size_mmp" value="3x240" id="abc_saiz_mmp_3x240" class=" ">
                                                            <label for="abc_saiz_mmp_3x240" class="fw-400">3x240</label>
                                                        </div>
                                                    </div>
                                                </div>






                                                {{-- ABC PANJNG METER --}}
                                            <div class="row">
                                                <div class="col-md-6"><label for="st7">ABC {{ __('messages.panjang_meter') }} </label></div>
                                                <div class=" col-md-6 ">
                                                    <input type="number" name="abc_panjang_meter" id="abc_panjang_meter" class="form-control">
                                                </div>
                                            </div>

                                                {{-- ABC TIANG NO --}}
                                            {{-- <div class="row">
                                                <div class="col-md-6"><label for="st7">ABC {{ __('messages.tiang_no') }} </label></div>
                                                <div class=" col-md-6 ">
                                                    <input type="text" name="abc_tiang_no" id="abc_tiang_no" class="form-control">
                                                </div>
                                            </div> --}}

                                        </div>
                                    </div>



                                    <div class="col-md-6">
                                        <div class="card p-4">


                                             {{-- BARE SAIZ MMP --}}
                                             <div class="row">
                                                <div class="col-md-6"><label for="st7">BARE {{ __('messages.saiz_mmp') }} </label></div>
                                                <div class=" col-md-6 ">
                                                    <input type="text" name="bare_size_mmp" id="bare_size_mmp" class="form-control">
                                                </div>
                                            </div>

                                                {{-- BARE PANJANG METER --}}

                                            <div class="row">
                                                <div class="col-md-6"><label for="st7">BARE {{ __('messages.panjang_meter') }} </label></div>
                                                <div class=" col-md-6 ">
                                                    <input type="text" name="bare_panjang_meter" id="bare_panjang_meter" class="form-control">
                                                </div>
                                            </div>

                                                {{-- BARE TIANG NO --}}

                                            {{-- <div class="row">
                                                <div class="col-md-6"><label for="st7">BARE {{ __('messages.tiang_no') }} </label></div>
                                                <div class=" col-md-6 ">
                                                    <input type="text" name="bare_tiang_no" id="bare_tiang_no" class="form-control">
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>



                                        {{-- UNDERGROUND --}}


                                    <div class="col-md-6">
                                        <div class="card p-4">
                                             <label for="st7">{{__('messages.underground_cabel')}}  {{ __('messages.saiz_mmp') }} </label>


                                            <div class=" col-md-12 row" >

                                                {{-- UNDERGROUND CABLE 3X70 --}}
                                                <div class="  col-md-4 voltan_11">
                                                    <div class="d-flex">
                                                        <input type="radio" name="underground_cabel_size_mmp" value="3x70" id="underground_cabel_size_mmp_3x70" class="  ">
                                                        <label for="underground_cabel_size_mmp_3x70" class="fw-400">3x70</label>
                                                    </div>
                                                </div>

                                                {{-- UNDERGROUND CABLE 3X150 --}}

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="underground_cabel_size_mmp" value="3x150" id="underground_cabel_size_mmp_3x150" class=" ">
                                                    <label for="underground_cabel_size_mmp_3x150" class="fw-400">3x150</label>
                                                </div>

                                                {{-- UNDERGROUND CABLE 3X240 --}}

                                                <div class="  col-md-4 voltan_11">
                                                    <div class="d-flex">
                                                        <input type="radio" name="underground_cabel_size_mmp" value="3x240" id="underground_cabel_size_mmp_3x240" class=" ">
                                                        <label for="underground_cabel_size_mmp_3x240" class="fw-400">3x240</label>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-6"><label for="st7">{{__('messages.underground_cabel')}} {{ __('messages.panjang_meter') }} </label></div>
                                            <div class=" col-md-6">
                                                <input type="number" name="underground_cabel_length_meter" id="underground_cabel_length_meter" class="form-control">
                                            </div>

                                        </div>
                                    </div>


                                        {{-- BIL. EQUIPMENT --}}

                                    <div class="col-md-6">
                                        <div class="card p-4">

                                                {{-- AUTO CIRCUIT RECLOSER  --}}
                                            <label for="st7">{{__('messages.eqp_no')}}  {{ __('messages.auto_circuit_recloser') }} </label>
                                            <div class=" col-md-12 row">

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_auto_circuit_recloser" value="Yes" id="eqp_no_auto_circuit_recloser_yes" class="  ">
                                                    <label for="eqp_no_auto_circuit_recloser_yes" class="fw-400">Yes</label>
                                                </div>

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_auto_circuit_recloser" value="No" id="eqp_no_auto_circuit_recloser_no" class=" ">
                                                    <label for="eqp_no_auto_circuit_recloser_no" class="fw-400">No</label>
                                                </div>
                                            </div>

                                                {{-- LOAD BREAK SWITCH  --}}
                                            <label for="st7">{{__('messages.eqp_no')}} {{ __('messages.load_break_switch') }} </label>
                                            <div class=" col-md-12 row">

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_load_break_switch" value="Yes" id="eqp_no_load_break_switch_yes" class="  ">
                                                    <label for="eqp_no_load_break_switch_yes" class="fw-400">Yes</label>
                                                </div>

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_load_break_switch" value="No" id="eqp_no_load_break_switch_no" class=" ">
                                                    <label for="eqp_no_load_break_switch_no" class="fw-400">No</label>
                                                </div>
                                            </div>


                                                    {{-- ISOLATOR SWITCH  --}}
                                            <label for="st7">{{__('messages.eqp_no')}} {{ __('messages.isolator_switch') }} </label>
                                            <div class=" col-md-12 row">

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_isolator_switch" value="Yes" id="eqp_no_isolator_switch_yes" class="  ">
                                                    <label for="eqp_no_isolator_switch_yes" class="fw-400">Yes</label>
                                                </div>

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_isolator_switch" value="No" id="eqp_no_isolator_switch_no" class=" ">
                                                    <label for="eqp_no_isolator_switch_no" class="fw-400">No</label>
                                                </div>
                                            </div>


                                                {{-- SET LFI  --}}
                                            <label for="st7">{{__('messages.eqp_no')}} {{ __('messages.set_lfi') }} </label>
                                            <div class=" col-md-12 row">

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_set_lfi" value="Yes" id="eqp_no_set_lfi_yes" class="  ">
                                                    <label for="eqp_no_set_lfi_yes" class="fw-400">Yes</label>
                                                </div>

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_set_lfi" value="No" id="eqp_no_set_lfi_no" class=" ">
                                                    <label for="eqp_no_set_lfi_no" class="fw-400">No</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>





                             </fieldset>





                           {{-- START Kejanggalan (3) --}}
                           <h3>{{ __('messages.kejanggalan') }}</h3>

                           <fieldset class="form-input defects">
                               <h3>{{ __('messages.kejanggalan') }}</h3>

                               <div class="table-responsive" id="kejanggalan">
                                   <table class="table table-bordered w-100">
                                       <thead style="background-color: #E4E3E3 !important">
                                           <th class="col-4">{{ __('messages.title') }}</th>
                                           <th class="col-4" colspan="2">{{ __('messages.defects') }}</th>

                                       </thead>
                                       {{-- POLE --}}
                                       <tr>
                                           <th rowspan="2">{{ __('messages.pole') }}</th>
                                           <td>
                                               {{-- <input type="checkbox" name="tiang_rust" id="tiang_rust" class="form-check"> --}}
                                               <label for="tiang_rust"> {{ __('messages.rust') }}</label>
                                           </td>
                                           <td class="d-flex"> {!!  savtYesOrNo('tiang_rust') !!}</td>
                                       </tr>

                                       <tr>
                                           <td>
                                               {{-- <input type="checkbox" name="tiang_leaning" id="tiang_leaning" class="form-check"> --}}
                                               <label for="tiang_leaning"> {{ __('messages.leaning') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('tiang_leaning') !!}</td>

                                       </tr>


                                       {{-- CONDUCTOR PHASE --}}

                                       <tr>
                                           <th rowspan="4">{{ __('messages.phase_conductor') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="conductor_sagging" id="conductor_sagging" class="form-check"> --}}
                                               <label for="conductor_sagging"> {{ __('messages.sagging') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('conductor_sagging') !!}</td>
                                       </tr>
                                       <tr>
                                           <td  >
                                               {{-- <input type="checkbox" name="conductor_torn" id="conductor_torn" class="form-check"> --}}
                                               <label for="conductor_torn"> {{ __('messages.torn') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('conductor_torn') !!}</td>
                                       </tr>

                                       <tr>
                                           <td class="">
                                               {{-- <input type="checkbox" name="conductor_broken" id="conductor_broken" class="form-check"> --}}
                                               <label for="conductor_broken">{{ __('messages.broken') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('conductor_broken') !!}</td>
                                       </tr>
                                       <tr>
                                           <td>
                                               {{-- <input type="checkbox" name="conductor_hotpspot" id="conductor_hotpspot" class="form-check"> --}}
                                               <label for="conductor_hotspot"> {{ __('messages.hotspot') }} </label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('conductor_hotspot') !!}</td>
                                       </tr>



                                       {{-- Umbang --}}

                                       <tr>
                                           <th rowspan="2">{{ __('messages.Umbang') }}</th>
                                           <td >
                                               {{-- <input type="checkbox" name="umbang_sagging" id="umbang_sagging" class="form-check "> --}}
                                               <label for="umbang_sagging">{{ __('messages.Sagging_Breaking') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('umbang_sagging') !!}</td>

                                       </tr>
                                       <tr>
                                           <td  >
                                               {{-- <input type="checkbox" name="umbang_disconnect" id="umbang_disconnect" class="form-check "> --}}
                                               <label for="umbang_disconnect">{{ __('messages.disconnect') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('umbang_disconnect') !!}</td>

                                       </tr>




                                       {{-- CABLE TERMINATE --}}
                                       <tr>
                                           <th rowspan="2">{{ __('messages.cable_terminate') }}</th>
                                           <td>
                                               {{-- <input type="checkbox" name="cable_terminate_crossing" id="cable_terminate_crossing"class="form-check"> --}}
                                               <label for="cabel_terminate_crossing">{{ __('messages.crossing') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('cabel_terminate_crossing') !!}</td>
                                       </tr>
                                       <tr>
                                           <td>
                                               {{-- <input type="checkbox" name="cable_terminate_hotspot" id="cable_terminate_hotspot" class="form-check"> --}}
                                               <label for="cabel_terminate_hotspot">{{ __('messages.hotspot') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('cabel_terminate_hotspot') !!}</td>

                                       </tr>

                                       {{-- LIGHTNING ARRESTER OCP --}}

                                       <tr>
                                           <th rowspan="3">{{ __('messages.lightning_arrester_ocp') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="lightning_arrester_ocp_broken" id="lightning_arrester_ocp_broken" class="form-check"> --}}
                                               <label for="lightning_arrester_ocp_broken">{{ __('messages.broken') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('lightning_arrester_ocp_broken') !!}</td>

                                       </tr>
                                       <tr>
                                           <td>
                                               {{-- <input type="checkbox" name="lightning_arrester_ocp_disconnected" id="lightning_arrester_ocp_disconnected" class="form-check"> --}}
                                               <label for="lightning_arrester_ocp_disconnected">{{ __('messages.disconnect') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('lightning_arrester_ocp_disconnected') !!}</td>

                                       </tr>

                                       <tr>
                                           <td>
                                               {{-- <input type="checkbox" name="lightning_arrester_ocp_hot_spot" id="lightning_arrester_ocp_hot_spot" class="form-check"> --}}
                                               <label for="lightning_arrester_ocp_hot_spot">{{ __('messages.hotspot') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('lightning_arrester_ocp_hot_spot') !!}</td>

                                       </tr>

                                       {{-- LIGHTNING ARRESTER PAC --}}

                                       <tr>
                                           <th rowspan="3">{{ __('messages.lightning_arrester_pac') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="lightning_arrester_pac_broken" id="lightning_arrester_pac_broken" class="form-check"> --}}
                                               <label for="lightning_arrester_pac_broken">{{ __('messages.broken') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('lightning_arrester_pac_broken') !!}</td>

                                       </tr>
                                       <tr>
                                           <td  >
                                               {{-- <input type="checkbox" name="lightning_arrester_pac_disconnected" id="lightning_arrester_pac_disconnected" class="form-check"> --}}
                                               <label for="lightning_arrester_pac_disconnected">{{ __('messages.disconnect') }} </label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('lightning_arrester_pac_disconnected') !!}</td>

                                       </tr>
                                       <tr>
                                           <td>
                                               {{-- <input type="checkbox" name="lightning_arrester_pac_hot_spot" id="lightning_arrester_pac_hot_spot" class="form-check"> --}}
                                               <label for="lightning_arrester_pac_hot_spot">{{ __('messages.hotspot') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('lightning_arrester_pac_hot_spot') !!}</td>

                                       </tr>

                                       {{-- NEED RENTIS --}}

                                       <tr>
                                           <th >{{ __('messages.rentis') }}</th>
                                           <td >
                                               {{-- <input type="checkbox" name="need_rentis" id="need_rentis" class="form-check"> --}}
                                               <label for="need_rentis">{{ __('messages.need_rentis') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('need_rentis') !!}</td>

                                       </tr>

                                       {{-- SWITCH LINK --}}

                                       <tr>
                                           <th >{{ __('messages.switch_link') }}</th>
                                           <td >
                                               {{-- <input type="checkbox" name="switch_link_need_repair" id="switch_link_need_repair" class="form-check"> --}}
                                               <label for="switch_link_need_repair">{{ __('messages.need_repair') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('switch_link_need_repair') !!}</td>

                                       </tr>

                                       {{-- POLE NEED REPAINT --}}
                                       <tr>
                                           <th >{{ __('messages.tiang') }}</th>
                                           <td>
                                               {{-- <input type="checkbox" name="tiang_needs_repaint" id="tiang_needs_repaint" class="form-check"> --}}
                                               <label for="tiang_needs_repaint">{{ __('messages.needs_repaint') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('tiang_needs_repaint') !!}</td>

                                       </tr>

                                       {{-- UMBANG INSULATION STATUS --}}
                                       <tr>
                                           <th >{{ __('messages.umbang') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="umbang_insulation_status" id="umbang_insulation_status" class="form-check"> --}}
                                               <label for="insulation_status">{{ __('messages.insulation_status') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('umbang_insulation_status') !!}</td>

                                       </tr>



                                       {{-- EARTH BOUNDING --}}

                                       <tr>
                                           <th rowspan="3">{{ __('messages.earth_bounding') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="earth_bounding_status" id="earth_bounding_status" class="form-check"> --}}
                                               <label for="earth_bounding_status">{{ __('messages.status') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('earth_bounding_status') !!}</td>

                                       </tr>
                                       <tr>
                                           <td  >
                                               {{-- <input type="checkbox" name="earth_bounding_hotspt" id="earth_bounding_hotspt" class="form-check"> --}}
                                               <label for="earth_bounding_hotspt">{{ __('messages.hotspot') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('earth_bounding_hotspt') !!}</td>

                                       </tr>
                                       <tr>
                                           <td>
                                               {{-- <input type="checkbox" name="earthbounding_ultrasound_status" id="earthbounding_ultrasound_status" class="form-check"> --}}
                                               <label for="earthbounding_ultrasound_status">{{ __('messages.ultrasound_status') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('earthbounding_ultrasound_status') !!}</td>

                                       </tr>



                                       {{-- CABLE TRAY --}}

                                       <tr>
                                           <th >{{ __('messages.cable_tray') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="cable_tray_change" id="cable_tray_change" class="form-check"> --}}
                                               <label for="cabel_tray_change">{{ __('messages.change') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('cabel_tray_change') !!}</td>
                                       </tr>





                                       {{-- SUSPENSION --}}

                                       <tr>
                                           <th  >{{ __('messages.suspension') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="suspension_clamp_change" id="suspension_clamp_change" class="form-check"> --}}
                                               <label for="suspension_clamp_change">{{ __('messages.clamp_change') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('suspension_clamp_change') !!}</td>
                                       </tr>





                                       {{-- TRIANGULAR --}}

                                       <tr>
                                           <th  >{{ __('messages.triangular') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="triangular_braker_change" id="triangular_braker_change" class="form-check"> --}}
                                               <label for="triangular_braker_change">{{ __('messages.braker_change') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('triangular_braker_change') !!}</td>

                                       </tr>




                                       {{-- CROSSARM --}}

                                       <tr>
                                           <th  rowspan="2">{{ __('messages.crossarm') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="crossarm_rust" id="crossarm_rust" class="form-check"> --}}
                                               <label for="crossarm_rust">{{ __('messages.rust') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('crossarm_rust') !!}</td>

                                       </tr>
                                       <tr>
                                           <td  >
                                               {{-- <input type="checkbox" name="crossarm_bent" id="crossarm_bent" class="form-check"> --}}
                                               <label for="crossarm_bent">{{ __('messages.bent') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('crossarm_bent') !!}</td>

                                       </tr>




                                       {{-- EARTH CROSSARM --}}

                                       <tr>
                                           <th rowspan="2">{{ __('messages.earth_crossarm') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="earth_crossarm_rust" id="earth_crossarm_rust" class="form-check"> --}}
                                               <label for="earth_crossarm_rust">{{ __('messages.rust') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('earth_crossarm_rust') !!}</td>

                                       </tr>
                                       <tr>
                                           <td  >
                                               {{-- <input type="checkbox" name="earth_crossarm_bent" id="earth_crossarm_bent" class="form-check"> --}}
                                               <label for="earth_crossarm_bent">{{ __('messages.bent') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('earth_crossarm_bent') !!}</td>

                                       </tr>




                                       {{-- CONDUCTOR OF THE EARTH --}}

                                       <tr>
                                           <th rowspan="3">{{ __('messages.conductor_of_the_earth') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="ce_sagging" id="ce_sagging" class="form-check"> --}}
                                               <label for="ce_sagging">{{ __('messages.sagging') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('ce_sagging') !!}</td>

                                       </tr>
                                       <tr>
                                           <td  >
                                               {{-- <input type="checkbox" name="ce_btc" id="ce_btc" class="form-check"> --}}
                                               <label for="ce_btc">{{ __('messages.bumi_tangga_connection') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('ce_btc') !!}</td>

                                       </tr>
                                       <tr>
                                           <td>
                                               {{-- <input type="checkbox" name="ce_broken" id="ce_broken" class="form-check"> --}}
                                               <label for="ce_broken">{{ __('messages.broken') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('ce_broken') !!}</td>

                                       </tr>




                                       {{-- WIRE TO EARTH --}}

                                       <tr>
                                           <th  >{{ __('messages.wire_to_earth') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="wte_hanging_disconnected" id="wte_hanging_disconnected" class="form-check"> --}}
                                               <label for="wte_hanging_disconnected">{{ __('messages.hanging_disconnected') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('wte_hanging_disconnected') !!}</td>

                                       </tr>




                                       {{-- INSULATION --}}

                                       <tr>
                                           <th rowspan="4">{{ __('messages.insulation') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="insulation_flashover" id="insulation_flashover" class="form-check"> --}}
                                               <label for="insulation_flashover">{{ __('messages.flashover') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('insulation_flashover') !!}</td>

                                       </tr>
                                       <tr>
                                           <td >
                                               {{-- <input type="checkbox" name="insulation_full" id="insulation_full" class="form-check"> --}}
                                               <label for="insulation_full">{{ __('messages.full') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('insulation_full') !!}</td>

                                       </tr>
                                       <tr>
                                           <td>
                                               {{-- <input type="checkbox" name="insulation_broken" id="insulation_broken" class="form-check"> --}}
                                               <label for="insulation_broken">{{ __('messages.broken') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('insulation_broken') !!}</td>

                                       </tr>


                                        <tr>
                                            <td>
                                                {{-- <input type="checkbox" name="insulation_hotspot" id="insulation_hotspot" class="form-check"> --}}
                                                <label for="insulation_hotspot">{{ __('messages.hotspot') }}</label>
                                            </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('insulation_hotspot') !!}</td>

                                        </tr>




                                       {{-- LIGHTNING CATCHER ON LINE --}}

                                       <tr>
                                           <th rowspan="2">{{ __('messages.lightning_catcher_on_line') }}</th>
                                           <td>
                                               {{-- <input type="checkbox" name="lcol_ripped_off" id="lcol_ripped_off" class="form-check"> --}}
                                               <label for="lcol_ripped_off">{{ __('messages.ripped_off') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('lcol_ripped_off') !!}</td>

                                       </tr>
                                       <tr>
                                           <td  >
                                               {{-- <input type="checkbox" name="lcol_hotspot" id="lcol_hotspot" class="form-check"> --}}
                                               <label for="lcol_hotspot">{{ __('messages.hotspot') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('lcol_hotspot') !!}</td>

                                       </tr>




                                       {{-- JUMPER --}}

                                       <tr>
                                           <th rowspan="2">{{ __('messages.jumper') }}</th>
                                           <td  >
                                               {{-- <input type="checkbox" name="jumper_need_repair" id="jumper_need_repair" class="form-check"> --}}
                                               <label for="jumper_need_repair">{{ __('messages.need_repair') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('jumper_need_repair') !!}</td>

                                       </tr>
                                       <tr>
                                           <td  >
                                               {{-- <input type="checkbox" name="jumper_hotspot" id="jumper_hotspot" class="form-check"> --}}
                                               <label for="jumper_hotspot">{{ __('messages.hotspot') }}</label>
                                           </td>
                                           <td  class="d-flex">{!!  savtYesOrNo('jumper_hotspot') !!}</td>

                                       </tr>




                                       {{-- PG CLAMPS / CONNECTORS --}}

                                       <tr>
                                            <th rowspan="2">{{ __('messages.pg_clamps_connectors') }}</th>
                                            <td >
                                                {{-- <input type="checkbox" name="pg_cc_need_change" id="pg_cc_need_change" class="form-check"> --}}
                                                <label for="pg_cc_need_change">{{ __('messages.need_change') }}</label>
                                            </td>
                                            <td  class="d-flex">{!!  savtYesOrNo('pg_cc_need_change') !!}</td>

                                        </tr>
                                        <tr>
                                            <td >
                                                {{-- <input type="checkbox" name="pg_cc_hotspot" id="pg_cc_hotspot" class="form-check"> --}}
                                                <label for="pg_cc_hotspot">{{ __('messages.hotspot') }}</label>
                                            </td>
                                            <td  class="d-flex">{!!  savtYesOrNo('pg_cc_hotspot') !!}</td>

                                        </tr>





                                    {{-- Climbing Barriers --}}

                                    <tr>
                                        <th  >{{ __('messages.climbing_barriers') }}</th>
                                        <td  >
                                            {{-- <input type="checkbox" name="climbing_barrier_need_change" id="climbing_barrier_need_change" class="form-check"> --}}
                                            <label for="climbing_barrier_need_change">{{ __('messages.need_change') }}</label>
                                        </td>
                                        <td  class="d-flex">{!!  savtYesOrNo('climbing_barrier_need_change') !!}</td>

                                    </tr>





                                    {{-- Arcing Horn --}}

                                    <tr>
                                        <th >{{ __('messages.arcing_horn') }}</th>
                                        <td  >
                                            {{-- <input type="checkbox" name="arcing_horn_need_repair" id="arcing_horn_need_repair" class="form-check"> --}}
                                            <label for="arcing_horn_need_repair">{{ __('messages.need_repair') }}</label>
                                        </td>
                                        <td  class="d-flex">{!!  savtYesOrNo('arcing_horn_need_repair') !!}</td>
                                    </tr>




                                    {{-- LFI --}}

                                    <tr>
                                        <th >{{ __('messages.lfi') }}</th>
                                        <td >
                                            {{-- <input type="checkbox" name="lfi_break_date" id="lfi_break_date" class="form-check"> --}}
                                            <label for="lfi_break_date">{{ __('messages.break_date') }}</label>
                                        </td>

                                        <td  class="d-flex">{!!  savtYesOrNo('lfi_break') !!}</td>

                                    </tr>

                                   </table>
                               </div>
                               <input type="hidden" name="total_defects" id="total_defects">
                           </fieldset>




                            <h3>{{ __('messages.Kebocoran_Arus') }}</h3>


                            {{-- START Kebocoran Arus (5) --}}

                            <fieldset class="form-input">
                                <h3>{{ __('messages.Kebocoran_Arus') }}</h3>

                                    {{-- REMARKS --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="remarks">{{__('messages.remarks')}}</label></div>
                                    <div class="col-md-4 mb-4">
                                        <textarea name="remarks" id="remarks" cols="30" rows="6" class="form-control"></textarea>
                                    </div>
                                </div>

                                    {{-- FIVE FEET AWAY --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="five_feet_away">{{ __('messages.five_feet_away') }} </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="five_feet_away" id="five_feet_away" class="form-control">
                                    </div>
                                </div>

                                    {{-- FFA NO OF HOUSES --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="ffa_no_of_houses">{{ __('messages.ffa_no_of_houses') }} </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="ffa_no_of_houses" id="ffa_no_of_houses" class="form-control">
                                    </div>
                                </div>

                                    {{-- FFA HOUSE NO --}}
                                <div class="row">
                                    <div class="col-md-4 ">
                                        <label for="ffa_house_no">{{ __('messages.ffa_house_no') }} </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="ffa_house_no" id="ffa_house_no" class="form-control">
                                    </div>
                                </div>
                            </fieldset>
                            {{-- END Kebocoran Arus (5) --}}
                        </form>

        </div>
    </section>
@endsection

@section('script')
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    <script src="{{ URL::asset('assets/test/js/jquery.steps.js') }}"></script>

    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    <script src="{{ URL::asset('map/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.js') }}"></script>

    <script>
        var form = $("#framework-wizard-form").show();
        form.steps(
            {
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",
                onStepChanging: function(event, currentIndex, newIndex) {
                    if (currentIndex > newIndex) {
                        return true;
                    }
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                },
                onFinished: function(event, currentIndex) {
                    form.submit();
                },
                // autoHeight: true,
            })




        function getWp(param)
        {
            var splitVal = param.value.split(',');
            addRemoveBundary(splitVal[1], splitVal[2], splitVal[3])
            $('#ba').val(splitVal[1])
        }


        function submitFoam() {
            if ($('#lat').val() == '' || $('#log').val() == '') {
                $('.map-error').html('Please select location')
                return false;
            } else {
                $('.map-error').html(' ')
            }
        }
    </script>


    <script type="text/javascript">
        var baseLayers
        var identifyme = '';
        var boundary3 = '';
        var marker = '';
        var boundary2 = '';
        map = L.map('map').setView([3.016603, 101.858382], 5);



        var st1 = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map); // satlite map

        var street = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'); // street map

        // ADD MAPS
        baseLayers = {
            "Satellite": st1,
            "Street": street
        };


        boundary3 = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
            layers: 'cite:aero_apks',
            format: 'image/png',
            maxZoom: 21,
            transparent: true
        }, {
            buffer: 10
        })



        // ADD LAYERS GROUPED OVER LAYS
        groupedOverlays = {
            "POI": {
                'BA': boundary3,
            }
        };

        var layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
            collapsed: true,
            position: 'topright'
            // groupCheckboxes: true
        }).addTo(map);



        // add boundary layer on page load
        map.addLayer(boundary3)
        map.setView([2.59340882301331, 101.07054901123], 8);


        // change layer and view when ba change
        function addRemoveBundary(param, paramY, paramX) {

            if (boundary3 != '') {
                map.removeLayer(boundary3) // Remove on page load boundary
            }


            if (boundary2 !== '') { // boundary if eesixts then first reomve from map
                map.removeLayer(boundary2)
            }

            boundary2 = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
                layers: 'cite:ba',
                format: 'image/png',
                cql_filter: "station='" + param + "'", // add ba name for filter boundary
                maxZoom: 21,
                transparent: true
            }, {
                buffer: 10
            })

            map.addLayer(boundary2) // add filtered boundary
            boundary2.bringToFront()

            map.setView([parseFloat(paramY), parseFloat(paramX)], 10); // set view

        }

        // on click map add marker and bind popup
        function onMapClick(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng);
            map.addLayer(marker);
            marker.bindPopup("<b>You clicked the map at " + e.latlng.toString()).openPopup();

            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            $('#lat').val(lat);
            $('#log').val(lng);
            $('#cordinates').val(e.latlng);
        }

        map.on('click', onMapClick);
    </script>

    <script>
        const b1Options = [
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

        const userBa = "{{ Auth::user()->ba }}";
        $(document).ready(function()
        {

            if (userBa !== '') {
                getBaPoints(userBa)
            }

            $('input[type="file"]').on('change', function() {
                showUploadedImage(this)
            })

        });

        // DISPALY UPLOADED IMAGE
        function showUploadedImage(param)
        {
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

        var total_defects = 0;


        $('#total_defects').val(total_defects)



        function getBaPoints(param)
        {
            var baSelect = $('#ba_s')
            baSelect.empty();

            b1Options.map((data) => {
                if (data[1] == param) {
                    baSelect.append(`<option value="${data}">${data[1]}</option>`)
                }
            });
            let baVal = document.getElementById('ba_s');
            getWp(baVal)
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
