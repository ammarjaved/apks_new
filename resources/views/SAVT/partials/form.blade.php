

                            <h3>{{ __('messages.info') }}</h3>

                            {{-- START Info (1) --}}
                            <fieldset class=" form-input">

                                {{-- BA --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="ba">{{ __('messages.ba') }}</label></div>
                                    <div class="col-md-4">
                                        <input type="text" name="ba" id="ba" readonly class="form-control" value="{{$data->ba}}">
                                        {{-- <input type="hidden" name="ba"   class="form-control" value="{{$data->ba}}"> --}}

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
                                        <input type="text" name="supplier_pmu_ppu" id="supplier_pmu_ppu" class="form-control" required value="{{$data->supplier_pmu_ppu}}" {{!$disabled ?: "disabled"}}>
                                    </div>
                                </div>

                                {{-- FEEDER NO --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="supplier_feeder_no"> {{ __('messages.Feeder_no') }}  </label></div>
                                    <div class="col-md-4">
                                        <input type="text" name="supplier_feeder_no"  id="supplier_feeder_no" class="form-control" required value="{{ $data->supplier_feeder_no }}" {{!$disabled ?: "disabled"}}>
                                    </div>
                                </div>

                                {{-- FEEDER NO --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="road_name"> {{ __('messages.road_name') }}  </label></div>
                                    <div class="col-md-4">
                                        <input type="text" name="road_name"  id="road_name" class="form-control" required value="{{ $data->road_name }}" {{!$disabled ?: "disabled"}}>
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
                                        <input type="text" name="sec_from" id="sec_from" class="form-control" value="{{ $data->sec_from }}" {{!$disabled ?: "disabled"}}>
                                    </div>
                                </div>

                                {{-- SECTION TO --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="sec_to">{{ __('messages.to') }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="sec_to" id="sec_to" class="form-control"  value="{{ $data->sec_to }}" {{!$disabled ?: "disabled"}}>
                                    </div>
                                </div>

                                {{-- TIANG NO --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="tiang_no">{{ __('messages.Tiang_No') }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="tiang_no" id="tiang_no" class="form-control" required value="{{ $data->tiang_no }}" {{!$disabled ?: "disabled"}}>
                                    </div>
                                </div>

                                {{-- VOLTAN (KV) --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="voltan_kv">{{ __('messages.Voltan') }} (KV)</label>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="voltan_kv" id="voltan_kv" class="form-control" required onchange="getVoltan(this.value)" {{!$disabled ?: "disabled"}}>
                                            <option value="{{ $data->voltan_kv }}" hidden>{{ $data->voltan_kv }}</option>
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
                                        <input type="date" name="visit_date" value="{{ $data->visit_date }}" id="review_date" class="form-control" required {{!$disabled ?: "disabled"}}>
                                    </div>
                                </div>

                                {{-- COORDINATES --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="cordinates">{{ __('messages.coordinate') }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="cordinates" id="cordinates" class="form-control" required readonly value="{{ $data->coords }}" {{!$disabled ?: "disabled"}}>
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






                            {{------------------------------------- IMAGES -------------------------------------------}}

                            <h3>{{__('messages.images')}}</h3>

                            <fieldset class="form-input">

                                {{-- SAVT IMAGE 1 --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="savt_image_1">{{ __('messages.savt') }} Image 1 </label></div>
                                    <div class="col-md-8 row">{!!  viewAndUpdateImage($data->savt_image_1 , 'savt_image_1' , $disabled)   !!}</div>
                                </div>

                                {{-- SAVT IMAGE 2 --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="savt_image_2">{{ __('messages.savt') }} Image 2</label></div>
                                    <div class="col-md-8 row">{!!  viewAndUpdateImage($data->savt_image_2 , 'savt_image_2'  , $disabled )  !!}</div>
                                </div>

                                {{-- SAVT IMAGE 3 --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="savt_image_3">{{ __('messages.savt') }} Image 3</label></div>
                                    <div class="col-md-8 row">{!!  viewAndUpdateImage($data->savt_image_3 , 'savt_image_3' , $disabled )  !!}</div>
                                </div>

                                {{-- SAVT IMAGE 4 --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="savt_image_4">{{ __('messages.savt') }} Image 4</label></div>
                                    <div class="col-md-8 row">{!!  viewAndUpdateImage($data->savt_image_4 , 'savt_image_4' , $disabled )  !!}</div>
                                </div>

                                {{-- SAVT CLEAN BANNER IMAGE --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="savt_clean_banner_image">{{ __('messages.savt_clean_banner') }} Image </label></div>
                                    <div class="col-md-8 row">{!!  viewAndUpdateImage($data->savt_clean_banner_image , 'savt_clean_banner_image' , $disabled )  !!}</div>
                                </div>


                                {{-- SAVT CLEAN BANNER IMAGE --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="savt_remove_creepers_image">{{ __('messages.savt_remove_creepers') }} Image </label></div>
                                    <div class="col-md-8 row">{!!  viewAndUpdateImage($data->savt_remove_creepers_image , 'savt_remove_creepers_image' , $disabled )  !!}</div>
                                </div>


                                {{-- SAVT CLEAN BANNER IMAGE --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="savt_current_leakage_image">{{ __('messages.savt_current_leakage') }} Image </label></div>
                                    <div class="col-md-8 row">{!!  viewAndUpdateImage($data->savt_current_leakage_image , 'savt_current_leakage_image' , $disabled )  !!}</div>
                                </div>

                            </fieldset>

                            {{------------------------------ END IMAGES -------------------------------------}}








                            {{------------------------------- ASSET REGISTER -------------------------------------}}

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
                                                            <input type="radio" name="abc_size_mmp" value="3x70" id="abc_saiz_mmp_3x70" class="  " {{$data->abc_size_mmp == '3x70' ? 'checked' : ''}} {{!$disabled ?: "disabled "}}>
                                                            <label for="abc_saiz_mmp_3x70" class="fw-400">3x70</label>
                                                        </div>
                                                    </div>

                                                    {{-- ABC SAIZ 3X150 --}}

                                                    <div class="d-flex col-md-4">
                                                        <input type="radio" name="abc_size_mmp" value="3x150" id="abc_saiz_mmp_3x150" class=" " {{$data->abc_size_mmp == '3x150' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                        <label for="abc_saiz_mmp_3x150" class="fw-400">3x150</label>
                                                    </div>

                                                    {{-- ABC SAIZ 3X240 --}}

                                                    <div class="  col-md-4 voltan_11">
                                                        <div class="d-flex">
                                                            <input type="radio" name="abc_size_mmp" value="3x240" id="abc_saiz_mmp_3x240" class=" " {{$data->abc_size_mmp == '3x240' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                            <label for="abc_saiz_mmp_3x240" class="fw-400">3x240</label>
                                                        </div>
                                                    </div>
                                                </div>




                                                {{-- ABC PANJNG METER --}}
                                            <div class="row">
                                                <div class="col-md-6"><label for="st7">ABC {{ __('messages.panjang_meter') }} </label></div>
                                                <div class=" col-md-6 ">
                                                    <input type="number" name="abc_panjang_meter" id="abc_panjang_meter" class="form-control" value="{{$data->abc_panjang_meter }}" {{!$disabled ?: "disabled"}}>
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
                                                    <input type="text" name="bare_size_mmp" id="bare_size_mmp" class="form-control" value="{{$data->bare_size_mmp }}" {{!$disabled ?: "disabled"}}>
                                                </div>
                                            </div>

                                                {{-- BARE PANJANG METER --}}

                                            <div class="row">
                                                <div class="col-md-6"><label for="st7">BARE {{ __('messages.panjang_meter') }} </label></div>
                                                <div class=" col-md-6 ">
                                                    <input type="text" name="bare_panjang_meter" id="bare_panjang_meter" class="form-control" value="{{$data->bare_panjang_meter}}" {{!$disabled ?: "disabled"}} >
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
                                                        <input type="radio" name="underground_cabel_size_mmp" value="3x70" id="underground_cabel_size_mmp_3x70" class="  "  {{$data->underground_cabel_size_mmp == '3x70' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                        <label for="underground_cabel_size_mmp_3x70" class="fw-400">3x70</label>
                                                    </div>
                                                </div>

                                                {{-- UNDERGROUND CABLE 3X150 --}}

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="underground_cabel_size_mmp" value="3x150" id="underground_cabel_size_mmp_3x150" class=" "  {{$data->underground_cabel_size_mmp == '3x150' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                    <label for="underground_cabel_size_mmp_3x150" class="fw-400">3x150</label>
                                                </div>

                                                {{-- UNDERGROUND CABLE 3X240 --}}

                                                <div class="  col-md-4 voltan_11">
                                                    <div class="d-flex">
                                                        <input type="radio" name="underground_cabel_size_mmp" value="3x240" id="underground_cabel_size_mmp_3x240" class=" "  {{$data->underground_cabel_size_mmp == '3x240' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                        <label for="underground_cabel_size_mmp_3x240" class="fw-400">3x240</label>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-6"><label for="st7">{{__('messages.underground_cabel')}} {{ __('messages.panjang_meter') }} </label></div>
                                            <div class=" col-md-6">
                                                <input type="number" name="underground_cabel_length_meter" id="underground_cabel_length_meter" class="form-control"  value="{{$data->underground_cabel_length_meter }}" {{!$disabled ?: "disabled"}}>
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
                                                    <input type="radio" name="eqp_no_auto_circuit_recloser" value="Yes" id="eqp_no_auto_circuit_recloser_yes" class="  "  {{$data->eqp_no_auto_circuit_recloser == 'Yes' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                    <label for="eqp_no_auto_circuit_recloser_yes" class="fw-400">Yes</label>
                                                </div>

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_auto_circuit_recloser" value="No" id="eqp_no_auto_circuit_recloser_no" class=" "  {{$data->eqp_no_auto_circuit_recloser == 'No' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                    <label for="eqp_no_auto_circuit_recloser_no" class="fw-400">No</label>
                                                </div>
                                            </div>

                                                {{-- LOAD BREAK SWITCH  --}}
                                            <label for="st7">{{__('messages.eqp_no')}} {{ __('messages.load_break_switch') }} </label>
                                            <div class=" col-md-12 row">

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_load_break_switch" value="Yes" id="eqp_no_load_break_switch_yes" class="  "  {{$data->eqp_no_load_break_switch == 'Yes' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                    <label for="eqp_no_load_break_switch_yes" class="fw-400">Yes</label>
                                                </div>

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_load_break_switch" value="No" id="eqp_no_load_break_switch_no" class=" "  {{$data->eqp_no_load_break_switch == 'No' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                    <label for="eqp_no_load_break_switch_no" class="fw-400">No</label>
                                                </div>
                                            </div>


                                                    {{-- ISOLATOR SWITCH  --}}
                                            <label for="st7">{{__('messages.eqp_no')}} {{ __('messages.isolator_switch') }} </label>
                                            <div class=" col-md-12 row">

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_isolator_switch" value="Yes" id="eqp_no_isolator_switch_yes" class="  "  {{$data->eqp_no_isolator_switch == 'Yes' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                    <label for="eqp_no_isolator_switch_yes" class="fw-400">Yes</label>
                                                </div>

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_isolator_switch" value="No" id="eqp_no_isolator_switch_no" class=" "  {{$data->eqp_no_isolator_switch == 'No' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                    <label for="eqp_no_isolator_switch_no" class="fw-400">No</label>
                                                </div>
                                            </div>


                                                {{-- SET LFI  --}}
                                            <label for="st7">{{__('messages.eqp_no')}} {{ __('messages.set_lfi') }} </label>
                                            <div class=" col-md-12 row">

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_set_lfi" value="Yes" id="eqp_no_set_lfi_yes" class="  "  {{$data->eqp_no_set_lfi == 'Yes' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                    <label for="eqp_no_set_lfi_yes" class="fw-400">Yes</label>
                                                </div>

                                                <div class="d-flex col-md-4">
                                                    <input type="radio" name="eqp_no_set_lfi" value="No" id="eqp_no_set_lfi_no" class=" "  {{$data->eqp_no_set_lfi == 'No' ? 'checked' : ''}} {{!$disabled ?: "disabled"}}>
                                                    <label for="eqp_no_set_lfi_no" class="fw-400">No</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                            </fieldset>

                            {{------------------------------- END ASSET REGISTER -------------------------------------}}









                           {{------------------------------- START Kejanggalan (3) ------------------------------------}}
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
                                           <td><label for="tiang_rust"> {{ __('messages.rust') }}</label></td>
                                           <td class="d-flex"> {!!  savtYesOrNo('tiang_rust', $disabled ,$data->tiang_rust ) !!}</td>
                                        </tr>

                                        <tr>
                                           <td><label for="tiang_leaning"> {{ __('messages.leaning') }}</label></td>
                                           <td  class="d-flex">{!!  savtYesOrNo('tiang_leaning', $disabled ,$data->tiang_leaning) !!}</td>
                                        </tr>


                                       {{-- CONDUCTOR PHASE --}}

                                        <tr>
                                           <th rowspan="4">{{ __('messages.phase_conductor') }}</th>
                                           <td><label for="conductor_sagging"> {{ __('messages.sagging') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('conductor_sagging', $disabled ,$data->conductor_sagging) !!}</td>
                                        </tr>

                                        <tr>
                                            <td><label for="conductor_torn"> {{ __('messages.torn') }}</label></td>
                                            <td  class="d-flex">{!!  savtYesOrNo('conductor_torn', $disabled ,$data->conductor_torn) !!}</td>
                                        </tr>

                                       <tr>
                                           <td><label for="conductor_broken">{{ __('messages.broken') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('conductor_broken', $disabled ,$data->conductor_broken) !!}</td>
                                       </tr>

                                       <tr>
                                           <td><label for="conductor_hotspot"> {{ __('messages.hotspot') }} </label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('conductor_hotspot', $disabled ,$data->conductor_hotspot) !!}</td>
                                       </tr>



                                       {{-- Umbang --}}

                                       <tr>
                                           <th rowspan="2">{{ __('messages.Umbang') }}</th>
                                           <td><label for="umbang_sagging">{{ __('messages.Sagging_Breaking') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('umbang_sagging', $disabled ,$data->umbang_sagging) !!}</td>

                                       </tr>

                                       <tr>
                                           <td><label for="umbang_disconnect">{{ __('messages.disconnect') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('umbang_disconnect', $disabled ,$data->umbang_disconnect) !!}</td>
                                       </tr>




                                       {{-- CABLE TERMINATE --}}
                                       <tr>
                                           <th rowspan="2">{{ __('messages.cable_terminate') }}</th>
                                           <td><label for="cabel_terminate_crossing">{{ __('messages.crossing') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('cabel_terminate_crossing', $disabled ,$data->cabel_terminate_crossing) !!}</td>
                                       </tr>
                                       <tr>
                                           <td><label for="cabel_terminate_hotspot">{{ __('messages.hotspot') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('cabel_terminate_hotspot', $disabled ,$data->cabel_terminate_hotspot) !!}</td>

                                       </tr>

                                       {{-- LIGHTNING ARRESTER OCP --}}

                                       <tr>
                                           <th rowspan="3">{{ __('messages.lightning_arrester_ocp') }}</th>
                                           <td><label for="lightning_arrester_ocp_broken">{{ __('messages.broken') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('lightning_arrester_ocp_broken', $disabled ,$data->lightning_arrester_ocp_broken) !!}</td>
                                       </tr>

                                       <tr>
                                           <td><label for="lightning_arrester_ocp_disconnected">{{ __('messages.disconnect') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('lightning_arrester_ocp_disconnected', $disabled ,$data->lightning_arrester_ocp_disconnected) !!}</td>
                                       </tr>

                                       <tr>
                                           <td><label for="lightning_arrester_ocp_hot_spot">{{ __('messages.hotspot') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('lightning_arrester_ocp_hot_spot', $disabled ,$data->lightning_arrester_ocp_hot_spot) !!}</td>
                                       </tr>

                                       {{-- LIGHTNING ARRESTER PAC --}}
                                       <tr>
                                           <th rowspan="3">{{ __('messages.lightning_arrester_pac') }}</th>
                                           <td><label for="lightning_arrester_pac_broken">{{ __('messages.broken') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('lightning_arrester_pac_broken', $disabled ,$data->lightning_arrester_pac_broken) !!}</td>

                                       </tr>
                                       <tr>
                                           <td><label for="lightning_arrester_pac_disconnected">{{ __('messages.disconnect') }} </label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('lightning_arrester_pac_disconnected', $disabled ,$data->lightning_arrester_pac_disconnected) !!}</td>

                                       </tr>
                                       <tr>
                                           <td><label for="lightning_arrester_pac_hot_spot">{{ __('messages.hotspot') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('lightning_arrester_pac_hot_spot', $disabled ,$data->lightning_arrester_pac_hot_spot) !!}</td>
                                       </tr>

                                       {{-- NEED RENTIS --}}
                                       <tr>
                                           <th>{{ __('messages.rentis') }}</th>
                                           <td><label for="need_rentis">{{ __('messages.need_rentis') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('need_rentis', $disabled ,$data->need_rentis) !!}</td>
                                       </tr>

                                       {{-- SWITCH LINK --}}
                                       <tr>
                                           <th>{{ __('messages.switch_link') }}</th>
                                           <td><label for="switch_link_need_repair">{{ __('messages.need_repair') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('switch_link_need_repair', $disabled ,$data->switch_link_need_repair) !!}</td>
                                       </tr>

                                       {{-- POLE NEED REPAINT --}}
                                       <tr>
                                           <th>{{ __('messages.tiang') }}</th>
                                           <td><label for="tiang_needs_repaint">{{ __('messages.needs_repaint') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('tiang_needs_repaint', $disabled ,$data->tiang_needs_repaint) !!}</td>
                                       </tr>

                                       {{-- UMBANG INSULATION STATUS --}}
                                       <tr>
                                           <th>{{ __('messages.umbang') }}</th>
                                           <td><label for="insulation_status">{{ __('messages.insulation_status') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('umbang_insulation_status', $disabled ,$data->umbang_insulation_status) !!}</td>
                                       </tr>

                                       {{-- EARTH BOUNDING --}}
                                       <tr>
                                           <th rowspan="3">{{ __('messages.earth_bounding') }}</th>
                                           <td><label for="earth_bounding_status">{{ __('messages.status') }}</label> </td>
                                           <td class="d-flex">{!!  savtYesOrNo('earth_bounding_status', $disabled ,$data->earth_bounding_status) !!}</td>
                                       </tr>

                                       <tr>
                                           <td><label for="earth_bounding_hotspt">{{ __('messages.hotspot') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('earth_bounding_hotspt', $disabled ,$data->earth_bounding_hotspt) !!}</td>
                                       </tr>

                                       <tr>
                                           <td><label for="earthbounding_ultrasound_status">{{ __('messages.ultrasound_status') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('earthbounding_ultrasound_status', $disabled ,$data->earthbounding_ultrasound_status) !!}</td>
                                       </tr>

                                       {{-- CABLE TRAY --}}

                                       <tr>
                                           <th>{{ __('messages.cable_tray') }}</th>
                                           <td><label for="cabel_tray_change">{{ __('messages.change') }}</label></td>
                                           <td  class="d-flex">{!!  savtYesOrNo('cabel_tray_change', $disabled ,$data->cabel_tray_change) !!}</td>
                                       </tr>

                                       {{-- SUSPENSION --}}

                                       <tr>
                                           <th>{{ __('messages.suspension') }}</th>
                                           <td><label for="suspension_clamp_change">{{ __('messages.clamp_change') }}</label></td>
                                           <td  class="d-flex">{!!  savtYesOrNo('suspension_clamp_change', $disabled ,$data->suspension_clamp_change) !!}</td>
                                       </tr>

                                       {{-- TRIANGULAR --}}

                                       <tr>
                                           <th>{{ __('messages.triangular') }}</th>
                                           <td><label for="triangular_braker_change">{{ __('messages.braker_change') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('triangular_braker_change', $disabled ,$data->triangular_braker_change) !!}</td>
                                       </tr>

                                       {{-- CROSSARM --}}

                                       <tr>
                                           <th rowspan="2">{{ __('messages.crossarm') }}</th>
                                           <td><label for="crossarm_rust">{{ __('messages.rust') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('crossarm_rust', $disabled ,$data->crossarm_rust) !!}</td>
                                       </tr>

                                       <tr>
                                           <td><label for="crossarm_bent">{{ __('messages.bent') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('crossarm_bent', $disabled ,$data->crossarm_bent) !!}</td>
                                       </tr>

                                       {{-- EARTH CROSSARM --}}

                                       <tr>
                                           <th rowspan="2">{{ __('messages.earth_crossarm') }}</th>
                                           <td><label for="earth_crossarm_rust">{{ __('messages.rust') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('earth_crossarm_rust', $disabled ,$data->earth_crossarm_rust) !!}</td>
                                       </tr>

                                       <tr>
                                           <td><label for="earth_crossarm_bent">{{ __('messages.bent') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('earth_crossarm_bent', $disabled ,$data->earth_crossarm_bent) !!}</td>
                                       </tr>

                                       {{-- CONDUCTOR OF THE EARTH --}}

                                       <tr>
                                           <th rowspan="3">{{ __('messages.conductor_of_the_earth') }}</th>
                                           <td><label for="ce_sagging">{{ __('messages.sagging') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('ce_sagging', $disabled ,$data->ce_sagging) !!}</td>
                                       </tr>

                                       <tr>
                                           <td><label for="ce_btc">{{ __('messages.bumi_tangga_connection') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('ce_btc', $disabled ,$data->ce_btc) !!}</td>
                                       </tr>

                                       <tr>
                                           <td><label for="ce_broken">{{ __('messages.broken') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('ce_broken', $disabled ,$data->ce_broken) !!}</td>
                                       </tr>

                                       {{-- WIRE TO EARTH --}}

                                       <tr>
                                           <th>{{ __('messages.wire_to_earth') }}</th>
                                           <td><label for="wte_hanging_disconnected">{{ __('messages.hanging_disconnected') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('wte_hanging_disconnected', $disabled ,$data->wte_hanging_disconnected) !!}</td>
                                       </tr>

                                       {{-- INSULATION --}}

                                       <tr>
                                           <th rowspan="4">{{ __('messages.insulation') }}</th>
                                           <td><label for="insulation_flashover">{{ __('messages.flashover') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('insulation_flashover', $disabled ,$data->insulation_flashover) !!}</td>
                                       </tr>

                                        <tr>
                                            <td><label for="insulation_full">{{ __('messages.full') }}</label></td>
                                            <td class="d-flex">{!!  savtYesOrNo('insulation_full', $disabled ,$data->insulation_full) !!}</td>
                                        </tr>

                                        <tr>
                                           <td><label for="insulation_broken">{{ __('messages.broken') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('insulation_broken', $disabled ,$data->insulation_broken) !!}</td>
                                        </tr>

                                        <tr>
                                            <td><label for="insulation_hotspot">{{ __('messages.hotspot') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('insulation_hotspot', $disabled ,$data->insulation_hotspot) !!}</td>
                                        </tr>




                                       {{-- LIGHTNING CATCHER ON LINE --}}

                                       <tr>
                                           <th rowspan="2">{{ __('messages.lightning_catcher_on_line') }}</th>
                                           <td><label for="lcol_ripped_off">{{ __('messages.ripped_off') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('lcol_ripped_off', $disabled ,$data->lcol_ripped_off) !!}</td>
                                       </tr>

                                       <tr>
                                           <td><label for="lcol_hotspot">{{ __('messages.hotspot') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('lcol_hotspot', $disabled ,$data->lcol_hotspot) !!}</td>
                                       </tr>

                                       {{-- JUMPER --}}

                                       <tr>
                                           <th rowspan="2">{{ __('messages.jumper') }}</th>
                                           <td><label for="jumper_need_repair">{{ __('messages.need_repair') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('jumper_need_repair', $disabled ,$data->jumper_need_repair) !!}</td>
                                       </tr>

                                       <tr>
                                           <td><label for="jumper_hotspot">{{ __('messages.hotspot') }}</label></td>
                                           <td class="d-flex">{!!  savtYesOrNo('jumper_hotspot', $disabled ,$data->jumper_hotspot) !!}</td>
                                       </tr>

                                       {{-- PG CLAMPS / CONNECTORS --}}

                                       <tr>
                                            <th rowspan="2">{{ __('messages.pg_clamps_connectors') }}</th>
                                            <td><label for="pg_cc_need_change">{{ __('messages.need_change') }}</label></td>
                                            <td class="d-flex">{!!  savtYesOrNo('pg_cc_need_change', $disabled ,$data->pg_cc_need_change) !!}</td>
                                        </tr>

                                        <tr>
                                            <td><label for="pg_cc_hotspot">{{ __('messages.hotspot') }}</label></td>
                                            <td class="d-flex">{!!  savtYesOrNo('pg_cc_hotspot', $disabled ,$data->pg_cc_hotspot) !!}</td>
                                        </tr>

                                        {{-- Climbing Barriers --}}

                                        <tr>
                                            <th>{{ __('messages.climbing_barriers') }}</th>
                                            <td><label for="climbing_barrier_need_change">{{ __('messages.need_change') }}</label>
                                            </td>
                                            <td class="d-flex">{!!  savtYesOrNo('climbing_barrier_need_change', $disabled ,$data->climbing_barrier_need_change) !!}</td>
                                        </tr>

                                        {{-- Arcing Horn --}}

                                        <tr>
                                            <th>{{ __('messages.arcing_horn') }}</th>
                                            <td><label for="arcing_horn_need_repair">{{ __('messages.need_repair') }}</label></td>
                                            <td class="d-flex">{!!  savtYesOrNo('arcing_horn_need_repair', $disabled ,$data->arcing_horn_need_repair) !!}</td>
                                        </tr>

                                        {{-- LFI --}}

                                        <tr>
                                            <th>{{ __('messages.lfi') }}</th>
                                            <td><label for="lfi_break">{{ __('messages.break_date') }}</label></td>
                                            <td class="d-flex">{!!  savtYesOrNo('lfi_break', $disabled ,$data->lfi_break) !!}</td>
                                        </tr>

                                   </table>
                               </div>
                               <input type="hidden" name="total_defects" id="total_defects">
                           </fieldset>


                           {{------------------------------- END Kejanggalan (3) ------------------------------------}}







                            {{------------------------------- START Kebocoran Arus (5) --------------------------------}}

                            <h3>{{ __('messages.Kebocoran_Arus') }}</h3>



                            <fieldset class="form-input">
                                <h3>{{ __('messages.Kebocoran_Arus') }}</h3>

                                    {{-- REMARKS --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="remarks">{{__('messages.remarks')}}</label></div>
                                    <div class="col-md-4 mb-4">
                                        <textarea name="remarks" id="remarks" cols="30" rows="6" class="form-control" {{!$disabled ?: "disabled"}}>{{$data->remarks}}</textarea>
                                    </div>
                                </div>

                                    {{-- FIVE FEET AWAY --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="five_feet_away">{{ __('messages.five_feet_away') }} </label></div>
                                    <div class="col-md-4">
                                        <input type="text" name="five_feet_away" id="five_feet_away" class="form-control" value="{{$data->five_feet_away}}" {{!$disabled ?: "disabled"}}>
                                    </div>
                                </div>

                                    {{-- FFA NO OF HOUSES --}}
                                <div class="row">
                                    <div class="col-md-4"><label for="ffa_no_of_houses">{{ __('messages.ffa_no_of_houses') }} </label></div>
                                    <div class="col-md-4">
                                        <input type="text" name="ffa_no_of_houses" id="ffa_no_of_houses" class="form-control" value="{{$data->ffa_no_of_houses}}" {{!$disabled ?: "disabled"}}>
                                    </div>
                                </div>

                                    {{-- FFA HOUSE NO --}}
                                <div class="row">
                                    <div class="col-md-4 "><label for="ffa_house_no">{{ __('messages.ffa_house_no') }} </label></div>
                                    <div class="col-md-4">
                                        <input type="text" name="ffa_house_no" id="ffa_house_no" class="form-control" value="{{$data->ffa_house_no}}" {{!$disabled ?: "disabled"}}>
                                    </div>
                                </div>


                                @if ($disabled)


                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="zone">QA Status</label>
                                    </div>
                                    <div class="col-md-4" style="height: 200px">



                                        @if ($data->visit_date != '' && $data->savt_image_1 != '')


                                            <button type="button" class="btn  text-left form-control {{$data->qa_status == 'Accept' ? 'btn-success' :($data->qa_status == 'Reject' ? 'btn-danger' :'btn-primary') }} "
                                                data-toggle="dropdown">
                                                {{ $data->qa_status }}

                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if ($data->qa_status != 'Accept')
                                                    <a href="/{{ app()->getLocale() }}/savt?status=Accept&&id={{ $data->id }}"
                                                        onclick="return confirm('are you sure?')">
                                                        <button type="button"
                                                            class="dropdown-item pl-3 w-100 text-left">Accept</button>
                                                    </a>
                                                @endif

                                                @if ($data->qa_status != 'Reject')
                                                    <button type="button" class="btn btn-primary dropdown-item" data-id="{{$data->id }}"
                                                        data-toggle="modal" data-target="#rejectReasonModal">
                                                        Reject
                                                    </button>
                                                @endif

                                            </div>

                                        @else

                                            <button type="button" class="btn  text-left form-control" style="background: orange ; color:white">
                                                <strong >Unsurveyed</strong>
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
                                            <textarea name="" id="" cols="10" rows="4" disabled class="form-control">{{$data->reject_remarks}}</textarea>
                                        </div>
                                    </div>
                                @endif

                                @endif
                            </fieldset>


                            {{--------------------------------- END Kebocoran Arus (5) ------------------------------}}
