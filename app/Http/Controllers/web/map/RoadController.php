<?php

namespace App\Http\Controllers\web\map;

use App\Http\Controllers\Controller;
use App\Models\Road;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoadController extends Controller
{
    public function saveRoad(Request $req)
    {
        // return $req;
        $name = $req->road_name;
        $wp = $req->id_wp;
        $geom = $req->geom;

        $sql = "INSERT INTO public.tbl_roads(
          road_name, geom, id_workpackage , ba , zone)
          VALUES ('$name', st_geomfromgeojson('$geom'), '$wp' , '$req->ba', '$req->zone'); ";
        try {
            $data = DB::insert($sql);
        } catch (\Throwable $th) {
            return redirect('map-1');
            return $th->getMessage();
        }
        return $data;
    }

    public function removeRoad($id)
    {
        try {
            $wp = Road::find($id);
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

    public function getRoadName($language,$lat, $lng)
    {
        try {
            $data = DB::select("SELECT road_name,
                ST_Distance(
                    ST_Transform(ST_GeomFromText('POINT($lng $lat)', 4326), 32647),
                    ST_Transform(geom, 32647)
                ) AS distance
            FROM tbl_roads
            WHERE ST_Intersects(
                ST_Buffer(
                    ST_Transform(ST_GeomFromText('POINT($lng $lat)', 4326), 32647),
                    100, 2
                ),
                ST_Transform(geom, 32647)
            )
            ORDER BY distance
            LIMIT 1;");

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
}
