<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

function checkCheckBox($key, $array)
{
    try {
        //code...

        if ($array != null) {
            if (array_key_exists($key, $array) && $array[$key]==true) {

                return 'checked';
            }
        }
        return '';
    } catch (\Throwable $th) {
        //throw $th;
    }
}

function substaionCheckBox($key, $array)
{
    try {
        if ($array != null) {
            if ($array->$key == 'true') {
                return 'checked';
            }
        }
        return '';
    } catch (\Throwable $th) {
        //throw $th;
    }
}

function getInputValue($key, $array)
{
    try {
        if (is_array($array) || is_a($array, 'stdClass')) {
            if (property_exists($array, $key) || array_key_exists($key, (array) $array)) {
                return is_array($array) ? $array[$key] : $array->$key;
            }
        }
        return '';
    } catch (\Throwable $th) {
    }
}

function excelCheckBOc($key, $array)
{

    // return $array;
    if ($array != null && isset($array->{$key})) {
       return $array->{$key} ? '1':'0';
    }
    return "";

}

function getZone()
{
    $zone = '';
    $ba = Auth::user()->ba;

    if (empty($ba)) {
        $zone = '<option value="" hidden>select zone</option>
        <option value="W1">W1</option>
        <option value="B1">B1</option>
        <option value="B2">B2</option>
        <option value="B4">B4</option>';
    } else {
        $sql = DB::select('SELECT ppb_zone FROM ba WHERE station = ?', [$ba]);

        if (count($sql) > 0) {
            $zone = '<option value="' . $sql[0]->ppb_zone . '">' . $sql[0]->ppb_zone . '</option>';
        }
    }

    return $zone;
}

function getImage($checkBox, $arr, $key)
{
    try {
        if ($checkBox == 'checked') {
            if ($arr != null) {
                if (array_key_exists($key, $arr) && file_exists(public_path($arr[$key])) && $arr[$key] != '') {
                    return '<a href="' .
                        URL::asset($arr[$key]) .
                        '" data-lightbox="roadtrip">
                            <img src="' .
                        URL::asset($arr[$key]) .
                        '" alt="" class="adjust-height mb-1" style="height:30px; width:30px !important">
                        </a>';
                }
            }
        } else {
            return '';
        }
        return "<span style='font-size:11px'>no image found</span>";
    } catch (\Throwable $th) {
        //throw $th;
    }
}

function getImage2($key, $arr, $arr_name, $img_arr, $lab_name)
{
    $lab_name = __('messages.' . $lab_name);
    $html = '';



    $id = $arr_name . '_' . $key;
    $name = $arr_name . '[' . $key . ']';
    $image1 = $key == 'creepers_after' ? 'creepers_after1' : $key;

    $image_name = $arr_name . '_image[' . $image1 . ']';
    $image_name_2 = "{$arr_name}_image[{$key}2]";

    // Check if $key is "other" to decide the CSS classes
    $class = $key != 'other' ? 'd-flex' : '';

    if ($key  !== 'creepers_after') {
        // Check for checked checkbox
        $key_exist = !empty($arr) && array_key_exists($key, $arr) && $arr[$key] == true;
        $html .= "<td class='$class'>
                        <input type='checkbox' name='$name' id='$id' " . ($key_exist? 'checked' : '') . " class='form-check'>
                <label class='text-capitalize' for='$id'> $lab_name</label>";
    }else{
        // Check for checked checkbox
        $key_exist = !empty($arr) && array_key_exists('creepers', $arr) && $arr['creepers'] == true;
        $html .=
        "<td class='$class'><label class='text-capitalize' for='$id'> $lab_name After</label>";
    }


    if ($key == 'other') {
        $key2 = $key . '_value';
        $otherValue = isset($arr[$key2]) ? $arr[$key2] : '';
        $html .= "<input type='text' name='{$arr_name}[{$key2}]' id='{$id}-input'  value='$otherValue' class='form-control " . ($key_exist ? '' : 'd-none') . "' placeholder='mention other defect'>";
    }


    $html .=
        "</td>
            <td>
                <input type='file' name='$image_name' id='{$id}-image' class='" . ($key_exist ? '' : 'd-none') . " form-control' accept='image/*'>
                <input type='file' name='$image_name_2' id='{$id}-image-2' class='" . ($key_exist ? '' : 'd-none') . " form-control' accept='image/*'>
            </td>
            <td>";

    if ($key_exist   && $img_arr != '') {
        if (array_key_exists($image1, $img_arr) && file_exists(public_path($img_arr[$image1])) && $img_arr[$image1] != '') {

            $html .=
                "<a href='" . URL::asset($img_arr[$image1]) . "' data-lightbox='roadtrip'>
                    <img src='" . URL::asset($img_arr[$image1]) . "' class='adjust-height mb-1' style='height:30px; width:30px !important'>
                </a>";
        }

        if (array_key_exists($key . '2', $img_arr) && file_exists(public_path($img_arr[$key . '2'])) && $img_arr[$key . '2'] != '') {
            $html .=
                "<a href='" . URL::asset($img_arr[$key . '2']) . "' data-lightbox='roadtrip'>
                    <img src='" . URL::asset($img_arr[$key . '2']) . "' class='adjust-height mb-1' style='height:30px; width:30px !important'>
                </a>";
        }
        # code...
    }
    $html .= '</td>';

    return $html;
}

function getImageShow($key, $arr, $arr_name, $img_arr, $lab_name)
{
    $lab_name = __('messages.' . $lab_name);
    $html = '';

    // Check for checked checkbox
    $key_exist = !empty($arr) && array_key_exists($key, $arr) && $arr[$key] == true;

    $id = $arr_name . '_' . $key;
    $name = $arr_name . '[' . $key . ']';

    // Check if $key is "other" to decide the CSS classes
    $class = $key != 'other' ? 'd-flex' : '';

    if ($key  !== 'creepers_after') {
        // Check for checked checkbox
        $key_exist = !empty($arr) && array_key_exists($key, $arr) && $arr[$key] == true;
        $html .= "<td class='$class'>
                        <input type='checkbox' name='$name' id='$id' " . ($key_exist? 'checked' : '') . " disabled class='form-check'>
                <label class='text-capitalize' for='$id'> $lab_name</label>";
    }else{
        // Check for checked checkbox
        $key_exist = !empty($arr) && array_key_exists('creepers', $arr) && $arr['creepers'] == true;
        $html .=
        "<td class='$class'><label class='text-capitalize' for='$id'> $lab_name After</label>";
    }
    if ($key == 'other') {
        $key2 = $key . '_value';
        $otherValue = isset($arr[$key2]) ? $arr[$key2] : '';
        $html .= "<input type='text'  id='{$id}-input'  value='$otherValue' class='form-control " . ($key_exist ? '' : 'd-none') . "' placeholder='mention other defect' disabled>";
    }

    $html .= "</td>
    <td class=''>";

    if ($key_exist && $img_arr != '') {
        $image1 = $key == 'creepers_after' ? 'creepers_after1' : $key;
        if (array_key_exists($image1, $img_arr) && file_exists(public_path($img_arr[$image1])) && $img_arr[$image1] != '') {

            $html .=
                "<a href='" . URL::asset($img_arr[$image1]) . "' data-lightbox='roadtrip'>
                    <img src='" . URL::asset($img_arr[$image1]) . "' class='adjust-height mb-1' style='height:30px; width:30px !important'>
                </a>";
        }

        if (array_key_exists($key . '2', $img_arr) && file_exists(public_path($img_arr[$key . '2'])) && $img_arr[$key . '2'] != '') {
            $html .=
                "<a href='" .
                URL::asset($img_arr[$key . '2']) .
                "' data-lightbox='roadtrip'>
                    <img src='" .
                URL::asset($img_arr[$key . '2']) .
                "' class='adjust-height mb-1' style='height:30px; width:30px !important'>
                </a>";
        }
    }
    $html .= '</td>';

    return $html;
}


function tiangSpanRadio($value , $key , $subkey , $status)
{

    $html = '';

    $name = $key ."_".$subkey;
    $disable = $status ? '' : 'disabled';

    $other_key  =  isset($value->$subkey) && !in_array($value->$subkey, [1, 2, 3, 4]) && $value->$subkey != '' ? true : false;
    $html .= "<div class='row mb-3'>
                    <div class='col-md-2 d-flex'>
                        <input type='radio' name='".$name."' id='".$name."_1' value='1' class='select-radio-value' ". (isset($value->$subkey) && $value->$subkey == 1 ? 'checked' : '') ." $disable>
                        <label for='".$name."_1' class='fw-400'>1</label>
                    </div>

                    <div class='col-md-2 d-flex'>
                        <input type='radio' name='".$name."' id='".$name."_2' value='2' class='select-radio-value' ". (isset($value->$subkey) && $value->$subkey == 2 ? 'checked' : '') ." $disable>
                        <label for='".$name."_2' class='fw-400'>2</label>
                    </div>

                    <div class='col-md-2 d-flex'>
                        <input type='radio' name='".$name."' id='".$name."_3' value='3' class='select-radio-value'  ". (isset($value->$subkey) && $value->$subkey == 3 ? 'checked' : '') ." $disable>
                        <label for='".$name."_3' class='fw-400'>3</label>
                    </div>

                    <div class='col-md-2 d-flex'>
                        <input type='radio' name='".$name."' id='".$name."_4' value='4' class='select-radio-value' ". (isset($value->$subkey) && $value->$subkey == 4 ? 'checked' : '') ." $disable>
                        <label for='".$name."_4' class='fw-400'>4</label>
                    </div>

                    <div class='col-md-2 d-flex'>
                        <input type='radio' name='".$name."' id='".$name."_other' value='other' class='select-radio-value' ". ($other_key  ? 'checked' : '') ." $disable>
                        <label for='".$name."_other' class='fw-400'>other</label>
                    </div>
              </div>
              <div class='col-md-6'><input type='number' name='".$key."[".$subkey."]' placeholder='enter other value'
                                                        id='".$name."_input' value='".(isset($value->$subkey) ?  $value->{$subkey} : '' )."' class='form-control   ".($other_key  ? '' : 'd-none' )." '$disable></div>
    ";
    return $html;









}





//  for show and update images start

    function viewAndUpdateImage($image , $name , $disabled )
    {

            $html = '';
                // IF DISABLE IS FALSE THEN SHOW IMAGE UPLOAD INPUT FIELD
            if (!$disabled)
            {
                $html.="<div class='col-md-4'>
                            <input type='file' accept='image/*' name='$name' id='$name' class='form-control'>
                        </div>";
            }

            $html.="<div class='col-md-4 text-center  py-2'>";      //START DIV

                        if ( $image != ''  )    // IF IMAGE IS NOT EMPTY THEN SHOW IMAGE
                        {
                            $html.="<a href='".config('globals.APP_IMAGES_URL').$image ."' data-lightbox='roadtrip'>
                                        <img src='".config('globals.APP_IMAGES_URL').$image ."' alt='' style='height:70px' height='70' class='adjust-height ml-5'>
                                    </a>";
                        }
                        else                    //ELSE SHOW IMAGE NOT FOUND
                        {
                            $html.="<strong>".__('messages.no_image_found') ."</strong>";
                        }
            $html.="</div>
                    <div id='".$name."_div'> </div>";   //FOR SHOW TEMP IMAGE

            return $html;
    }

//  for show and update images end






    function getImageForPdfHelper($imagePaht  )
    {
        $html = '';
        if ($imagePaht != '' && file_exists(public_path($imagePaht)))
        {
            $html.= '<img src="data:image/png;base64,'.base64_encode(file_get_contents(public_path($imagePaht))).'" height="70" alt="" srcset="">' ;
        }
        return $html;
    }


    function getRepairDate($key , $array){
        // return $key;
        if ($array != null && array_key_exists($key , $array)) {
            return  $array[$key];
         }
         return "";

    }


    function tiangDefactCheckBox($key, $arr, $arr_name,  $lab_name , $disabled)
    {
    $lab_name = __('messages.' . $lab_name);
    $html = '';

    $id = $arr_name . '_' . $key;
    $name = $arr_name . '[' . $key . ']';



    $key_exist = !empty($arr) && array_key_exists($key, $arr) && $arr[$key] == true;
        $html .= "<td class='d-flex'>
                        <input type='checkbox' name='$name' id='$id' " . ($key_exist? 'checked' : '') . " class='form-check' ".($disabled ? '' : 'disabled').">
                <label class='text-capitalize' for='$id'> $lab_name</label>";


    if ($key == 'other') {
        $key2 = $key . '_value';
        $otherValue = isset($arr[$key2]) ? $arr[$key2] : '';
        $html .= "<input type='text' name='{$arr_name}[{$key2}]' id='{$id}-input'  value='$otherValue' class='form-control " . ($key_exist ? '' : 'd-none') . "' placeholder='mention other defect' ".($disabled ? '' : 'disabled').">";
    }

    $html .= '</td>';

    return $html;
}




    // SAVT YES NO
    function savtYesOrNo($name, $disable = null, $value = null )
    {
        $yes = __('messages.yes');
        $no = __('messages.no');
        $html = '<div class="d-flex px-4">
                    <input type="radio" name="'.$name.'" id="'.$name.'_yes" value="Yes" '. ($value == "Yes" ? "checked" : "").' '. (!$disable?:"disabled").'>
                    <label for="'.$name.'_yes">'.$yes.'</label>
                </div>
                <div class="d-flex px-4">
                    <input type="radio" name="'.$name.'" id="'.$name.'_no" value="No" '.($value == "No" ? "checked" : "").' '. (!$disable?:"disabled").'>
                    <label for="'.$name.'_no">'.$no.'</label>
                </div>';

        return $html;

    }
