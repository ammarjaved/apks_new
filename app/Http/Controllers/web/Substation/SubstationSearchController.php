<?php

namespace App\Http\Controllers\web\Substation;

use App\Http\Controllers\Controller;
use App\Models\Substation;
use Illuminate\Http\Request;

class SubstationSearchController extends Controller
{
    //

    public function getSubstationByPolygon(Request $request)
    {
        

        try 
        {
            $data = Substation::whereRaw("ST_Intersects(geom, ST_GeomFromGeoJSON('$request->json'))")
                            ->select('id', 'fl', 'name', 'type','voltage' ,'substation_image_1', 'substation_image_2' ,'reject_remarks', 'qa_status','created_by','total_defects', 'visit_date')
                            ->whereNotNull('substation_image_1')
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
