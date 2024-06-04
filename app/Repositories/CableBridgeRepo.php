<?php

namespace App\Repositories;

use App\Models\Substation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class CableBridgeRepo
{

    public function store($data, $request)
    {
        $currentDate = Carbon::now()->toDateString();
        $combinedDateTime = $currentDate . ' ' . $request->patrol_time;

            $defects = [];
            $defects = ['pipe_staus', 'vandalism_status', 'collapsed_status', 'rust_status', 'bushes_status' ,
                'danger_sign', 'anti_crossing_device','condong','pencerobohan','kebersihan_jabatan'];

            if ($data->qa_status == '') {
                $data->qa_status = 'pending';
            }
            $total_defects =0;

            $data->zone = $request->zone;
            $data->ba = $request->ba;
            $data->team = $request->team;
            $data->visit_date = $request->visit_date;
            $data->patrol_time = $combinedDateTime;
            $data->feeder_involved = $request->feeder_involved;

            $data->start_date = $request->start_date;
            $data->end_date = $request->end_date;
            $data->voltage = $request->voltage;


            foreach ($defects as  $value) {
                $data->{$value} = $request->{$value};
                $request->has($value)&& $request->{$value} == 'Yes' ? $total_defects++ : '';
            }
            $data->total_defects = $total_defects;


            $destinationPath = 'assets/images/cable-bridge/';
            $imageStoreUrlPath = config('globals.APP_IMAGES_STORE_URL').$destinationPath;
      //      $externalPath = config('globals.APP_IMAGES_STORE_URL_TEMP').'/cable-bridge/';


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
           //         $sourcePath = $imageStoreUrlPath . $filename;
                  //  $destinationPath2 = $externalPath . $filename;
                   //  copy($sourcePath, $destinationPath2);
                }

            }
        return $data;
    }

}
