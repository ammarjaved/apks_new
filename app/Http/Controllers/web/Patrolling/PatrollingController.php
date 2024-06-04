<?php

namespace App\Http\Controllers\web\Patrolling;

use App\Http\Controllers\Controller;
use App\Models\Road;
use App\Models\Team;
use App\Models\WorkPackage;
use App\Models\Patroling;
use App\Traits\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PatrollingController extends Controller
{
    //
    use Filter;

    public function index()
    {
        return view('patrolling.index');
    }

    public function create()
    {
        $team_id = auth()->user()->id_team;
        $team = Team::find($team_id)->team_name;
        $wp = WorkPackage::all();

        return view('patrolling.edit-road', ['team' => $team, 'wp' => $wp]);
    }
    public function updateRoads(Request $request)
    {
        try {
            $currentDate = Carbon::now()->toDateString();
            $time_patrol = $currentDate . ' ' . $request->time_petrol;

            $road = Road::find($request->road_id);
            $road->road_name = $request->road_name;
            $road->date_patrol = $request->date_patrol;
            $road->fidar = $request->fidar;
            $road->name_project = $request->name_project;
            $road->time_patrol = $time_patrol;
            $road->actual_km = $request->actual_km;
            $road->total_digging = $request->total_digging;
            $road->total_notice = $request->total_notice;
            $road->total_supervision = $request->total_supervision;
            $road->update();

            return redirect()->route('get-all-work-packages', app()->getLocale())->with('success', 'Request Success');
        } catch (\Throwable $th) {
            return $th->getMessage();
            return redirect()->route('get-all-work-packages', app()->getLocale())->with('failed', 'Request Failed');
        }
    }

    public function editRoad($language, $id)
    {
        try {
            $road = Road::find($id);
            return $road ? view('patrolling.update-road', ['road' => $road]) : abort(404);
        } catch (\Throwable $th) {
            return redirect('/get-all-work-packages')->with('failed', 'Request Failed');
        }
    }

    public function getRoads($language, $id)
    {
        $roads = Road::where('id_workpackage', $id)->select('id', 'road_name')->get();

        return $roads;
    }

    public function getRoadsByID($language, $id)
    {
        $road = Road::where('id', $id)->select('id', 'road_name', 'ba', 'km', 'date_patrol', 'time_patrol', 'name_project', 'actual_km', 'fidar', 'total_digging', 'total_notice', 'total_supervision')->first();
        $road->time_petrol = date('H:i:s', strtotime($road->time_petrol));
        return $road;
    }

    public function getRoad($language, $id)
    {
        $road = Road::where('id', $id)
            ->with('workPackage')

            ->first();
        $road->time_petrol = date('H:i:s', strtotime($road->time_petrol));
        return view('patrolling.show-road', ['road' => $road]);
    }

    public function paginate(Request $request, $language)
    {
        if ($request->ajax()) {
            $result = Patroling::query();

            $request = $this->filterWithOutAccpet($result, 'vist_date', $request);

            $result
                // ->whereNotNull('km')
                // ->where('km', '!=', '0')
                ->select('*', DB::raw('st_x(geom_start) as start_x'), DB::raw('st_y(geom_start) as start_y'), DB::raw('st_x(geom_end) as end_x'), DB::raw('st_y(geom_end) as end_y'))
                ->orderByDesc('date');

            return Datatables::of($result->get()->makeHidden(['geom']))->make(true);
        }

        return view('patrolling.index');
    }

    public function getGeoJson($landg, $id)
    {
        $query = DB::select("SELECT json_build_object(
                'type', 'FeatureCollection',
                'crs', json_build_object(
                    'type', 'name',
                    'properties', json_build_object('name', 'EPSG:4326')
                ),
                'features', json_agg(json_build_object(
                    'type', 'Feature',
                    'id', id,
                    'geometry', ST_AsGeoJSON(geom)::json,
                    'properties', json_build_object(
                        'id',id

                    )
                ))) AS geojson
            FROM (
                SELECT id, geom
                FROM patroling_lines
                WHERE patroling_id = '$id'
            ) AS tbl1;
             ");

        return response()->json($query, 200);
    }

    public function updateQAStatus(Request $req)
    {
        try {
            $qa_data = Patroling::find($req->id);
            $qa_data->qa_status = $req->status;
            if ($req->status == 'Reject') {
                $qa_data->reject_remarks = $req->reject_remakrs;
            }
            $qa_data->update();

            return response()->json(['status' => $req->status]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'Request failed']);
        }
    }
}
