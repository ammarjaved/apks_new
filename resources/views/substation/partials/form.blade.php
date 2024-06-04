

    {{-- ZONE --}}
    <div class="row">
        <div class="col-md-4"><label for="zone">{{ __('messages.zone') }}</label></div>
        <div class="col-md-4">
            <select name="zone" id="search_zone" class="form-control" required {{$disabled ? 'disabled' : ''}}>
                <option value="{{ $data->zone }}" hidden>{{ $data->zone }}</option>
                @if (Auth::user()->ba == '')
                    <option value="W1">W1</option>
                    <option value="B1">B1</option>
                    <option value="B2">B2</option>
                    <option value="B4">B4</option>
                @endif
            </select>
        </div>
    </div>

    {{-- BA --}}
    <div class="row">
        <div class="col-md-4"><label for="ba">{{ __('messages.ba') }}</label></div>
        <div class="col-md-4">
            <select name="ba" id="ba" class="form-control" required {{$disabled ? 'disabled' : ''}} onchange="getWp(this)">
                <option value="{{ $data->ba }}" hidden>{{ $data->ba }}</option>
            </select>
        </div>
    </div>

    {{-- FL SUBSTATION --}}
    <div class="row">
        <div class="col-md-4"><label for="fl">{{ __('messages.fl_substation') }}</label></div>
        <div class="col-md-4">
            <input type="text" name="fl" id="fl" {{$disabled ? 'disabled' : ''}}
                value="{{ $data->fl }}" class="form-control"
                required>
        </div>
    </div>

    {{-- NAME --}}
    <div class="row">
        <div class="col-md-4"><label for="name">{{ __('messages.substation_feeder_pillar_name') }}</label></div>
        <div class="col-md-4">
            <input type="text" name="name" id="name" class="form-control" required value="{{ $data->name }}" {{$disabled ? 'disabled' : ''}}>
        </div>
    </div>

    {{-- TYPE --}}
    <div class="row">
        <div class="col-md-4"><label for="type">{{ __('messages.type') }}</label></div>
        <div class="col-md-4">
            <select name="type" id="type" class="form-control" {{$disabled ? 'disabled' : ''}}>
                <option value="{{$data->type}}" hidden>{{$data->type == '' ? 'select' : $data->type}}</option>
                <option value="Indoor">Indoor</option>
                <option value="Attach Building">Attach Building</option>
                <option value="Outdoor">Outdoor</option>
                <option value="Padat">Padat</option>
                <option value="Pencawang Atas Tiang (PAT)">Pencawang Atas Tiang (PAT)</option>
            </select>
        </div>
    </div>

    {{-- VOLTAGE --}}
    <div class="row">
        <div class="col-md-4"><label for="area">{{ __('messages.voltage') }}</label></div>
        <div class="col-md-4">
            <select name="voltage" id="voltage" class="form-control" {{$disabled ? 'disabled' : ''}}>
                <option value="{{$data->voltage}}" hidden>{{$data->voltage == '' ? 'select' : $data->voltage}}</option>
                <option value="11kw">11kv</option>
                <option value="13kw">13kv</option>
            </select>
        </div>
    </div>


    {{-- SURVEY DATE --}}
    <div class="row">
        <div class="col-md-4"><label for="visit_date">{{ __('messages.survey_date') }}</label></div>
        <div class="col-md-4">
            <input type="date" name="visit_date" id="visit_date" class="form-control" {{$disabled ? 'disabled' : ''}}
                value="{{ date('Y-m-d', strtotime($data->visit_date)) }}" required>
        </div>
    </div>

    {{-- PATROL TIME --}}
    <div class="row">
        <div class="col-md-4"><label for="patrol_time">{{ __('messages.patrol_time') }}</label>
        </div>
        <div class="col-md-4">
            <input type="time" name="patrol_time" id="patrol_time" class="form-control" {{$disabled ? 'disabled' : ''}}
                value="{{ $data->patrol_time =='' ? now('Asia/Kuala_Lumpur')->format('H:i') : date('H:i:s', strtotime($data->patrol_time)) }}" required>
        </div>
    </div>

    {{-- COORDINATES --}}
    @if ($disabled)

        <div class="row">
            <div class="col-md-4"><label for="coordinate">{{ __('messages.coordinate') }}</label> </div>
            <div class="col-md-4">
                <input type="text" name="coordinate" id="coordinate" value="{{ $data->coordinate }}" class="form-control" required readonly disabled>
            </div>
        </div>

    @endif

    {{-- GATE --}}
    <div class="row">
        <div class="col-md-4"><label for="pipe_staus">{{ __('messages.gate') }}</label></div>
        <div class="col-md-4">

            {{-- GATE UNLOCKED --}}
            <div class=" d-flex">
                <input type="checkbox" name="gate_status[locked]" {{$disabled ? 'disabled' : ''}}
                    {{ substaionCheckBox('unlocked', $data->gate_status) }}
                    id="gate_status_unlocked" value="unlocked" >
                <label for="gate_status_unlocked">{{ __('messages.unlocked') }}</label>
            </div>

            {{-- GATE DAMAGED --}}
            <div class=" d-flex">
                <input type="checkbox" name="gate_status[demaged]" {{$disabled ? 'disabled' : ''}}
                    {{ substaionCheckBox('demaged', $data->gate_status) }}
                    id="gate_status_demaged">
                <label for="gate_status_demaged">{{ __('messages.demaged') }}</label>
            </div>

            {{-- GATE OTHER DEFECTS --}}
            <div class="d-flex">
                <input type="checkbox" name="gate_status[other]" {{$disabled ? 'disabled' : ''}}
                    {{ substaionCheckBox('other', $data->gate_status) }} id="gate_status_others"
                    onclick="getStatus(this)">
                <label for="gate_status_others">{{ __('messages.others') }}</label>


            </div>
            {{-- GATE OTHER DEFECT INPUT --}}
            <input type="text" name="gate_status[other_value]" id="gate_status_other" {{$disabled ? 'disabled' : ''}}
                class="form-control  @if (substaionCheckBox('other', $data->gate_status) !== 'checked') d-none @endif"
                value="@if (substaionCheckBox('other', $data->gate_status) == 'checked') {{ $data->gate_status->other_value }} @endif"
                placeholder="please enter other gate defect">

        </div>
    </div>

    {{-- LONG GRASS --}}
    <div class="row">
        <div class="col-md-4"><label for="grass_status">{{ __('messages.long_grass') }}</label></div>
        <div class="col-md-4">
            <select name="grass_status" id="grass_status" class="form-control" required {{$disabled ? 'disabled' : ''}}>
                <option value="{{ $data->grass_status }}" hidden>{{ $data->grass_status }}</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>

    {{-- TREE BRANCHES IN P.E --}}
    <div class="row">
        <div class="col-md-4"><label for="tree_branches_status">{{ __('messages.tree_branches_in_PE') }}</label></div>
        <div class="col-md-4">
            <select name="tree_branches_status" id="tree_branches_status" class="form-control" {{$disabled ? 'disabled' : ''}} required>
                <option value="{{ $data->tree_branches_status }}" hidden>{{ $data->tree_branches_status }}</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>

    {{-- BUILDING DEFECTS --}}
    <div class="row">
        <div class="col-md-4"><label for="building_status">{{ __('messages.building_defects') }}</label></div>

        <div class="col-md-4">

            {{-- BUILDING BROKEN ROOF --}}
            <div class="d-flex">
                <input type="checkbox" name="building_status[broken_roof]" {{$disabled ? 'disabled' : ''}}
                    {{ substaionCheckBox('broken_roof', $data->building_status) }} id="building_status_broken_roof">
                <label for="building_status_broken_roof">{{ __('messages.broken_roof') }}</label>
            </div>

            {{-- BUILDING BROKEN GUTTER --}}
            <div class="d-flex">
                <input type="checkbox" name="building_status[broken_gutter]" {{$disabled ? 'disabled' : ''}}
                    {{ substaionCheckBox('broken_gutter', $data->building_status) }} id="building_status_broken_gutter">
                <label for="building_status_broken_gutter">{{ __('messages.broken_gutter') }}</label>
            </div>

            {{-- BUILDING BROKEN BASE --}}
            <div class="d-flex">
                <input type="checkbox" name="building_status[broken_base]" {{$disabled ? 'disabled' : ''}}
                    {{ substaionCheckBox('broken_base', $data->building_status) }} id="building_status_broken_base">
                <label for="building_status_broken_base">{{ __('messages.broken_base') }}</label>
            </div>

            {{-- BUILDING OTHER --}}
            <div class="d-flex">
                <input type="checkbox" name="building_status[other]" {{$disabled ? 'disabled' : ''}}
                    {{ substaionCheckBox('other', $data->building_status) }} id="building_status_other" onclick="bulidingStatus(this)">
                <label for="building_status_other">{{ __('messages.others') }}</label>
            </div>

            {{-- BUILDING OTHER DEFECT INPUT --}}
            <input type="text" name="building_status[other_value]" id="other_building_defects" {{$disabled ? 'disabled' : ''}}
                placeholder="please enter other buliding defects"
                class="form-control @if (substaionCheckBox('other', $data->building_status) !== 'checked') d-none @endif"
                value="@if (substaionCheckBox('other', $data->building_status) == 'checked') {{ $data->building_status->other_value }} @endif">

        </div>

    </div>

    {{-- CLEANING ILLEGAL ADS BANNERS --}}
    <div class="row">
        <div class="col-md-4"><label for="collapsed_status">{{ __('messages.cleaning_illegal_ads_banners') }}</label></div>
        <div class="col-md-4">
            <select name="advertise_poster_status" id="advertise_poster_status" {{$disabled ? 'disabled' : ''}} class="form-control" required>
                <option value="{{ $data->advertise_poster_status }}" hidden>{{ $data->advertise_poster_status }}</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>

    {{-- SUBSTATION IMAGE 1 & 2 --}}
    <div class="row">
        <div class="col-md-4"><label for="image_pipe">{{ __('messages.substation') }} {{__('messages.images')}}</label></div>
        <div class="col-md-8 row">
            {!!  viewAndUpdateImage($data->substation_image_1 , 'substation_image_1' , $disabled )  !!}
            {!!  viewAndUpdateImage($data->substation_image_2 , 'substation_image_2' , $disabled )  !!}
        </div>
    </div>


    {{-- GATE IMAGE 1 & 2 --}}
    <div class="row">
        <div class="col-md-4"><label for="image_pipe">{{ __('messages.image_gate') }}</label></div>
        <div class="col-md-8 row">
            {!!  viewAndUpdateImage($data->image_gate , 'image_gate' , $disabled )  !!}
            {!!  viewAndUpdateImage($data->image_gate_2 , 'image_gate_2' , $disabled )  !!}
        </div>
    </div>

    {{-- AFTER GATE LOCK IMAGE  1 & 2 --}}
    <div class="row">
        <div class="col-md-4"><label for="images_gate_after_lock">{{ __('messages.images_gate_after_lock') }}</label></div>
        <div class="col-md-8 row">
            {!!  viewAndUpdateImage($data->images_gate_after_lock , 'images_gate_after_lock' , $disabled )  !!}
            {!!  viewAndUpdateImage($data->images_gate_after_lock_2 , 'images_gate_after_lock_2' , $disabled )  !!}
        </div>
    </div>

    {{-- GRASS IMAGE 1 & 2 --}}
    <div class="row">
        <div class="col-md-4"><label for="image_grass">{{ __('messages.image_grass') }}</label></div>
        <div class="col-md-8 row">
            {!!  viewAndUpdateImage($data->image_grass , 'image_grass' , $disabled )  !!}
            {!!  viewAndUpdateImage($data->image_grass_2 , 'image_grass_2' , $disabled )  !!}
        </div>
    </div>

    {{-- TREE BRANCHES IN P.E IMAGE 1 & 2 --}}
    <div class="row">
        <div class="col-md-4"><label for="image_tree_branches">{{ __('messages.image_tree_branches') }}</label></div>
        <div class="col-md-8 row">
            {!!  viewAndUpdateImage($data->image_tree_branches , 'image_tree_branches' , $disabled )  !!}
            {!!  viewAndUpdateImage($data->image_tree_branches_2 , 'image_tree_branches_2' , $disabled )  !!}
        </div>
    </div>

    {{-- BUILDING IMAGE 1 & 2 --}}
    <div class="row">
        <div class="col-md-4"><label for="image_building">{{ __('messages.image_building') }}</label></div>
        <div class="col-md-8 row">
            {!!  viewAndUpdateImage($data->image_building , 'image_building' , $disabled )  !!}
            {!!  viewAndUpdateImage($data->image_building_2 , 'image_building_2' , $disabled )  !!}
        </div>
    </div>

    {{-- ADVERSITEMENT IMAGE 1 & 2 --}}
    <div class="row">
        <div class="col-md-4"><label for="image_building">{{ __('messages.image_advertise_poster') }}</label></div>
        <div class="col-md-8 row">
            {!!  viewAndUpdateImage($data->image_advertisement_before_1 , 'image_advertisement_before_1' , $disabled )  !!}
            {!!  viewAndUpdateImage($data->image_advertisement_before_2 , 'image_advertisement_before_2' , $disabled )  !!}
        </div>
    </div>

    {{-- AFTER ADVERTISEMENT POSTER REMOVAL IMAGE 1 & 2 --}}
    <div class="row">
        <div class="col-md-4"><label for="image_building">{{ __('messages.image_advertise_poster_removal') }}</label></div>
        <div class="col-md-8 row">
            {!!  viewAndUpdateImage($data->image_advertisement_after_1 , 'image_advertisement_after_1' , $disabled )  !!}
            {!!  viewAndUpdateImage($data->image_advertisement_after_2 , 'image_advertisement_after_2' , $disabled )  !!}
        </div>
    </div>

    {{-- OTHER IMAGE --}}
    <div class="row">
        <div class="col-md-4"><label for="other_image">{{ __('messages.other_image') }}</label></div>
        @if (!$disabled)
          <div class="col-md-4 form-input pr-3">
            <input type="file" accept="image/*" name="other_image" id="other_image" class="form-control">
          </div>
        @endif

        <div class="col-md-4 text-center mb-3">
            @if ( $data->other_image != '')
                <a href="{{ config('globals.APP_IMAGES_URL').$data->other_image }}" data-lightbox="roadtrip">
                    <img src="{{ config('globals.APP_IMAGES_URL').$data->other_image }}" alt="" height="70" class="adjust-height ml-5  ">
                </a>
            @else
                <strong>{{ __('messages.no_image_found') }}</strong>
            @endif
        </div>
    </div>

