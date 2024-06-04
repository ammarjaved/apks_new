<?php

namespace App\Repositories;

use App\Models\Tiang;

class TiangRepository
{


    protected function uploadImges(array $request){


        // $destinationPath = 'assets/images/';
        //     foreach ($request->all() as $key => $file) {
        //         // Check if the input is a file and it is valid
        //         if ($request->hasFile($key) && $request->file($key)->isValid()) {
        //             $uploadedFile = $request->file($key);
        //             $img_ext = $uploadedFile->getClientOriginalExtension();
        //             $filename = $key . '-' . strtotime(now()) . '.' . $img_ext;
        //             $uploadedFile->move($destinationPath, $filename);
        //             $data->{$key} = $destinationPath . $filename;
        //         }
        //     }
    }
}
