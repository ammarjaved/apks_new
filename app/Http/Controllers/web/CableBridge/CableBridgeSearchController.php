<?php

namespace App\Http\Controllers\web\CableBridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CableBridge;


class CableBridgeSearchController extends Controller
{
    //
    public function getCableBridgeByPolygon(Request $request)
    {

// return "Asda";
        try
        {
            $data = CableBridge::whereRaw("ST_Intersects(geom, ST_GeomFromGeoJSON('$request->json'))")
                            ->select('id', 'ba', 'end_date', 'start_date','voltage' ,'cable_bridge_image_1', 'cable_bridge_image_2' ,'reject_remarks', 'qa_status','created_by','total_defects', 'visit_date')
                            ->whereNotNull('cable_bridge_image_1')
                            ->where('qa_status', 'pending')
                            ->whereNotNull('visit_date')
                            ->orderBy('id')
                            ->get();


        } catch (\Throwable $th) {
            // return $th->getMessage();
            return response()->json(['data'=>'' ,'status'=> 400]);
        }

        return response()->json(['data'=> $data , 'status' => 200]);
    }
}
