<?php

namespace App\Http\Controllers\web\FeederPillar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeederPillar;
use App\Models\Team;
use App\Traits\Filter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Repositories\FeederPillarRepo;

class FPController extends Controller
{

    use Filter;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $result = FeederPillar::query();
            $result = $this->filter($result,'visit_date',$request);
            $result->when(true, function ($query)
            {
                return $query->select(
                    'id',
                    'ba',
                    'visit_date',
                    DB::raw("CASE WHEN (gate_status->>'unlocked')::text='true' THEN 'Yes' ELSE 'No' END as unlocked"),
                    DB::raw("CASE WHEN (gate_status->>'demaged')::text='true' THEN 'Yes' ELSE 'No' END as demaged"),
                    DB::raw("CASE WHEN (gate_status->>'other')::text='true' THEN 'Yes' ELSE 'No' END as other_gate"),
                    'vandalism_status',
                    'leaning_staus',
                    'rust_status',
                    'advertise_poster_status',
                    'total_defects',
                    'qa_status',
                    'qa_status' , 'reject_remarks',
                );
            });

            return datatables()->of($result->get())->addColumn('feeder_pillar_id', function ($row)
            {
                return "FP-" .$row->id;
            })->make(true);
        }
        return view('feeder-pillar.index');
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
        return view('feeder-pillar.create', ['team' => $team]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , FeederPillarRepo $feederPillar)
    {
        try
        {
            $data = new FeederPillar();
            $data->coordinate = $request->coordinate;
            $user = Auth::user()->name;
            $data->team = $request->team;
            $data->created_by = $user;
            $data->qa_status = 'pending';
            $data->geom = DB::raw("ST_GeomFromText('POINT(" . $request->log . ' ' . $request->lat . ")',4326)");
            $feederPillar->store($data,$request);
            $data->save();
            Session::flash('success', 'Request Success');
        }
        catch (\Throwable $th)
        {
            return $th->getMessage();
            Session::flash('failed', 'Request Failed');
        }
        return redirect()->route('feeder-pillar.index', app()->getLocale());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($language,$id)
    {
        $data = FeederPillar::find($id);
        if ($data)
        {
            $data->gate_status = json_decode($data->gate_status);
            return view('feeder-pillar.show', ['data' => $data ,'disabled'=>true]);
        }
        return abort('404');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($language,$id)
    {
        $data = FeederPillar::find($id);
        if ($data)
        {
            $data->gate_status = json_decode($data->gate_status);
            return view('feeder-pillar.edit', ['data' => $data , 'disabled'=>false]);
        }
        return abort('404');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$language,$id , FeederPillarRepo $feederPillar)
    {
        try
        {
            $data = FeederPillar::find($id);
            $user = Auth::user()->name;
            // $data->updated_by = $user;
            $feederPillar->store($data,$request);
            $data->update();
            Session::flash('success', 'Request Success');
        }
        catch (\Throwable $th)
        {
            Session::flash('failed', 'Request Failed');
        }
        return redirect()->route('feeder-pillar.index', app()->getLocale());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($language,$id)
    {
        try
        {
            FeederPillar::find($id)->delete();
            Session::flash('success', 'Request Success');
        }
        catch (\Throwable $th)
        {
            Session::flash('failed', 'Request Failed');
        }
        return redirect()->route('feeder-pillar.index', app()->getLocale());
    }



    public function destroyFeederPillar($language, $id)
    {
        try {
            FeederPillar::find($id)->delete();
            return response()->json(['success'=>true],200);
        }
        catch (\Throwable $th)
        {
            return response()->json(['success'=>false],400);
        }
    }


    public function updateQAStatus(Request $req)
    {
        try
        {
            $qa_data = FeederPillar::find($req->id);
            $qa_data->qa_status = $req->status;
            if ($req->status == 'Reject')
            {
                $qa_data->reject_remarks = $req->reject_remakrs;
            }
            $user = Auth::user()->name;

            $qa_data->qc_by = $user;
            $qa_data->qc_at = now();

            $qa_data->update();

        }
        catch (\Throwable $th)
        {
            return response()->json(['status' => 'Request failed']);
        }

        if ($req->ajax()) {
            return response()->json(['message'=>'Update Successfully','status' =>200]);
        }
        return redirect()->back();
    }
}
