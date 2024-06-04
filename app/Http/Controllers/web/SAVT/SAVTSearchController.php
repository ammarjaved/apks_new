<?php

namespace App\Http\Controllers\web\SAVT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SAVT;

class SAVTSearchController extends Controller
{
    public function getSAVTByPolygon(Request $request)
    {


        try
        {
            $data = SAVT::whereRaw("ST_Intersects(geom, ST_GeomFromGeoJSON('$request->json'))")
                            ->select('id', 'supplier_pmu_ppu', 'supplier_feeder_no', 'road_name','total_defects' ,'voltan_kv', 'qa_status' ,'reject_remarks',  'ba' ,'savt_image_1','savt_image_2' ,'created_by')
                            ->whereNotNull('savt_image_1')
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


    public function seacrhSubstation($lang , $type, $q)
    {

        // $ba = \Illuminate\Support\Facades\Auth::user()->ba;

        // $data = Substation::where('ba', 'LIKE', '%' . $ba . '%');
        // if ($type == 'substation_name') {
        //    $data->where('name' , 'LIKE' , '%' . $q . '%')->select('name');
        // }else{
        //     $data->where('id' , 'LIKE' , '%' . $q . '%')->select(DB::raw('id as name'));
        // }
        // $data = $data->limit(10)->get();

        // return response()->json($data, 200);
    }

    public function seacrhSubstationCoordinated($lang , $name, $searchBy)
    {
        // return $searchBy;
        // $name = urldecode($name);
        // $data = Substation::query();
        // if ($searchBy == 'substation_name') {
        //   $data =  $data->where('name' ,$name );
        // }
        // if ($searchBy == 'substation_id') {
        //     $data = $data->where('id' ,$name );
        // }
        // $data =$data->select( DB::raw('ST_X(geom) as x'),DB::raw('ST_Y(geom) as y'))->first();

        // return response()->json($data, 200);
    }
}
