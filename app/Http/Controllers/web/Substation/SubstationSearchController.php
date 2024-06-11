<?php

namespace App\Http\Controllers\web\Substation;

use App\Http\Controllers\Controller;
use App\Models\Substation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SubstationSearchController extends Controller
{
    //

    public function getSubstationByPolygon(Request $request)
    {


        try
        {

            $data = DB::table('tbl_substation_geom')
                    ->join('tbl_substation', 'tbl_substation_geom.id', '=', 'tbl_substation.geom_id')
                    ->whereRaw("ST_Intersects(tbl_substation_geom.geom, ST_GeomFromGeoJSON(?))", [$request->json])
                    ->where('qa_status', 'pending')
                    ->whereNotNull('substation_image_1')
                    ->whereNotNull('visit_date')
                    ->where('cycle',$request->cycle)
                    ->select(
                            'tbl_substation.id',
                            'tbl_substation.fl',
                            'tbl_substation.name',
                            'tbl_substation.type',
                            'tbl_substation.voltage',
                            'tbl_substation.substation_image_1',
                            'tbl_substation.substation_image_2',
                            'tbl_substation.reject_remarks',
                            'tbl_substation.qa_status',
                            'tbl_substation.created_by',
                            'tbl_substation.total_defects',
                            'tbl_substation.visit_date'
                            )
                    ->orderBy('id')
                    ->get();

            // $data = Substation::whereRaw("ST_Intersects(geom, ST_GeomFromGeoJSON('$request->json'))")
            //                 ->select('id', 'fl', 'name', 'type','voltage' ,'substation_image_1', 'substation_image_2' ,'reject_remarks', 'qa_status','created_by','total_defects', 'visit_date')
            //                 ->whereNotNull('substation_image_1')
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
