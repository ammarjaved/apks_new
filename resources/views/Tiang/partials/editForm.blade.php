



    <h3>{{ __('messages.info') }} </h3>


    {{-- START Info (1) --}}
    <fieldset class=" form-input">

        {{-- BA --}}
        <div class="row">
            <div class="col-md-4"><label for="ba">{{ __('messages.ba') }}</label></div>
            <div class="col-md-4">
                <select name="ba" id="ba" class="form-control" required>
                    <option value="{{ $data->ba }}">{{ $data->ba }}</option>
                    @if (Auth::user()->ba == '')
                        <optgroup label="W1">
                            <option value="KUALA LUMPUR PUSAT">KL PUSAT</option>
                        </optgroup>
                        <optgroup label="B1">
                            <option value="PETALING JAYA">PETALING JAYA</option>
                            <option value="RAWANG">RAWANG</option>
                            <option value="KUALA SELANGOR">KUALA SELANGOR</option>
                        </optgroup>
                        <optgroup label="B2">
                            <option value="KLANG">KLANG</option>
                            <option value="PELABUHAN KLANG">PELABUHAN KLANG</option>
                        </optgroup>
                        <optgroup label="B4">
                            <option value="CHERAS">CHERAS</option>
                            <option value="BANTING">BANTING</option>
                            <option value="BANGI">BANGI</option>
                            <option value="PUTRAJAYA & CYBERJAYA">PUTRAJAYA & CYBERJAYA</option>
                        </optgroup>
                    @endif
                </select>
            </div>
        </div>

        {{-- FP NAME --}}
        <div class="row">
            <div class="col-md-4"><label for="fp_name"> {{ __('messages.name_of_substation') }} / {{ __('messages.Name_of_Feeder_Pillar') }} </label></div>
            <div class="col-md-4">
                <input type="text" name="fp_name" value="{{ $data->fp_name }}" id="fp_name" class="form-control" required>
            </div>
        </div>

        {{-- FEEDER NAME --}}
        <div class="row">
            <div class="col-md-4"><label for="fp_road"> {{ __('messages.Feeder_Name') }} / {{ __('messages.Street_Name') }}</label></div>
            <div class="col-md-4">
                <input type="text" name="fp_road" value="{{ $data->fp_road }}" id="fp_road" class="form-control" required>
            </div>
        </div>

        {{-- SECTION --}}
        <div class="row">
            <div class="col-md-4"><label for="">{{ __('messages.Section') }} </label></div>
        </div>

        {{-- SECTON FROM --}}
        <div class="row">
            <div class="col-md-4"><label for="section_from">{{ __('messages.from') }} </label></div>
            <div class="col-md-4">
                <input type="text" name="section_from" value="{{ $data->section_from }}" id="section_from" class="form-control">
            </div>
        </div>



        {{-- FROM TIANG IMAGES --}}


        @isset($fromPoleImage1)
            <div class="row">
                <div class="col-4">
                    <a class="text-right"  href="{{config('globals.APP_IMAGES_URL').$fromPoleImage1}}" data-lightbox="roadtrip">
                        <img src="{{config('globals.APP_IMAGES_URL').$fromPoleImage1}}" style="height:50px;"/>
                    </a>
                </div>
                <div class="col-4">
                    <a class="text-right"  href="{{config('globals.APP_IMAGES_URL').$fromPoleImage2}}" data-lightbox="roadtrip">
                        <img src="{{config('globals.APP_IMAGES_URL').$fromPoleImage2}}" style="height:50px;"/>
                    </a>
                </div>
            </div>
        @endisset




        {{-- SECTION TO --}}
        <div class="row">
            <div class="col-md-4"><label for="section_to">{{ __('messages.to') }}</label></div>
            <div class="col-md-4">
                <input type="text" name="section_to" value="{{ $data->section_to }}" id="section_to" class="form-control">
            </div>
        </div>

        {{-- TIANG NO --}}
        <div class="row">
            <div class="col-md-4"><label for="tiang_no">{{ __('messages.Tiang_No') }}</label></div>
            <div class="col-md-4">
                <input type="text" name="tiang_no" value="{{ $data->tiang_no }}" id="tiang_no" class="form-control" required>
            </div>
        </div>

        {{-- VISIT DATE --}}
        <div class="row">
            <div class="col-md-4"><label for="review_date">{{__('messages.visit_date')}}</label></div>
            <div class="col-md-4">
                <input type="date" name="review_date" id="review_date" class="form-control" required  value="{{ $data->review_date }}">
            </div>
        </div>

        {{-- MAIN LINE SERVICE LINE --}}
        <div class="row">
            <div class="col-md-4">
                <label for="main_line">{{__('messages.main_line_service_line')}}</label>
            </div>
            <div class="col-md-5 d-flex">
                <div class="col-md-6">
                    <input type="checkbox" name="main_line" id="main_line" {{$data->main_line != '' ? 'checked' : ''}} >
                    <label for="main_line">Main Line</label>
                </div>
                <div class="col-md-6">
                    <input type="checkbox" name="service_line" id="service_line"  {{$data->service_line != '' ? 'checked' : ''}}>
                    <label for="service_line">Service Line</label>
                </div>
            </div>
        </div>

        {{-- Number of Services Involves 1 user only --}}
        <div class="row " id="main_line_connection">
            <div class="col-md-4"><label for="">Number of Services Involves 1 user only</label></div>
            <div class="col-md-4">
                <input type="number" name="talian_utama" value="{{$data->talian_utama}}" class="form-control" id="main_line_connection_one"  >
            </div>
        </div>

    </fieldset>
    {{-- END Info (1) --}}


    {{-- IMAGES --}}
    <h3>{{__('messages.images')}}</h3>

    <fieldset class="form-input">

        {{-- POLE IMAGE 1 --}}
        <div class="row">
            <div class="col-md-4"><label for="pole_image-1">{{ __('messages.pole') }} Image 1 </label></div>
            <div class="col-md-8 row">{!!  viewAndUpdateImage($data->pole_image_1 , 'pole_image_1' , false )  !!}</div>
        </div>

        {{-- POLE IMAGE 2 --}}

        <div class="row">
            <div class="col-md-4"><label for="pole_image-2">{{ __('messages.pole') }} Image 2</label></div>
            <div class="col-md-8 row">{!!  viewAndUpdateImage($data->pole_image_2 , 'pole_image_2' , false )  !!}</div>

        </div>

        {{-- POLE IMAGE 3 --}}

        <div class="row">
            <div class="col-md-4"><label for="pole_image-3">{{ __('messages.pole') }} Image 3 </label></div>
            <div class="col-md-8 row">{!!  viewAndUpdateImage($data->pole_image_3 , 'pole_image_3' , false )  !!}</div>
        </div>

        {{-- POLE IMAGE 4 --}}
        <div class="row">
            <div class="col-md-4"><label for="pole_image-4">{{ __('messages.pole') }} Image 4</label></div>
            <div class="col-md-8 row">{!!  viewAndUpdateImage($data->pole_image_4 , 'pole_image_4' , false )  !!}</div>
        </div>

        {{-- POLE IMAGE 5 --}}
        <div class="row">
            <div class="col-md-4"><label for="pole_image-5">{{ __('messages.pole') }} Image 5 </label></div>
            <div class="col-md-8 row">{!!  viewAndUpdateImage($data->pole_image_5 , 'pole_image_5' , false )  !!}</div>
        </div>

    </fieldset>

    {{-- END IMAGES --}}

    <h3> {{ __('messages.Asset_Register') }} </h3>

    {{-- START Asset Register (2) --}}

    <fieldset class="form-input">
        <div class="row">
            <div class="col-md-6">

                {{-- POLE SIZE BILL --}}
                <div class="card p-4">
                    <label for="st7">{{ __('messages.Pole_Size_Bill') }} </label>
                    <div class="row">
                        <div class="col-md-12 row">

                            {{-- POLE SIZE BILL 7.5 --}}
                            <div class="d-flex col-md-4">
                                <input type="radio" name="size_tiang" value="7.5" id="st7" {{ $data->size_tiang == '7.5' ? 'checked' : '' }}class="  ">
                                <label for="st7" class="fw-400"> 7.5</label>
                            </div>

                            {{-- POLE SIZE BILL 9 --}}
                            <div class="d-flex col-md-4">
                                <input type="radio" name="size_tiang" value="9" id="st9" {{ $data->size_tiang == '9' ? 'checked' : '' }} class=" ">
                                <label for="st9" class="fw-400"> 9</label>
                            </div>

                            {{-- POLE SIZE BILL SIZE TIANG --}}
                            <div class="d-flex col-md-4">
                                <input type="radio" name="size_tiang" value="10" id="st10" {{ $data->size_tiang == '10' ? 'checked' : '' }} class=" ">
                                <label for="st10" class="fw-400"> 10</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">

                {{-- POLE  TYPE NO --}}
                <div class="card p-4">
                    <label for="">{{ __('messages.Pole_type_No') }} </label>
                    <div class="row">
                        <div class="col-md-12 row">

                            {{-- POLE TYPE NO SPUN --}}
                            <div class="d-flex col-md-4">
                                <input type="radio" name="jenis_tiang" value="spun" id="spun" class=" " {{ $data->jenis_tiang == 'spun' ? 'checked' : '' }}>
                                <label for="spun" class="fw-400">{{ __('messages.Spun') }}</label>
                            </div>

                            {{-- POLE TYPE NO CONCRETE --}}
                            <div class="d-flex col-md-4">
                                <input type="radio" name="jenis_tiang" value="concrete" id="concrete" class=" " {{ $data->jenis_tiang == 'concrete' ? 'checked' : '' }}>
                                <label for="concrete" class="fw-400">{{ __('messages.Concrete') }}</label>
                            </div>

                            {{-- POLE TYPE NO IRON --}}
                            <div class="d-flex col-md-4">
                                <input type="radio" name="jenis_tiang" value="iron" id="iron" class=" " {{ $data->jenis_tiang == 'iron' ? 'checked' : '' }}>
                                <label for="iron" class="fw-400">{{ __('messages.Iron') }}</label>
                            </div>

                            {{-- POLE TYPE NO WOOD --}}
                            <div class="d-flex col-md-4">
                                <input type="radio" name="jenis_tiang" value="wood" id="wood" class=" " {{ $data->jenis_tiang == 'wood' ? 'checked' : '' }}>
                                <label for="wood" class="fw-400">{{ __('messages.Wood') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ABC SPAN --}}
            <div class="col-md-6">
                <div class="card p-4">

                    {{-- ABC SPAN 3 X 185--}}
                    <label for="section_to">{{ __('messages.ABC_Span') }} 3 X 185</label>
                        {!! tiangSpanRadio(  $data->abc_span, 'abc_span', 's3_185',  true) !!}

                    {{-- ABC SPAN 3 X 95 --}}
                    <label for="s3_95">{{ __('messages.ABC_Span') }}3 X 95</label>
                        {!! tiangSpanRadio(  $data->abc_span, 'abc_span', 's3_95',  true) !!}

                    {{-- ABC SPAN 3 X 16--}}
                    <label for="s3_16">{{ __('messages.ABC_Span') }}>3 X 16</label>
                        {!! tiangSpanRadio(  $data->abc_span, 'abc_span', 's3_16',  true) !!}

                    {{-- ABC SPAN  1 X 16--}}
                    <label for="s1_16">{{ __('messages.ABC_Span') }}1 X 16</label>
                        {!! tiangSpanRadio(  $data->abc_span, 'abc_span', 's1_16',  true) !!}
                </div>
            </div>

            {{-- PVC SPAN --}}
            <div class="col-md-6 ">
                <div class="card p-4">

                    {{-- PVC SPAN 19/064 --}}
                    <label for="s19_064">{{ __('messages.PVC_Span') }}19/064</label>
                        {!! tiangSpanRadio(    $data->pvc_span, 'pvc_span', 's19_064',  true) !!}

                    {{-- PVC SPAN 7/083--}}
                    <label for="s7_083"  >{{ __('messages.PVC_Span') }}7/083</label>
                        {!! tiangSpanRadio($data->pvc_span, 'pvc_span', 's7_083',  true) !!}

                    {{-- PVC SPAN 7/044--}}
                    <label for="s7_044"  >{{ __('messages.PVC_Span') }}7/044</label>
                        {!! tiangSpanRadio(  $data->pvc_span, 'pvc_span', 's7_044',  true) !!}
                </div>
            </div>

            {{-- BARE SPAN --}}
            <div class="col-md-6">
                <div class="card p-4">

                    {{-- BARE SPAN 7/173 --}}
                    <label for="s7_173">{{ __('messages.BARE_Span') }} 7/173</label>
                        {!! tiangSpanRadio(  $data->bare_span, 'bare_span', 's7_173',  true) !!}

                    {{-- BARE SPAN 7/122 --}}
                    <label for="s7_122">{{ __('messages.BARE_Span') }} 7/122</label>
                        {!! tiangSpanRadio(  $data->bare_span, 'bare_span', 's7_122',  true) !!}

                    {{-- BARE SPAN 3/132 --}}
                    <label for="s3_132">{{ __('messages.BARE_Span') }} 3/132</label>
                        {!! tiangSpanRadio(  $data->bare_span, 'bare_span', 's3_132',  true) !!}
                </div>
            </div>
        </div>
    </fieldset>

    {{-- END Asset Register (2) --}}


    <h3>{{ __('messages.kejanggalan') }}</h3>

    {{-- START KEJANGGALAN --}}
    <fieldset class="form-input defects">

        <h3>{{ __('messages.kejanggalan') }}</h3>
        <div class="table-responsive">
            <table class="table table-bordered w-100">
                <thead style="background-color: #E4E3E3 !important">
                    <th class="col-4">{{ __('messages.title') }}</th>
                    <th class="col-4">{{ __('messages.defects') }}</th>
                </thead>
                {{-- POLE --}}
                <tr>
                    <th rowspan="5">{{ __('messages.pole') }}</th>
                    {!! tiangDefactCheckBox('cracked', $data->tiang_defect, 'tiang_defect',  'cracked',true ) !!}
                </tr>
                <tr>{!! tiangDefactCheckBox('leaning', $data->tiang_defect, 'tiang_defect', 'leaning',true ) !!}</tr>
                <tr>{!! tiangDefactCheckBox('dim', $data->tiang_defect, 'tiang_defect', 'no_dim_post_none',true ) !!}</tr>
                <tr>{!! tiangDefactCheckBox('creepers', $data->tiang_defect, 'tiang_defect',  'creepers',true ) !!}</tr>
                <tr>{!! tiangDefactCheckBox('other', $data->tiang_defect, 'tiang_defect', 'others',true ) !!}</tr>

                {{-- Line (Main / Service) --}}
                <tr>
                    <th rowspan="4">{{ __('messages.line_main_service') }}</th>
                    {!! tiangDefactCheckBox('joint', $data->talian_defect, 'talian_defect', 'joint',true ) !!}
                </tr>
                <tr>{!! tiangDefactCheckBox('need_rentis', $data->talian_defect, 'talian_defect', 'need_rentis',true ) !!}</tr>
                <tr>{!! tiangDefactCheckBox( 'ground', $data->talian_defect, 'talian_defect', 'Does_Not_Comply_With_Ground_Clearance',true ) !!}</tr>
                <tr>{!! tiangDefactCheckBox('other', $data->talian_defect, 'talian_defect', 'others',true ) !!}</tr>

                {{-- Umbang --}}
                <tr>
                    <th rowspan="5">{{ __('messages.Umbang') }}</th>
                    {!! tiangDefactCheckBox('breaking', $data->umbang_defect, 'umbang_defect', 'Sagging_Breaking',true ) !!}
                </tr>
                <tr>{!! tiangDefactCheckBox('creepers', $data->umbang_defect, 'umbang_defect', 'Creepers',true ) !!}</tr>
                <tr>{!! tiangDefactCheckBox( 'cracked', $data->umbang_defect, 'umbang_defect', 'No_Stay_Insulator_Damaged',true ) !!}</tr>
                <tr>{!! tiangDefactCheckBox( 'stay_palte', $data->umbang_defect, 'umbang_defect', 'Stay_Plate_Base_Stay_Blocked',true ) !!}</tr>
                {{-- <tr>{!! tiangDefactCheckBox('current_leakage', $data->umbang_defect, 'umbang_defect', $data->umbang_defect_image, 'current_leakage',true ) !!}</tr> --}}
                <tr>{!! tiangDefactCheckBox('other', $data->umbang_defect, 'umbang_defect', 'others',true ) !!}</tr>

                {{-- IPC --}}
                <tr>
                    <th rowspan="2">{{ __('messages.IPC') }}</th>
                    {!! tiangDefactCheckBox('burn', $data->ipc_defect, 'ipc_defect', 'Burn Effect',true ) !!}
                </tr>
                <tr>{!! tiangDefactCheckBox('other', $data->ipc_defect, 'ipc_defect', 'others',true ) !!}</tr>

                {{-- Black Box --}}

                <tr>
                    <th rowspan="2">{{ __('messages.Black_Box') }}</th>
                    {!! tiangDefactCheckBox('cracked', $data->blackbox_defect, 'blackbox_defect', 'Kesan_Bakar',true ) !!}
                </tr>
                <tr>{!! tiangDefactCheckBox('other', $data->blackbox_defect, 'blackbox_defect', 'others',true ) !!}</tr>

                {{-- Jumper --}}
                <tr>
                    <th rowspan="3">{{ __('messages.jumper') }}</th>
                    {!! tiangDefactCheckBox('sleeve', $data->jumper, 'jumper', 'no_uv_sleeve',true ) !!}
                </tr>
                <tr>{!! tiangDefactCheckBox('burn', $data->jumper, 'jumper', 'Burn Effect',true ) !!}</tr>
                <tr>{!! tiangDefactCheckBox('other', $data->jumper, 'jumper', 'others',true ) !!}</tr>

                {{-- Lightning catcher --}}
                <tr>
                    <th rowspan="2">{{ __('messages.lightning_catcher') }}</th>
                    {!! tiangDefactCheckBox('broken', $data->kilat_defect, 'kilat_defect', 'broken',true ) !!}
                </tr>
                <tr>{!! tiangDefactCheckBox('other', $data->kilat_defect, 'kilat_defect', 'others',true ) !!}</tr>

                {{-- Service --}}
                <tr>
                    <th rowspan="3">{{ __('messages.Service') }}</th>
                    {!! tiangDefactCheckBox('roof', $data->servis_defect, 'servis_defect', 'the_service_line_is_on_the_roof',true ) !!}
                </tr>
                <tr>{!! tiangDefactCheckBox('won_piece', $data->servis_defect, 'servis_defect',  'won_piece_date',true ) !!}</tr>
                <tr>{!! tiangDefactCheckBox('other', $data->servis_defect, 'servis_defect', 'others',true ) !!}</tr>

                {{-- Grounding --}}
                <tr>
                    <th rowspan="2">{{ __('messages.grounding') }}</th>
                    {!! tiangDefactCheckBox('netural', $data->pembumian_defect, 'pembumian_defect', 'no_connection_to_neutral',true ) !!}
                </tr>
                <tr>{!! tiangDefactCheckBox('other', $data->pembumian_defect, 'pembumian_defect', 'others',true ) !!}</tr>

                {{-- Signage - OFF Point / Two Way Supply --}}
                <tr>
                    <th rowspan="2">{{ __('messages.signage_off_point_two_way_supply') }}</th>
                    {!! tiangDefactCheckBox('damage', $data->bekalan_dua_defect, 'bekalan_dua_defect', 'faded_damaged_missing_signage',true ) !!}
                </tr>
                <tr>{!! tiangDefactCheckBox('other', $data->bekalan_dua_defect, 'bekalan_dua_defect', 'others',true ) !!}</tr>

                {{-- Main Street --}}
                <tr>
                    <th rowspan="3">{{ __('messages.main_street') }}</th>
                    {!! tiangDefactCheckBox('date_wire', $data->kaki_lima_defect, 'kaki_lima_defect', 'date_wire',true ) !!}
                </tr>
                <tr>{!! tiangDefactCheckBox('burn', $data->kaki_lima_defect, 'kaki_lima_defect',   'junction_box_date_burn_effect',true ) !!}</tr>
                <tr>{!! tiangDefactCheckBox('other', $data->kaki_lima_defect, 'kaki_lima_defect',  'others',true ) !!}</tr>
            </table>
        </div>
        <input type="hidden" name="total_defects" id="total_defects">
    </fieldset>
    {{-- END KEJANGGALAN --}}





    <h3>{{ __('messages.Heigh_Clearance') }}</h3>

    {{-- START Heigh Clearance (4) --}}

    <fieldset class="form-input high-clearance">
        <div class="table-responsive">
            <table class="table table-bordered w-100">
                <thead style="background-color: #E4E3E3 !important">
                    <th class="col-4">{{ __('messages.title') }}</th>
                    <th class="col-4">{{ __('messages.defects') }}</th>
                </thead>
                <tbody>
                    {{-- Site Conditions --}}
                    <tr>
                        <th rowspan="3">{{ __('messages.Site_Conditions') }} </th>
                        {{-- CROSSING THE ROAD --}}
                        {!! tiangDefactCheckBox('road', $data->tapak_condition, 'tapak_condition',  'Crossing_the_Road',true ) !!}
                    </tr>
                    <tr>
                        {!! tiangDefactCheckBox('side_walk', $data->tapak_condition, 'tapak_condition',  'Sidewalk',true ) !!}
                    </tr>
                    <tr>
                        {!! tiangDefactCheckBox('vehicle_entry', $data->tapak_condition, 'tapak_condition',  'No_vehicle_entry_area',true ) !!}
                    </tr>


                    {{-- Area --}}
                    <tr>
                        <th rowspan="4">{{ __('messages.Area') }}</th>
                        {!! tiangDefactCheckBox('bend', $data->kawasan, 'kawasan',  'Bend',true ) !!}
                    </tr>
                    <tr>
                        {!! tiangDefactCheckBox('road', $data->kawasan, 'kawasan',  'Road',true ) !!}
                    </tr>
                    <tr>
                        {!! tiangDefactCheckBox('forest', $data->kawasan, 'kawasan',  'Forest',true ) !!}
                    </tr>
                    <tr>
                        {!! tiangDefactCheckBox('other', $data->kawasan, 'kawasan',  'others',true ) !!}

                    </tr>

                </tbody>
            </table>
        </div>


        {{-- CLEARANCE DISTANCE --}}
        <div class="row">
            <div class="col-md-6"><label for="jarak_kelegaan">{{ __('messages.Clearance_Distance') }}</label></div>
            <div class="col-md-6">
                <input type="text" name="jarak_kelegaan" value="{{ $data->jarak_kelegaan }}" id="jarak_kelegaan" class="form-control">
            </div>
        </div>


        <div class="row">
            <div class="col-md-6"><label for="">{{ __('messages.Line_clearance_specifications') }}</label></div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <input type="radio" name="talian_spec" id="line-comply" {{ $data->talian_spec == 'comply' ? 'checked' : '' }} value="comply" class="form-check">
                        <label for="line-comply">{{ __('messages.Comply') }}</label>
                    </div>
                    <div class="col-md-6 d-flex">
                        <input type="radio" name="talian_spec" {{ $data->talian_spec == 'uncomply' ? 'checked' : '' }} value="uncomply" id="line-disobedient" class="form-check">
                        <label for="line-disobedient"> Uncomply </label>
                    </div>
                </div>
            </div>
        </div>

    </fieldset>

                            {{-- END Heigh Clearance (4) --}}



                            <h3>{{ __('messages.Kebocoran_Arus') }}</h3>




                            {{-- START Kebocoran Arus (5) --}}

                            <fieldset class="form-input">


                                <div class="row">
                                    <div class="col-md-4"><label
                                            for="">{{ __('messages.Inspection_of_current_leakage_on_the_pole') }}
                                        </label></div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-4 d-flex">
                                                <input type="radio" name="tiang_defect_current_leakage" id="arus_pada_tiang_no"
                                                    class="form-check" value="No"
                                                    {{ $data->arus_pada_tiang === 'No' ? 'checked' : '' }}>
                                                <label for="arus_pada_tiang_no">{{ __('messages.no') }}</label>
                                            </div>

                                            <div class="col-md-4 d-flex">
                                                <input type="radio" name="tiang_defect_current_leakage" id="arus_pada_tiang_yes"
                                                    class="form-check" value="Yes"
                                                    {{ $data->arus_pada_tiang === 'Yes' ? 'checked' : '' }}>
                                                <label for="arus_pada_tiang_yes">{{ __('messages.yes') }}</label>
                                            </div>

                                            <div class="col-md-4 @if ($data->arus_pada_tiang == 'No' || $data->arus_pada_tiang == '') d-none @endif"
                                                id="arus_pada_tiang_amp_div">
                                                <label for="arus_pada_tiang_amp">{{ __('messages.Amp') }}</label>
                                                <input type="text" name="tiang_defect[current_leakage_val]" id="arus_pada_tiang_amp"
                                                    class="form-control" value="{{ $data->arus_pada_tiang_amp }}"
                                                    required>

                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <div class="row">
                                    <div class="col-md-4"><label
                                            for="">{{ __('messages.Inspection_of_current_leakage_on_the_umbang') }}
                                        </label></div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-4 d-flex">
                                                <input type="radio" name="umbang_defect_current_leakage" id="arus_pada_umbgan_no"
                                                    class="form-check" value="No"
                                                    {{ array_key_exists('current_leakage' , $data->umbang_defect) && $data->umbang_defect['current_leakage'] === false ? 'checked' : '' }}>
                                                <label for="arus_pada_umbgan_no">{{ __('messages.no') }}</label>
                                            </div>

                                            <div class="col-md-4 d-flex">
                                                <input type="radio" name="umbang_defect_current_leakage" id="arus_pada_umbgan_yes"
                                                    class="form-check" value="Yes"
                                                    {{ array_key_exists('current_leakage' , $data->umbang_defect) && $data->umbang_defect['current_leakage'] === true ? 'checked' : '' }}>

                                                <label for="arus_pada_umbgan_yes">{{ __('messages.yes') }}</label>
                                            </div>

                                            <div class="col-md-4 @if(!array_key_exists('current_leakage' , $data->umbang_defect) || $data->umbang_defect['current_leakage'] !== true) d-none @endif"
                                                id="arus_pada_umbgan_amp_div">
                                                <label for="arus_pada_tiang_amp">{{ __('messages.Amp') }}</label>
                                                <input type="text" name="umbang_defect[current_leakage_val]" id="arus_pada_tiang_amp"
                                                    class="form-control" value="{{array_key_exists('current_leakage_val' , $data->umbang_defect) ? $data->umbang_defect['current_leakage_val'] : ''}}"
                                                    required>

                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="five_feet_away">{{ __('messages.five_feet_away') }} </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="five_feet_away" value="{{$data->five_feet_away}}" id="five_feet_away" class="form-control">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="ffa_no_of_houses">{{ __('messages.ffa_no_of_houses') }} </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="ffa_no_of_houses" value="{{$data->ffa_no_of_houses}}" id="ffa_no_of_houses" class="form-control">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 ">
                                        <label for="ffa_house_no">{{ __('messages.ffa_house_no') }} </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="ffa_house_no" value="{{$data->ffa_house_no}}" id="ffa_house_no" class="form-control">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-4"><label for="clean_banner_image">{{ __('messages.clean_banner') }} Image </label></div>
                                    <div class="col-md-8 row">{!!  viewAndUpdateImage($data->clean_banner_image , 'clean_banner_image' , false )  !!}</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4"><label for="remove_creepers_image">{{ __('messages.remove_creepers') }} Image </label></div>
                                    <div class="col-md-8 row">{!!  viewAndUpdateImage($data->remove_creepers_image , 'remove_creepers_image' , false )  !!}</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4"><label for="current_leakage_image">{{ __('messages.current_leakage') }} Image </label></div>
                                    <div class="col-md-8 row">{!!  viewAndUpdateImage($data->current_leakage_image , 'current_leakage_image' , false )  !!}</div>
                                </div>


                            </fieldset>
                            {{-- END Kebocoran Arus (5) --}}






