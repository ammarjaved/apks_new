<?php

namespace App\Http\Controllers\web\FeederPillar;

use App\Http\Controllers\Controller;
use App\Models\FeederPillar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeederPillarSearchController extends Controller
{
    public function getFeederPillarByPolygon(Request $request)
    {

        return $request->cycle;
        try
        {

            $data = DB::table('tbl_feeder_pillar_geom')
                    ->join('tbl_feeder_pillar', 'tbl_feeder_pillar_geom.id', '=', 'tbl_feeder_pillar.geom_id')
                    ->whereRaw("ST_Intersects(tbl_feeder_pillar_geom.geom, ST_GeomFromGeoJSON(?))", [$request->json])
                    // ->where('qa_status', 'pending')
                    // ->whereNotNull('feeder_pillar_image_1')
                    // ->whereNotNull('visit_date')
                    ->where('cycle',$request->cycle)
                    ->select(
                        'tbl_feeder_pillar.id',
                        'tbl_feeder_pillar.ba',
                        'tbl_feeder_pillar.size',
                        'tbl_feeder_pillar.visit_date',
                        'tbl_feeder_pillar.patrol_time',
                        'tbl_feeder_pillar.feeder_pillar_image_1',
                        'tbl_feeder_pillar.feeder_pillar_image_2',
                        'tbl_feeder_pillar.reject_remarks',
                        'tbl_feeder_pillar.qa_status',
                        'tbl_feeder_pillar.created_by',
                        'tbl_feeder_pillar.total_defects'
                        )
                    ->orderBy('id')
                    ->get();

            // $data = FeederPillar::whereRaw("ST_Intersects(geom, ST_GeomFromGeoJSON('$request->json'))")
            //                 ->select('id', 'ba', 'size', 'visit_date','patrol_time' ,'feeder_pillar_image_1', 'feeder_pillar_image_2' ,'reject_remarks', 'qa_status','created_by','total_defects')
            //                 ->whereNotNull('feeder_pillar_image_1')
            //                 ->where('qa_status', 'pending')
            //                 ->whereNotNull('visit_date')
            //                 ->orderBy('id')
            //                 ->get();

        } catch (\Throwable $th) {
            // return $th->getMessage();
            return response()->json(['data'=>'' ,'status'=> 400]);
        }
        return response()->json(['data'=> $data , 'status' => 200]);
    }
}
