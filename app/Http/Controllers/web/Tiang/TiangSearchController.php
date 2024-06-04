<?php

namespace App\Http\Controllers\web\Tiang;

use App\Http\Controllers\Controller;
use App\Models\Substation;
use App\Models\Tiang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TiangSearchController extends Controller
{
    //

    public function getTiangByPolygon(Request $request)
    {
        
        $spanColumns = [ 
                'abc_span'=>['label'=>'ABC SPAN','keys' =>['s3_185','s3_95','s3_16' ,'s1_16']],
                'pvc_span'=>['label'=>'ABC SPAN','keys' =>['s19_064','s7_083','s7_044']],
                'bare_span'=>['label'=>'ABC SPAN','keys' =>['s7_173','s7_122','s3_132']]
            ];

        try 
        {
            $data = Tiang::whereRaw("ST_Intersects(geom, ST_GeomFromGeoJSON('$request->json'))")
                            ->select('id', 'fp_name', 'tiang_no', 'review_date','total_defects' ,'section_from', 'qa_status' ,'reject_remarks', 'fp_road' , 'ba' ,'pole_image_1','pole_image_2' , 'jenis_tiang' ,'size_tiang','abc_span','pvc_span','bare_span','created_by')
                            ->whereNotNull('pole_image_1')
                            ->where('qa_status', 'pending')
                            ->whereNotNull('review_date')
                            ->orderBy('id')
                            ->get();

                            foreach ($data as $rec) {
                                $from_img = Tiang::where('tiang_no' , $rec->section_from)->select('pole_image_1','pole_image_2')->first();
                                if ($from_img) {
                                    $rec['from_pole_image_1'] = $from_img->pole_image_1;
                                    $rec['from_pole_image_2'] = $from_img->pole_image_2;
                                }
                                foreach ($spanColumns as $key => $value) {
                                    $spanValue = json_decode($rec->{$key});
                                    $span = '';
                        
                                    foreach ($value['keys'] as $spanKey) {
                                        if (isset($spanValue->{$spanKey}) && $spanValue->{$spanKey} != '' ) {
                                            $span .= $value['label'] . ' ' . $spanKey . ' : ' . $spanValue->{$spanKey} . ' , ';
                                        }
                                    }
                        
                                    $rec->{$key} = rtrim($span, ' ,');
                                }
                            }
                       
        } catch (\Throwable $th) {
            return $th->getMessage();
            return response()->json(['data'=>'' ,'status'=> 400]);   
        }
       
        return response()->json(['data'=> $data , 'status' => 200]);
    }


    public function seacrhSubstation($lang , $type, $q)
    {

        $ba = \Illuminate\Support\Facades\Auth::user()->ba;

        $data = Substation::where('ba', 'LIKE', '%' . $ba . '%');
        if ($type == 'substation_name') {
           $data->where('name' , 'LIKE' , '%' . $q . '%')->select('name');
        }else{
            $data->where('id' , 'LIKE' , '%' . $q . '%')->select(DB::raw('id as name'));
        }
        $data = $data->limit(10)->get();

        return response()->json($data, 200);
    }

    public function seacrhSubstationCoordinated($lang , $name, $searchBy)
    {
        // return $searchBy;
        $name = urldecode($name);
        $data = Substation::query();
        if ($searchBy == 'substation_name') {
          $data =  $data->where('name' ,$name );
        }
        if ($searchBy == 'substation_id') {
            $data = $data->where('id' ,$name );
        }
        $data =$data->select( DB::raw('ST_X(geom) as x'),DB::raw('ST_Y(geom) as y'))->first();

        return response()->json($data, 200);
    }
}
