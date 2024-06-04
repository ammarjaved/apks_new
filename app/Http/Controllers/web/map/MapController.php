<?php

namespace App\Http\Controllers\web\map;

use App\Http\Controllers\Controller;
use App\Models\Road;
use App\Models\WorkPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    //

    public function index()
    {
        $wp = DB::table('tbl_workpackage')
            ->select('id', 'package_name')
            ->get();

        //    return response()->json($wp);
        return view('map.index', ['wps' => $wp]);
    }

    public function allWP()
    {
        // return WorkPackage::all();
        $ba = Auth::user()->ba;
        $datas = WorkPackage::where('ba', $ba)->get();

        $roads = [];

        return view('map.detail', ['datas' => $datas, 'roads' => $roads]);
    }

    public function getRoadsDetails($wpID)
    {
        return Road::where('id_workpackage', $wpID)
            ->select('id', 'road_name', 'km', 'actual_km', 'fidar')
            ->get();
    }

    public function proxy($language, $url)
    {
        $result = file_get_contents(rawurldecode($url));
        return response()->json($result);
    }

    public function preNext($language,$id, $status){
        try {
            if($status=='next'){
            $idn=$id+1;
            $data = DB::select("select gid, photo, filename, directory, altitude, direction, 
            longitude, latitude, st_asgeojson(geom) as geom from pano_apks where gid=$idn order by gid limit 1;");
            }else{
                $idn=$id-1;
                $data = DB::select("select gid, photo, filename, directory, altitude, direction, 
                longitude, latitude, st_asgeojson(geom) as geom from pano_apks where gid=$idn order by gid limit 1;");
            }

            if (count($data) > 0) {
                // Return the record with the shortest distance
                return response()->json(['Success' => true, $data[0]], 200);
            } else {
                // Handle the case where no matching records were found
                return response()->json(['Success' => false, 'error' => 'No matching records found'], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['Success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    
    public function teswtpagination(Request $request, $language, $id, $status)
    {
        if ($status == 'Patroled') {
            $roads = Road::where('id_workpackage', $id)
                ->where('actual_km', '!=', null)
                ->select('id', 'road_name', 'actual_km', 'km')
                ->paginate(10);
        } else {
            $roads = Road::where('id_workpackage', $id)
                ->where('actual_km', null)
                ->select('id', 'road_name', 'actual_km', 'km')
                ->paginate(10);
        }

        return view('map.pagination.roads-pagination', ['roads' => $roads])->render();
    }
}
