<?php

namespace App\Http\Controllers\web\SavrFFA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavrFfaSearchController extends Controller
{
    public function getSavrFFAByPolygon(Request $request)
    {
 
        try
        {

            $data = DB::table('tbl_savr_ffa')
                    ->whereRaw("ST_Intersects(geom, ST_GeomFromGeoJSON(?))", [$request->json])
                    ->where('qa_status', 'pending')
                    ->where('cycle',$request->cycle)
                    ->orderBy('id')
                    ->get();


        } catch (\Throwable $th) {
            // return $th->getMessage();
            return response()->json(['data'=>'' ,'status'=> 400]);
        }
        return response()->json(['data'=> $data , 'status' => 200]);
    }
}
