<?php

namespace App\Http\Controllers\web\FeederPillar;

use App\Http\Controllers\Controller;
use App\Models\FeederPillar;
use Illuminate\Http\Request;

class FeederPillarSearchController extends Controller
{
    public function getFeederPillarByPolygon(Request $request)
    {
        

        try 
        {
            $data = FeederPillar::whereRaw("ST_Intersects(geom, ST_GeomFromGeoJSON('$request->json'))")
                            ->select('id', 'ba', 'size', 'visit_date','patrol_time' ,'feeder_pillar_image_1', 'feeder_pillar_image_2' ,'reject_remarks', 'qa_status','created_by','total_defects')
                            ->whereNotNull('feeder_pillar_image_1')
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
