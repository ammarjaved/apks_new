<?php

namespace App\Http\Controllers\web\CableBridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CableBridge;
use Illuminate\Support\Facades\DB;


class CableBridgeSearchController extends Controller
{
    //
    public function getCableBridgeByPolygon(Request $request)
    {

// return "Asda";
        try
        {

            $data = DB::table('tbl_cable_bridge_geom')
                    ->join('tbl_cable_bridge', 'tbl_cable_bridge_geom.id', '=', 'tbl_cable_bridge.geom_id')
                    ->whereRaw("ST_Intersects(tbl_cable_bridge_geom.geom, ST_GeomFromGeoJSON(?))", [$request->json])
                    ->where('qa_status', 'pending')
                    ->whereNotNull('cable_bridge_image_1')
                    ->whereNotNull('visit_date')
                    ->where('cycle',$request->cycle)
                    ->select(
                        'tbl_cable_bridge.id',
                        'tbl_cable_bridge.ba',
                        'tbl_cable_bridge.end_date',
                        'tbl_cable_bridge.start_date',
                        'tbl_cable_bridge.voltage',
                        'tbl_cable_bridge.cable_bridge_image_1',
                        'tbl_cable_bridge.cable_bridge_image_2',
                        'tbl_cable_bridge.reject_remarks',
                        'tbl_cable_bridge.qa_status',
                        'tbl_cable_bridge.created_by',
                        'tbl_cable_bridge.total_defects',
                        'tbl_cable_bridge.visit_date'
                        )
                    ->orderBy('id')
                    ->get();

            // $data = CableBridge::whereRaw("ST_Intersects(geom, ST_GeomFromGeoJSON('$request->json'))")
            //                 ->select('id', 'ba', 'end_date', 'start_date','voltage' ,'cable_bridge_image_1', 'cable_bridge_image_2' ,'reject_remarks', 'qa_status','created_by','total_defects', 'visit_date')
            //                 ->whereNotNull('cable_bridge_image_1')
            //                 ->where('qa_status', 'pending')
            //                 ->whereNotNull('visit_date')
            //                 ->orderBy('id')
            //                 ->get();


        } catch (\Throwable $th) {
            return $th->getMessage();
            return response()->json(['data'=>'' ,'status'=> 400]);
        }

        return response()->json(['data'=> $data , 'status' => 200]);
    }
}
