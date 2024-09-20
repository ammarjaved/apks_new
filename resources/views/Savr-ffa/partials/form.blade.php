


    {{-- BA --}}
    <div class="row">
        <div class="col-md-4"><label for="ba">{{ __('messages.ba') }}</label></div>
        <div class="col-md-4">
            <input type="text" name="ba" id="ba" readonly class="form-control" value="{{$data->ba}}" required>
            {{-- <input type="hidden" name="ba"   class="form-control" value="{{$data->ba}}"> --}}

        </div>
    </div>

    {{-- POLE NO --}}
    <div class="row">
        <div class="col-md-4"><label for="pole_no">{{__("messages.pole_no")}} </label></div>
        <div class="col-md-4">

            <input   type="text" name="pole_no"    value="{{$data->pole_no}}"   class="form-control" required >

        </div>
    </div>

    {{-- POLE ID --}}
    <div class="row">
        <div class="col-md-4"><label for="pole_id">{{__("messages.pole_id")}}</label></div>
        <div class="col-md-4">
            <input type="text" name="pole_id" id="pole_id" class="form-control" readonly value="{{$data->pole_id}}" required {{!$disabled ?: "disabled"}}>
        </div>
    </div>

      {{-- HOUSE NUMBER --}}
      <div class="row">
        <div class="col-md-4"><label for="house_number">{{__("messages.house_number")}}</label></div>
        <div class="col-md-4">
            <input type="text" name="house_number" id="house_number" class="form-control" required value="{{ $data->house_number }}" {{!$disabled ?: "disabled"}}>
        </div>
    </div>


    {{-- WAYAR TERTANGGAL --}}

    <div class="row">
        <div class="col-md-4"><label for="wayar_tertanggal">{{__("messages.wayar_tertanggal")}}</label></div>
        <div class="col-md-4">
            <select name="wayar_tertanggal" id="wayar_tertanggal" class="form-control" required {{!$disabled ?: "disabled"}}>
                <option value="{{$data->wayar_tertanggal}}" hidden>{{$data->wayar_tertanggal == '' ? 'select' : $data->wayar_tertanggal}}</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>

    {{-- JOINT BOX --}}

    <div class="row">
        <div class="col-md-4"><label for="joint_box">{{__("messages.joint_box")}} </label></div>
        <div class="col-md-4">
            <select name="joint_box" id="joint_box" class="form-control" required {{!$disabled ?: "disabled"}}>
                <option value="{{$data->joint_box}}" hidden>{{$data->joint_box == '' ? 'select' : $data->joint_box}}</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>

    {{-- IPC TERBAKAR --}}
    <div class="row">
        <div class="col-md-4"><label for="ipc_terbakar">{{__("messages.ipc_terbakar")}}</label></div>
        <div class="col-md-4">
            <select name="ipc_terbakar" id="ipc_terbakar" class="form-control" required {{!$disabled ?: "disabled"}}>
                <option value="{{$data->ipc_terbakar}}" hidden>{{$data->ipc_terbakar == '' ? 'select' : $data->ipc_terbakar}}</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>

    {{-- HOUSE RENOVATION --}}
    <div class="row">
        <div class="col-md-4"><label for="house_renovation">{{__("messages.house_renovation")}}</label></div>
        <div class="col-md-4">
            <select name="house_renovation" id="house_renovation" class="form-control" required {{!$disabled ?: "disabled"}}>
                <option value="{{$data->house_renovation}}" hidden>{{$data->house_renovation == '' ? 'select' : $data->house_renovation}}</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>

    {{-- OTHER --}}
    <div class="row">
        <div class="col-md-4"><label for="other">{{__("messages.other")}}</label></div>
        <div class="col-md-4">
            <select name="other" id="other" class="form-control" required {{!$disabled ?: "disabled"}}>
                <option value="{{$data->other}}" hidden>{{$data->other == '' ? 'select' : $data->other}}</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
    </div>

     {{-- OTHER NAME --}}
     <div class="row  {{$data->other === 'Yes' ?:'d-none'}}" id="other_name_div">
        <div class="col-md-4"><label for="other_name">{{__("messages.other_name")}}</label></div>
        <div class="col-md-4">
            <input type="text" name="other_name" id="other_name" class="form-control " value="{{$data->other_name}}" {{!$disabled ?: "disabled"}}>
        </div>
    </div>






    {{-- SAVT IMAGE 1 --}}
    <div class="row">
        <div class="col-md-4"><label for="house_image">{{ __('messages.house_image') }}</label></div>
        <div class="col-md-8 row">{!!  viewAndUpdateImage($data->house_image , 'house_image' , $disabled)   !!}</div>
    </div>

    <div class="row">
        <div class="col-md-4"><label for="house_image">{{ __('messages.house_image1') }}</label></div>
        <div class="col-md-8 row">{!!  viewAndUpdateImage($data->image2 , 'house_image2' , $disabled)   !!}</div>
    </div>

    <div class="row">
        <div class="col-md-4"><label for="house_image">{{ __('messages.house_image2') }}</label></div>
        <div class="col-md-8 row">{!!  viewAndUpdateImage($data->image3 , 'house_image3' , $disabled)   !!}</div>
    </div>
