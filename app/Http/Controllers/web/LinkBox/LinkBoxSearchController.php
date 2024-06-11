<?php

namespace App\Http\Controllers\web\LinkBox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LinkBox;
use Illuminate\Support\Facades\DB;


class LinkBoxSearchController extends Controller
{
    //

    public function getLinkBoxByPolygon(Request $request)
    {

        try
        {

            $data = DB::table('tbl_link_box_geom')
                    ->join('tbl_link_box', 'tbl_link_box_geom.id', '=', 'tbl_link_box.geom_id')
                    ->whereRaw("ST_Intersects(tbl_link_box_geom.geom, ST_GeomFromGeoJSON(?))", [$request->json])
                    // ->where('qa_status', 'pending')
                    // ->whereNotNull('link_box_image_1')
                    // ->whereNotNull('visit_date')
                    ->where('cycle',$request->cycle)
                    ->select(
                        'tbl_link_box.id',
                        'tbl_link_box.ba',
                        'tbl_link_box.end_date',
                        'tbl_link_box.start_date',
                        'tbl_link_box.link_box_image_1',
                        'tbl_link_box.link_box_image_2',
                        'tbl_link_box.reject_remarks',
                        'tbl_link_box.qa_status',
                        'tbl_link_box.created_by',
                        'tbl_link_box.total_defects',
                        'tbl_link_box.visit_date'
                        )
                    ->orderBy('id')
                    ->get();
            // $data = LinkBox::whereRaw("ST_Intersects(geom, ST_GeomFromGeoJSON('$request->json'))")
            //                 ->select('id', 'ba', 'end_date', 'start_date'  ,'link_box_image_1', 'link_box_image_2' ,'reject_remarks', 'qa_status','created_by','total_defects', 'visit_date')
            //                 ->whereNotNull('link_box_image_1')
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
