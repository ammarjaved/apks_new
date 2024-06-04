<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ThirdPartyDiging;
use App\Models\Team;
use App\Models\WorkPackage;
use App\Traits\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ThirdPartyDiggingController extends Controller
{
    use Filter;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
 

        if ($request->ajax()) {

  
            $result = ThirdPartyDiging::query();

            $this->filter($result , 'survey_date' , $request);

            $result->when(true, function ($query) {
                return $query->select('wp_name', 'qa_status' , 'reject_remarks', 'zone', 'ba', 'survey_date', 'id', 'patrolling_time', 'supervision', 'notice', 'survey_status', 'digging' ,'qa_status');
            });

            return datatables()
                ->of($result->get())
                ->make(true);
        }

        return view('third-party.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ba = Auth::user()->ba;
        $sql = DB::select("SELECT ppb_zone FROM ba where station = '$ba'");

        $team_id = auth()->user()->id_team;
        $team = Team::find($team_id)->team_name;
        $wp = WorkPackage::all();

        return view('third-party.create', ['team' => $team, 'wp' => $wp]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $currentDate = Carbon::now()->toDateString();
            $combinedDateTime = $currentDate . ' ' . $request->patrolling_time;

            $data = new ThirdPartyDiging();
            $data->wp_name = $request->wp_name;
            $data->zone = $request->zone;
            $data->ba = $request->ba;
            $data->team_name = $request->team_name;
            $data->survey_date = $request->survey_date;
            $data->patrolling_time = $combinedDateTime;
            $data->qa_status = 'pending';
            // $data->project_name = $request->project_name;
            $data->road_name = $request->road_name;

            // $data->km_actual = $request->km_actual;
        $user = Auth::user()->id;

            $data->created_by = $user;
            $data->digging = $request->digging;
            $data->notice = $request->notice;
            $data->supervision = $request->supervision;
            $data->company_name = $request->company_name;
            $data->main_contractor = $request->main_contractor;
            $data->office_phone_no = $request->office_phone_no;
            $data->developer_phone_no = $request->developer_phone_no;
            $data->contractor_company_name = $request->contractor_company_name;
            $data->site_supervisor_name = $request->site_supervisor_name;
            $data->site_supervisor_phone_no = $request->site_supervisor_phone_no;
            $data->excavator_operator_name = $request->excavator_operator_name;

            $data->excavator_machinery_reg_no = $request->excavator_machinery_reg_no;
            $data->workpackage_id = $request->workpackage_id;
            $data->department_diging = $request->department_diging;
            $data->survey_status = $request->survey_status;

            $destinationPath = 'assets/images/third-party-digging/';

            foreach ($request->all() as $key => $file) {
                // Check if the input is a file and it is valid
                if ($request->hasFile($key) && $request->file($key)->isValid()) {
                    $uploadedFile = $request->file($key);
                    $img_ext = $uploadedFile->getClientOriginalExtension();
                    $filename = $key . '-' . strtotime(now()). $user . '.' . $img_ext;
                    $uploadedFile->move($destinationPath, $filename);
                    $data->{$key} = $destinationPath . $filename;
                }
            }

            $data->geom = DB::raw("ST_GeomFromText('POINT(" . $request->log . ' ' . $request->lat . ")',4326)");

            $data->save();

            return redirect()
                ->route('third-party-digging.index', app()->getLocale())
                ->with('success', 'Form Intserted');
        } catch (\Throwable $th) {
            return $th->getMessage();
            return redirect()
                ->route('third-party-digging.index', app()->getLocale())
                ->with('failed', 'Form Intserted Failed');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($language, $id)
    {
        $data = ThirdPartyDiging::find($id);
        if ($data) {
            return view('third-party.show', ['data' => $data]);
        }
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($language, $id)
    {
        $wp = WorkPackage::all();
        $data = ThirdPartyDiging::find($id);

        return view('third-party.edit', ['data' => $data, 'wp' => $wp]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $language, $id)
    {
        try {
            $currentDate = Carbon::now()->toDateString();
            $combinedDateTime = $currentDate . ' ' . $request->patrolling_time;

            $data = ThirdPartyDiging::find($id);
            $data->wp_name = $request->wp_name;
            $data->zone = $request->zone;
            $data->ba = $request->ba;
            $data->survey_date = $request->survey_date;
            $data->patrolling_time = $combinedDateTime;
            // $data->project_name = $request->project_name;
            $data->feeder_involved = $request->feeder_involved;

            // $data->km_actual = $request->km_actual;
            $data->road_name = $request->road_name;
            $user = Auth::user()->id;

            $data->updated_by = $user;
            if ($data->qa_status == '') {
                $data->qa_status = 'pending';
            }
            $data->digging = $request->digging;
            $data->notice = $request->notice;
            $data->supervision = $request->supervision;
            $data->company_name = $request->company_name;
            $data->main_contractor = $request->main_contractor;

            $data->developer_phone_no = $request->developer_phone_no;
            $data->contractor_company_name = $request->contractor_company_name;
            $data->site_supervisor_name = $request->site_supervisor_name;
            $data->site_supervisor_phone_no = $request->site_supervisor_phone_no;
            $data->excavator_operator_name = $request->excavator_operator_name;

            $data->excavator_machinery_reg_no = $request->excavator_machinery_reg_no;
            $data->workpackage_id = $request->workpackage_id;
            $data->department_diging = $request->department_diging;
            $data->survey_status = $request->survey_status;

            $destinationPath = 'assets/images/third-party-digging/';

            foreach ($request->all() as $key => $file) {
                // Check if the input is a file and it is valid
                if ($request->hasFile($key) && $request->file($key)->isValid()) {
                    $uploadedFile = $request->file($key);
                    $img_ext = $uploadedFile->getClientOriginalExtension();
                    $filename = $key . '-' . strtotime(now()). $data->id . '.' . $img_ext;
                    $uploadedFile->move($destinationPath, $filename);
                    $data->{$key} = $destinationPath . $filename;
                }
            }

            $data->update();

            return redirect()
                ->route('third-party-digging.index', app()->getLocale())
                ->with('success', 'Form Update');
        } catch (\Throwable $th) {
            return $th->getMessage();
            return redirect()
                ->route('third-party-digging.index', app()->getLocale())
                ->with('failed', 'Form Intserted Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($language, $id)
    {
        try {
            ThirdPartyDiging::find($id)->delete();

            return redirect()
                ->route('third-party-digging.index', app()->getLocale())
                ->with('success', 'Recored Removed');
        } catch (\Throwable $th) {
            // return $th->getMessage();
            return redirect()
                ->route('third-party-digging.index', app()->getLocale())
                ->with('failed', 'Request Failed');
        }
    }


    public function updateQAStatus(Request $req)
    {
        try {
            $qa_data = ThirdPartyDiging::find($req->id);
            $qa_data->qa_status = $req->status;
            if ($req->status == 'Reject') {
                $qa_data->reject_remarks = $req->reject_remakrs;
            }
            $qa_data->update();

            return redirect()->back();
        } catch (\Throwable $th) {
            return response()->json(['status' => 'Request failed']);
        }
    }
}
