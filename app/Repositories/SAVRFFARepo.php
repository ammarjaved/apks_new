<?php

namespace App\Repositories;

use App\Models\SavrFfa;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class SAVRFFARepo
{

    public function store($data, $request)
    {

        $data->wayar_tertanggal = $request->wayar_tertanggal;
        $data->pole_id = $request->pole_id;
        $data->ipc_terbakar = $request->ipc_terbakar;
        $data->other = $request->other;
        $data->other_name = $request->other_name;
        $data->pole_no = $request->pole_no;

        $data->ba = $request->ba;
        $data->joint_box = $request->joint_box;
        $data->house_renovation = $request->house_renovation;
        $data->house_number = $request->house_number;



        $destinationPath = 'assets/images/';
        $imageStoreUrlPath = config('globals.APP_IMAGES_STORE_URL').$destinationPath;




            if ($request->hasFile('house_image') && $request->file('house_image')->isValid()) {
                $uploadedFile = $request->file('house_image');
                $img_ext = $uploadedFile->getClientOriginalExtension();
                $filename = 'house_image' . '-' . strtotime(now()) . rand(10, 100) . '.' . $img_ext;

                $filePath = $imageStoreUrlPath   . $filename;

                $fileContents = file_get_contents($uploadedFile->getRealPath());
                file_put_contents($filePath, $fileContents);

                $data->house_image = $destinationPath . $filename;


            }

        return $data;
    }

}
