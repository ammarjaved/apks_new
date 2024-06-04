<?php

namespace App\Http\Controllers\web\map;

use App\Http\Controllers\Controller;
use App\Models\Road;
use Illuminate\Http\Request;
use App\Models\WorkPackage;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class WPController extends Controller
{
    public function saveWorkPackage(Request $req)
    {
        // $zone = $req->zone;
        // $ba = $req->ba;
        // $name = $req->name;
        // $geom = $req->geom;

        // $sql = "WITH inserted_rows as (INSERT INTO public.tbl_workpackage(
        // package_name, geom, zone, ba,wp_status)
        // VALUES ('$name', st_geomfromgeojson('$geom'), '$zone', '$ba','') RETURNING  id)
        // SELECT row(id) FROM inserted_rows";

        try {
        $data = new WorkPackage();
            $data->zone = $req->zone;
            $data->ba = $req->ba;
            $data->package_name = $req->name;
            $data->geom = DB::raw("st_geomfromgeojson('$req->geom')");
            $data->save();


          //  $data = DB::raw($sql);
            // $getRoads=DB::raw("select *,st_intersection(st_geomfromgeojson('$req->geom'),geom)
            // from road_layer where st_intersects(st_geomfromgeojson('$req->geom'),geom)");

            $add_roads=DB::insert("INSERT INTO public.tbl_roads(
                 road_name, geom, id_workpackage, ba, zone, km)
                select street,geom,'$data->id','$req->ba', '$req->zone',km from road_layer where st_intersects(st_geomfromgeojson('$req->geom'),geom)");


            return  $data;
            // DB::disconnect();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
      //  return redirect('map-1');
    }

    public function selectWP($language,$ba, $zone)
    {
        // return  "select id, package_name ,st_x(st_centroid(geom)) as x  ,st_y(st_centroid(geom)) as y from tbl_workpackage  where ba= '$ba' and zone = '$zone'";
        $wp = DB::select("select id, package_name ,st_x(st_centroid(geom)) as x  ,st_y(st_centroid(geom)) as y ,wp_status from tbl_workpackage  where ba= '$ba' and zone = '$zone' ");

        return response()->json($wp);
    }

    public function getRoadInfo(Request $req)
    {
        $geom = $req->geom;
         
        $result = DB::select("SELECT id, package_name , ba , zone FROM tbl_workpackage WHERE ST_Intersects(geom, ST_GeomFromGeoJSON('$geom'))");
        return response()->json(['data' => $result, 'status' => '200'], 200);
    }

    public function getBaInfo(Request $req)
    {
        $geom = $req->geom;
        $result = DB::select("SELECT ppb_zone, station  FROM ba WHERE ST_Intersects(geom, ST_GeomFromGeoJSON('$geom'))");
        return response()->json([$result[0]], 200);
    }

    public function detail($language,$id)
    {
        $rec = WorkPackage::withCount('diging')->find($id);

        if (!$rec) {
            return abort(404);
        }
        $count = Road::where('id_workpackage', $id)->count();
        $wp = WorkPackage::selectRaw('ST_X(ST_Centroid(geom)) as x')
            ->selectRaw('ST_Y(ST_Centroid(geom)) as y')
            ->where('ba', $rec->ba)
            ->where('zone', $rec->zone)
            ->where('id',$id)
            ->first();

        $road = Road::selectRaw('(ST_Length(geom::geography))/1000 as distance')
            ->where('id_workpackage', $id)
            ->get();


        return $rec != '' ? view('map.show', ['rec' => $rec, 'wp' => $wp, 'distance' => $road->sum('distance') , 'count'=>$count]) : abort(404);
    }
    public function getStats($language,$ba)
    {

        $result = DB::select("SELECT (sum(st_length(geom::geography)))/1000 as distance FROM tbl_roads where ba='$ba'");
        $result1 = DB::select("SELECT count(*)  FROM tbl_third_party_diging_patroling where ba='$ba' and notice='yes'");
        $result2 = DB::select("SELECT count(*)  FROM tbl_third_party_diging_patroling where ba='$ba' and supervision='yes'");


        return response()->json([$result[0], $result1[0], $result2[0]], 200);
    }

    public function removeWP($language,$id)
    {
        try {
            $wp = WorkPackage::find($id);
            if ($wp) {
                $wp->delete();
            }
            return redirect()
                ->back()
                ->with('success', 'Remove records successfully');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('failed', 'try again later');
        }
    }
}
