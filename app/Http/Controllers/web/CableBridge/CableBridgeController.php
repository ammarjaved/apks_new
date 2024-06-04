<?php

namespace App\Http\Controllers\web\CableBridge;

use App\Http\Controllers\Controller;
use App\Models\CableBridge;
use App\Models\Team;
use App\Repositories\CableBridgeRepo;
use App\Traits\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class CableBridgeController extends Controller
{
    use Filter;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        if ($request->ajax()) {
            $ba = $request->filled('ba') ? $request->ba : Auth::user()->ba;
            $result = CableBridge::query();

           $result = $this->filter($result , 'visit_date',$request);

            $result->when(true, function ($query) {
                return $query->select('id', 'ba', 'zone', 'team', 'visit_date', 'total_defects', 'qa_status','qa_status' , 'reject_remarks');
            });

            return datatables()
                ->of($result->get())
                ->addColumn('cable_bridge_id', function ($row) {
                    return 'CB-' . $row->id;
                })
                // ->addColumn('qa_status_action', function ($row) {

                //     if ($row->visit_date != '' && $row->cable_bridge_image_1 != '') {
                //         return "SDfsdfsd";
                //         if ($row->qa_status === 'Accept' || $row->qa_status === 'Reject') {
                //             if ($row->qa_status == 'Accept') {
                //                 return "<span class='badge bg-success'>Accept</span>";
                //             }
                //             return "<span class='badge bg-danger'>Reject</span>";
                //         } else {
                //             return "<div class='d-flex text-center' id='status-$row->id'>
                //                         <a type='button' class='btn btn-sm btn-success' onclick='updateQaStatus('Accept',$row->id)'>Accept</a>/
                //                         <a type='button' class='btn btn-sm btn-danger ' onclick='updateQaStatus('Reject',$row->id)'> Reject </a>
                //                     </div>";
                //         }
                //     }
                //     return '';
                // })
                ->make(true);
        }
        return view('cable-bridge.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $team_id = auth()->user()->id_team;
        $team = Team::find($team_id)->team_name;
        return view('cable-bridge.create', ['team' => $team]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , CableBridgeRepo $cableBridge)
    {
        try
        {
            $data = new CableBridge();
            $data->coords = $request->coordinate;
            $user = Auth::user()->name;

            $data->created_by = $user;
            $data->team = $request->team;

            $data->qa_status = 'pending';
            $data->geom = DB::raw("ST_GeomFromText('POINT(" . $request->log . ' ' . $request->lat . ")',4326)");
            $cableBridge->store($data,$request);
            $data->save();
            Session::flash('success', 'Request Success');
        }
        catch (\Throwable $th)
        {   return $th->getMessage();
            Session::flash('failed', 'Request Failed');
        }
        return redirect()->route('cable-bridge.index', app()->getLocale());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($language, $id)
    {
        //
        $data = CableBridge::find($id);
        return $data ? view('cable-bridge.show', ['data' => $data, 'disabled' => true]) : abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($language, $id)
    {
        //
        $data = CableBridge::find($id);
        return $data ? view('cable-bridge.edit', ['data' => $data, 'disabled' => false]) : abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $language, $id , CableBridgeRepo $cableBridge)
    {
        try
        {
            $data = CableBridge::find($id);
            $user = Auth::user()->name;

            // $data->updated_by = $user;
            $cableBridge->store($data,$request);
            $data->update();
            Session::flash('success', 'Request Success');
        }
        catch (\Throwable $th)
        {
            Session::flash('failed', 'Request Failed');
        }
        return redirect()->route('cable-bridge.index', app()->getLocale());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($language, $id)
    {
        try
        {
            CableBridge::find($id)->delete();
            Session::flash('success', 'Request Success');
        }
        catch (\Throwable $th)
        {
            Session::flash('failed', 'Request Failed');
        }
        return redirect()->route('feeder-pillar.index', app()->getLocale());
    }


    public function destroyCableBridge($language, $id)
    {
        try {
            CableBridge::find($id)->delete();
        }
        catch (\Throwable $th)
        {
            return response()->json(['success'=>false],400);
        }
    }


    public function updateQAStatus(Request $req)
    {
        try {
            $qa_data = CableBridge::find($req->id);
            $qa_data->qa_status = $req->status;
            if ($req->status == 'Reject') {
                $qa_data->reject_remarks = $req->reject_remakrs;
            }
            $user = Auth::user()->name;
            $qa_data->qc_by = $user;
            $qa_data->qc_at = now();

            $qa_data->update();

            // return redirect()->back();
        } catch (\Throwable $th) {
            return response()->json(['status' => 'Request failed']);
        }
        if ($req->ajax()) {
            return response()->json(['message'=>'Update Successfully','status' =>200]);
        }
        return redirect()->back();
    }
}
