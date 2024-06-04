<?php

namespace App\Repositories;

use App\Constants\SAVTConstants;
use App\Models\SAVT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class SAVTRepo
{


    public function store($request){
        $data = new SAVT();
        $data->qa_status = 'pending';
        $user = Auth::user()->name;
        $data->created_by = $user;
        if ($request->lat != '' && $request->log != '') {
            $data->geom =DB::raw("ST_GeomFromText('POINT(" . $request->log . ' ' . $request->lat . ")',4326)");
            $data->coords = number_format( $request->log , 5 )  .' , ' . number_format( $request->lat , 5);
        }
        $data->ba  = $request->ba;
        return $data;
    }



    public function prepareData($data , $request)
    {
        $data->supplier_pmu_ppu                 = $request->supplier_pmu_ppu;
        $data->supplier_feeder_no               = $request->supplier_feeder_no;
        $data->road_name                        = $request->road_name;
        $data->sec_from                         = $request->sec_from;
        $data->sec_to                           = $request->sec_to;
        $data->tiang_no                         = $request->tiang_no;
        $data->voltan_kv                        = $request->voltan_kv;
        $data->visit_date                       = $request->visit_date;
        $data->abc_panjang_meter                = $request->abc_panjang_meter;
        $data->abc_size_mmp                     = $request->abc_size_mmp;
        $data->bare_size_mmp                    = $request->bare_size_mmp;
        $data->bare_panjang_meter               = $request->bare_panjang_meter;
        $data->underground_cabel_size_mmp       = $request->underground_cabel_size_mmp;
        $data->underground_cabel_length_meter   = $request->underground_cabel_length_meter;
        $data->remarks                          = $request->remarks;
        $data->five_feet_away                   = $request->five_feet_away;
        $data->ffa_no_of_houses                 = $request->ffa_no_of_houses;
        $data->ffa_house_no                     = $request->ffa_house_no;
        $data->eqp_no_auto_circuit_recloser     = $request->eqp_no_auto_circuit_recloser;
        $data->eqp_no_load_break_switch         = $request->eqp_no_load_break_switch;
        $data->eqp_no_isolator_switch           = $request->eqp_no_isolator_switch;
        $data->eqp_no_set_lfi                   = $request->eqp_no_set_lfi;


        // $data->fill($request->only($data->getFillable()));



        $defects = SAVTConstants::SAVT_DEFECT;
        $total_defects = 0 ;
        foreach($defects as $defect){
            if($request->has($defect) && $request->{$defect} == 'Yes'){
                $total_defects ++ ;
            }
            $data->{$defect} = $request->has($defect) ?$request->{$defect} : '' ;
        }

        $data->total_defects = $total_defects;



        $defectsImg = SAVTConstants::SAVT_IMAGES;
        $destinationPath = 'assets/images/savt/';
        $imageStoreUrlPath = config('globals.APP_IMAGES_STORE_URL').$destinationPath;
     //   $externalPath = config('globals.APP_IMAGES_STORE_URL_TEMP').'/savt/';


        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }


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
            //    $sourcePath = $imageStoreUrlPath . $filename;
         //       $destinationPath2 = $externalPath . $filename;
            //     copy($sourcePath, $destinationPath2);
            }

        }
        return $data;


    }


}
