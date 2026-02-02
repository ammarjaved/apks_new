<?php

namespace App\Repositories;

use App\Models\Substation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

class FeederPillarRepo
{

    public function store($data, $request)
    {
        $currentDate = Carbon::now()->toDateString();
        $combinedDateTime = $currentDate . ' ' . $request->patrol_time;



            $defects = [];
            $defects =['leaning_staus','vandalism_status','advertise_poster_status','rust_status','paint_status'];

            if ($data->qa_status == '') {
                $data->qa_status = 'pending';
            }
            $total_defects =0;

            $data->zone = $request->zone;
            $data->ba = $request->ba;
            $data->team = $request->team;
            $data->visit_date = $request->visit_date;
            $data->patrol_time = $combinedDateTime;

            $data->size = $request->size;

            $data->leaning_angle = $request->leaning_angle;

            $gate = [ 'unlocked' => 'false', 'demaged' => 'false', 'other'=>'false'];

            if ($request->has('gate_status')) {
                $gateStatus = $request->gate_status;

                foreach ($gate as $key => $value) {

                    if (array_key_exists($key, $gateStatus)) {
                        $gate[$key] = true;
                        $total_defects++;
                    }else{
                        $gate[$key] = false;
                    }

                }
                $gate['other_value'] = $request->gate_status['other_value'];
            }
            $data->gate_status = json_encode($gate) ;
            foreach ($defects as  $value) {
                $data->{$value} = $request->{$value};
               $request->has($value)&& $request->{$value} == 'Yes' ? $total_defects++ : '';
            }
            $data->total_defects = $total_defects;


            // $destinationPath = 'assets/images/cable-bridge/';
            // $imageStoreUrlPath = config('globals.APP_IMAGES_STORE_URL').$destinationPath;
            // foreach ($request->all() as $key => $file) {
            //     // Check if the input is a file and it is valid
            //     if ($request->hasFile($key) && $request->file($key)->isValid()) {
            //         $uploadedFile = $request->file($key);
            //         $img_ext = $uploadedFile->getClientOriginalExtension();
            //         $filename = $key . '-' . strtotime(now()).rand(10,1000)  . '.' . $img_ext;
            //         $uploadedFile->move($imageStoreUrlPath, $filename);
            //         $data->{$key} = $destinationPath . $filename;
            //     }
            // }

            $destinationPath = 'assets/images/cable-bridge/';
            $imageStoreUrlPath = config('globals.APP_IMAGES_STORE_URL').$destinationPath;
          //  $externalPath = config('globals.APP_IMAGES_STORE_URL_TEMP').'/cable-bridge/';

            foreach ($request->allFiles() as $key => $file) {
                // Check if the input is a file and it is valid
                if ($request->hasFile($key) && $request->file($key)->isValid()) {
                    $uploadedFile = $request->file($key);
                    $img_ext = $uploadedFile->getClientOriginalExtension();
                    $filename = $key . '-' . strtotime(now()) . rand(10, 100) . '.' . $img_ext;
                    $filePath = $imageStoreUrlPath   . $filename;

                    // Move the file to the first location
                    $fileContents = file_get_contents($uploadedFile->getRealPath());
                    file_put_contents($filePath, $fileContents);
                    // Move the file to the first location
                    // $uploadedFile->move($imageStoreUrlPath, $filename);
                    $data->{$key} = $destinationPath . $filename;

                    // Copy the file to the second location
                   // $sourcePath = $imageStoreUrlPath . $filename;
                 //   $destinationPath2 = $externalPath . $filename;
                  //   copy($sourcePath, $destinationPath2);
                }

            }


            $data->save();

        return $data;
    }

public function getSubstation($id )  {


    $data = Substation::find($id);
    if ($data) {
        $data->gate_status = json_decode($data->gate_status);
        $data->building_status = json_decode($data->building_status);

        return $data;
    }
    return '';
}
}
