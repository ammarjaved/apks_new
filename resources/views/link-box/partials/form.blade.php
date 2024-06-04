<div class="row">
    <div class="col-md-4"><label for="zone">{{__('messages.zone')}}</label></div>
    <div class="col-md-4">
        <select {{$disabled ? 'disabled' : '' }}  name="zone" id="search_zone" class="form-control" required>

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

<div class="row">
    <div class="col-md-4"><label for="ba">{{__('messages.ba')}}</label></div>
    <div class="col-md-4">
        <select {{$disabled ? 'disabled' : '' }}  name="ba" id="ba" class="form-control" required
            onchange="getWp(this)">
            <option value="{{ $data->ba }}" hidden>{{ $data->ba }}</option>

        </select>
    </div>
</div>


<div class="row">
    <div class="col-md-4"><label for="end_date">{{__('messages.to')}}</label></div>
    <div class="col-md-4">
        <input {{$disabled ? 'disabled' : '' }}  type="text" name="end_date" id="end_date" value="{{ $data->end_date }}"
            class="form-control">
    </div>
</div>

<div class="row">
    <div class="col-md-4"><label for="start_date">{{__('messages.from')}}</label></div>
    <div class="col-md-4">
        <input {{$disabled ? 'disabled' : '' }}  type="text" name="start_date" id="start_date" value="{{ $data->start_date }}"
            class="form-control">
    </div>
</div>


<div class="row">
    <div class="col-md-4"><label for="type">{{ __('messages.type') }}</label></div>
    <div class="col-md-4">
        <select name="type" id="type" class="form-control" {{$disabled ? 'disabled' : ''}}>
            <option value="{{$data->type}}" hidden>{{$data->type == '' ? 'select' : $data->type}}</option>
            <option value="Indoor">Select Option</option>
            <option value="11KV">11KV</option>
            <option value="33KV">33KV</option>
            {{-- <option value="Outdoor">Outdoor</option>
            <option value="Padat">Padat</option>
            <option value="Pencawang Atas Tiang (PAT)">Pencawang Atas Tiang (PAT)</option> --}}
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-4"><label for="visit_date">{{__('messages.survey_date')}}</label></div>
    <div class="col-md-4">
        <input {{$disabled ? 'disabled' : '' }}  type="date" name="visit_date" id="visit_date" class="form-control"
            value="{{ date('Y-m-d', strtotime($data->visit_date)) }}" required>
    </div>
</div>




<div class="row">
    <div class="col-md-4"><label for="patrol_time">{{__('messages.patrol_time')}}</label></div>
    <div class="col-md-4">
        <input {{$disabled ? 'disabled' : '' }}  type="time" name="patrol_time" id="patrol_time" class="form-control"
            value="{{ date('H:i:s', strtotime($data->patrol_time)) }}" required>
    </div>
</div>








@if ($disabled)


<div class="row">
    <div class="col-md-4"><label for="coordinate">{{__("messages.coordinate")}}</label></div>
    <div class="col-md-4">
        <input {{$disabled ? 'disabled' : '' }}  type="text" name="coordinate" id="coordinate"
            value="{{ $data->coordinate }}" class="form-control" required readonly>
    </div>
</div>
@endif
<div class="row">
    <div class="col-md-4"><label for="cover_status">{{__("messages.cover_is_not_closed")}}</label></div>
    <div class="col-md-4">
        <select {{$disabled ? 'disabled' : '' }}  name="cover_status" id="cover_status" class="form-control" required>
            <option value="{{ $data->cover_status }}" hidden>{{ $data->cover_status }}
            </option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-4"><label for="vandalism_status">{{__("messages.vandalism")}}</label></div>
    <div class="col-md-4">
        <select {{$disabled ? 'disabled' : '' }}  name="vandalism_status" id="vandalism_status" class="form-control" required>
            <option value="{{ $data->vandalism_status }}" hidden>
                {{ $data->vandalism_status }}</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-4"><label for="leaning_status">{{__("messages.leaning")}}</label></div>
    <div class="col-md-4">
        <select {{$disabled ? 'disabled' : '' }}  name="leaning_status" id="leaning_status" class="form-control" required
            onchange="leaningStatus(this)">
            <option value="{{ $data->leaning_status }}" hidden>{{ $data->leaning_status }}
            </option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>

    </div>
</div>

<div class="row @if ($data->leaning_status == 'No') d-none @endif " id="leaning-angle">
    <div class="col-md-4"><label for="leaning_angle">{{__('messages.leaning_angle')}}</label></div>
    <div class="col-md-4">

        <input {{$disabled ? 'disabled' : '' }}  type="text" name="leaning_angle" id="leaning_angle"
            value="{{ $data->leaning_angle }}" class="form-control">

    </div>
</div>

<div class="row">
    <div class="col-md-4"><label for="rust_status">{{__('messages.rusty')}}</label></div>
    <div class="col-md-4">
        <select {{$disabled ? 'disabled' : '' }}  name="rust_status" id="rust_status" class="form-control" required>
            <option value="{{ $data->rust_status }}" hidden>{{ $data->rust_status }}</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>

    </div>
</div>

<div class="row">
    <div class="col-md-4"><label for="paint_status">{{ __('messages.paint_faded') }}</label></div>
    <div class="col-md-4">
        <select name="paint_status" {{$disabled ? 'disabled' : '' }}  id="paint_status" class="form-control" required>
            <option value="{{ $data->paint_status }}" hidden>{{ $data->paint_status != ''? $data->paint_status : 'select status' }}</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-4"><label for="advertise_poster_status">{{__("messages.cleaning_illegal_ads_banners")}}</label>
    </div>
    <div class="col-md-4">
        <select {{$disabled ? 'disabled' : '' }}  name="advertise_poster_status" id="advertise_poster_status"
        class="form-control" required>
        <option value="{{ $data->advertise_poster_status }}" hidden>{{ $data->advertise_poster_status }}</option>
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>

    </div>
</div>
<div class="row">
    <div class="col-md-4"><label for="bushes_status">{{__("messages.bushy")}}</label></div>
    <div class="col-md-4">
        <select {{$disabled ? 'disabled' : '' }}  name="bushes_status" id="bushes_status" class="form-control" required>
            <option value="{{ $data->bushes_status }}" hidden>{{ $data->bushes_status }}</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>

    </div>
</div>


<div class="row">
    <div class="col-md-4"><label for="link_box_image">{{__("messages.link_box")}} {{__("messages.images")}} </label></div>

    <div class="col-md-8 row">
        {!!  viewAndUpdateImage($data->link_box_image_1 , 'link_box_image_1' , $disabled )  !!}

        {!!  viewAndUpdateImage($data->link_box_image_2 , 'link_box_image_2' , $disabled )  !!}

        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-4"><label for="image_gate">{{__('messages.cover_image')}}</label></div>

    <div class="col-md-8 row">
        {!!  viewAndUpdateImage($data->image_cover , 'image_cover' , $disabled )  !!}

        {!!  viewAndUpdateImage($data->image_cover_2 , 'image_cover_2' , $disabled )  !!}

    </div>
</div>


<div class="row">
    <div class="col-md-4"><label for="image_vandalism">{{__('messages.image_vandalism')}}</label></div>

    <div class="col-md-8 row">
        {!!  viewAndUpdateImage($data->image_vandalism , 'image_vandalism' , $disabled )  !!}

        {!!  viewAndUpdateImage($data->image_vandalism_2 , 'image_vandalism_2' , $disabled )  !!}

    </div>
</div>


<div class="row">
    <div class="col-md-4"><label for="image_leaning">{{__("messages.image_leaning")}}</label></div>

    <div class="col-md-8 row">
        {!!  viewAndUpdateImage($data->image_leaning , 'image_leaning' , $disabled )  !!}

        {!!  viewAndUpdateImage($data->image_leaning_2 , 'image_leaning_2' , $disabled )  !!}

    </div>
</div>

<div class="row">
    <div class="col-md-4"><label for="image_rust">{{__("messages.image_rust")}}</label></div>

    <div class="col-md-8 row">
        {!!  viewAndUpdateImage($data->image_rust , 'image_rust' , $disabled )  !!}

        {!!  viewAndUpdateImage($data->image_rust_2 , 'image_rust_2' , $disabled )  !!}

    </div>
</div>



<div class="row">
    <div class="col-md-4"><label for="images_advertise_poster">{{__('messages.image_advertise_poster')}}</label></div>
    <div class="col-md-8 row">
        {!!  viewAndUpdateImage($data->images_advertise_poster , 'images_advertise_poster' , $disabled )  !!}

        {!!  viewAndUpdateImage($data->images_advertise_poster_2 , 'images_advertise_poster_2' , $disabled )  !!}

    </div>
</div>

<div class="row">
    <div class="col-md-4"><label for="images_bushes">{{__("messages.image_bushes")}}</label></div>

    <div class="col-md-8 row">
        {!!  viewAndUpdateImage($data->images_bushes , 'images_bushes' , $disabled )  !!}

        {!!  viewAndUpdateImage($data->images_bushes_2 , 'images_bushes_2' , $disabled )  !!}

    </div>
</div>

<div class="row">

    <div class="col-md-4">
        <label for="image_advertisement_after_1">{{__("messages.image_advertise_poster_removal")}}</label>
    </div>

    <div class="col-md-8 row">
        {!!  viewAndUpdateImage($data->image_advertisement_after_1 , 'image_advertisement_after_1' , $disabled )  !!}

        {!!  viewAndUpdateImage($data->image_advertisement_after_2 , 'image_advertisement_after_2' , $disabled )  !!}

        </div>


</div>

<div class="row">
    <div class="col-md-4"><label for="other_image">{{__("messages.other_image")}}</label></div>
    @if (!$disabled)
    <div class="col-md-4">
        <input  type="file" accept="image/*" name="other_image" id="other_image" class="form-control">
    </div>
    @endif

    <div class="col-md-4 text-center mb-3">
        @if ( $data->other_image != '')
            <a href="{{config('globals.APP_IMAGES_URL').$data->other_image }}" data-lightbox="roadtrip">
                <img src="{{ config('globals.APP_IMAGES_URL').$data->other_image }}" alt="" height="70" class="adjust-height ml-5  ">
            </a>
        @else
            <strong>{{ __('messages.no_image_found') }}</strong>
        @endif
    </div>
</div>

