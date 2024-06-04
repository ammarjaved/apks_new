<?php

namespace App\Http\Controllers\web\LinkBox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LinkBox;


class LinkBoxSearchController extends Controller
{
    //

    public function getLinkBoxByPolygon(Request $request)
    {

        try
        {
            $data = LinkBox::whereRaw("ST_Intersects(geom, ST_GeomFromGeoJSON('$request->json'))")
                            ->select('id', 'ba', 'end_date', 'start_date'  ,'link_box_image_1', 'link_box_image_2' ,'reject_remarks', 'qa_status','created_by','total_defects', 'visit_date')
                            ->whereNotNull('link_box_image_1')
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
