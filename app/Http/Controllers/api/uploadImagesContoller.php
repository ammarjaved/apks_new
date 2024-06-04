<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\FeederPillar;
use App\Models\Substation;
use App\Models\ThirdPartyDiging;
use App\Models\Tiang;
use Illuminate\Http\Request;

class uploadImagesContoller extends Controller
{

    public function uploadImages(Request $req,  $modelName,  $id)
    {
        $success = false;
        $error = null;


        $modelClass = "App\\Models\\$modelName";
        try {
        $data = $modelClass::find($id);

        if ($data) {
            $destinationPath = 'assets/images/';
            $imageStoreUrlPath = config('globals.APP_IMAGES_STORE_URL').$destinationPath;


            foreach ($req->allFiles() as $key => $file) {
                // Check if the input is a file and it is valid
                if ($req->hasFile($key) && $req->file($key)->isValid()) {
                    $uploadedFile = $req->file($key);
                    $img_ext = $uploadedFile->getClientOriginalExtension();
                    $filename = $key . '-' . strtotime(now()) . rand(10, 100) . '.' . $img_ext;
                    $filePath = $imageStoreUrlPath   . $filename;

                    // Move the file to the first location
                    $fileContents = file_get_contents($uploadedFile->getRealPath());
                    file_put_contents($filePath, $fileContents);
                    // $uploadedFile->move($imageStoreUrlPath, $filename);
                    $data->{$key} = $destinationPath . $filename;


                }

            }

                // foreach ($req->all() as $key => $file) {
                //     // Check if the input is a file and it is valid
                //     if ($req->hasFile($key) && $req->file($key)->isValid()) {
                //         $uploadedFile = $req->file($key);
                //         $img_ext = $uploadedFile->getClientOriginalExtension();
                //         $filename = $key . '-' . strtotime(now()). $data->id . '.' . $img_ext;
                //         $uploadedFile->move($imageStoreUrlPath, $filename);
                //         $data->{$key} = $destinationPath . $filename;
                //     }
                // }
                $data->save();

                $message = 'Images uploaded successfully';
                $success = true;
                $status = 200;

        } else {
            $message = 'Record not found';
            $status = 404;
        }
    } catch (\Throwable $th) {
        $message = 'Server-side error';
        $status = 500;
        $error = $th->getMessage();
    }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'error' => $error,
        ], $status);
    }

    public function uploadTiangImages(Request $request , $id){

        $success = false;
        $error = null;



        try {
        $data = Tiang::find($id);

        if ($data) {
            $destinationPath = 'assets/images/';
            $imageStoreUrlPath = config('globals.APP_IMAGES_STORE_URL').$destinationPath;
           // $externalPath = config('globals.APP_IMAGES_STORE_URL_TEMP').'/';


            foreach ($request->all() as $mainkey => $mainvalue) {
                if (is_array($mainvalue)) {
                    $arr = [];
                    if ($data->{$mainkey} != '') {
                        $before = json_decode( $data->{$mainkey});
                        foreach($before as $bKey => $bname){
                            $arr[$bKey]=$bname;
                        }
                    }




                    foreach ($request->allFiles() as $key => $file) {
                        // Check if the input is a file and it is valid
                        if (is_a($file, 'Illuminate\Http\UploadedFile') && $file->isValid()) {
                            $uploadedFile = $file;
                            $img_ext = $uploadedFile->getClientOriginalExtension();
                            $filename = $key . '-' . strtotime(now()) . rand(10, 100) . '.' . $img_ext;

                            $filePath = $imageStoreUrlPath   . $filename;

                            // Move the file to the first location
                            $fileContents = file_get_contents($uploadedFile->getRealPath());
                            file_put_contents($filePath, $fileContents);

                            // Move the file to the first location
                            // $uploadedFile->move($imageStoreUrlPath, $filename);
                            $arr[$key] = $destinationPath.$filename;

                            // Copy the file to the second location
                            // $sourcePath = $imageStoreUrlPath . $filename;
                            // $destinationPath2 = $externalPath . $filename;
                            //  copy($sourcePath, $destinationPath2);
                        }

                    }

                    // foreach ($mainvalue as $key => $file) {
                    //     if (is_a($file, 'Illuminate\Http\UploadedFile') && $file->isValid()) {
                    //         $uploadedFile = $file;
                    //         $img_ext = $uploadedFile->getClientOriginalExtension();
                    //         $filename = $key . '-' . strtotime(now()). $data->id . '.' . $img_ext;

                    //         $uploadedFile->move($imageStoreUrlPath, $filename);
                    //         $arr[$key] = $destinationPath.$filename;


                    //     }
                    // }
                    $data[$mainkey] = json_encode($arr);
                }else{

                    // if (is_a($mainvalue, 'Illuminate\Http\UploadedFile') && $mainvalue->isValid()) {
                    //     $uploadedFile = $mainvalue;
                    //     $img_ext = $uploadedFile->getClientOriginalExtension();
                    //     $filename = $mainkey . '-' . strtotime(now()). $data->id . '.' . $img_ext;
                    //     $uploadedFile->move($imageStoreUrlPath, $filename);
                    //     $data[$mainkey] = $destinationPath.$filename ;
                    // }


                        if (is_a($mainvalue, 'Illuminate\Http\UploadedFile') && $mainvalue->isValid()) {
                            $uploadedFile = $mainvalue;
                            $img_ext = $uploadedFile->getClientOriginalExtension();
                            $filename = $mainkey . '-' . strtotime(now()) . rand(10, 100) . '.' . $img_ext;


                            $filePath = $imageStoreUrlPath   . $filename;

                            // Move the file to the first location
                            $fileContents = file_get_contents($uploadedFile->getRealPath());
                            file_put_contents($filePath, $fileContents);
                            // Move the file to the first location
                            // $uploadedFile->move($imageStoreUrlPath, $filename);
                            $data[$mainkey] = $destinationPath.$filename ;

                            // Copy the file to the second location
                            // $sourcePath = $imageStoreUrlPath . $filename;
                            // $destinationPath2 = $externalPath . $filename;
                            //  copy($sourcePath, $destinationPath2);
                        }



                }
            }

                $data->save();

                $message = 'Images uploaded successfully';
                $success = true;
                $status = 200;

        } else {
            $message = 'Record not found';
            $status = 404;
        }
    } catch (\Throwable $th) {
        $message = 'Server-side error';
        $status = 500;
        $error = $th->getMessage();
    }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'error' => $error,
        ], $status);


    }



}
