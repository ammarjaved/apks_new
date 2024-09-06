<?php

namespace App\Http\Controllers\web\SavrFFA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SavrFfa;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SAVRFFARepo;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\Filter;
use Illuminate\Support\Facades\DB;


class SavrFfaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use Filter;
    public function index(Request $request)
    {

        if ($request->ajax()) {
            // if($request->filled('ba')){
            // $ba = $request->filled('ba') ? $request->ba : Auth::user()->ba;
            // $result = SavrFfa::where('ba',$ba);
            // }else{
                 $result = SavrFfa::query();
            // }
            $result = $this->filter($result , 'updated_at' , $request);

            $result->when(true, function ($query) {
                return $query->select(
                                'id',
                                'ba',
                                'qa_status',
                                'pole_id',
                                'pole_no',
                                'reject_remarks',
                                'updated_at'
                            );
            });

            // return datatables()
            //     ->of($result->get())->addColumn('ffa_id', function ($row) {

            //         return "FFA-" .$row->id;
            //     })
            //     ->make(true);

                return DataTables::eloquent($result)
                ->addColumn('ffa_id', function ($row) {
                    return "FFA-" . $row->id;
                })
                ->make(true);

        }

        return view('savr-ffa.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('Savr-ffa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($lang, $id)
    {
        $data = SavrFfa::find($id);
        if ($data)
        {
            return view("Savr-ffa.show",['data'=>$data, 'disabled' => true]);
        }
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang, $id)
    {
        $data = SavrFfa::find($id);
        if ($data)
        {
            return view("Savr-ffa.edit",['data'=>$data, 'disabled' => false]);
        }
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lang, $id, SAVRFFARepo $savr_repo)
    {
        try
        {
            $data = SavrFfa::find($id);
            $orignal_date=$data->updated_at;
            $savr_repo->store($data,$request);
            $data->update();
            DB::statement("update tbl_savr_ffa set updated_at='$orignal_date' where id =$data->id ");

            Session::flash('success', 'Request Success');
        }
        catch (\Throwable $th)
        {
            Session::flash('failed', 'Request Failed');
        }
        return redirect()->route('savr-ffa.index', app()->getLocale());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang, $id)
    {
        try
        {
            SavrFfa::find($id)->delete();
            Session::flash('success', 'Request Success');
        }
        catch (\Throwable $th)
        {
            Session::flash('failed', 'Request Failed');
        }
        return redirect()->route('savr-ffa.index', app()->getLocale());
    }


    public function destroySavrFFA($language, $id)
    {
        try {
            SavrFfa::find($id)->delete();
            return response()->json(['success'=>true],200);
        }
        catch (\Throwable $th)
        {
            return response()->json(['success'=>false],400);
        }
    }


    public function updateQAStatus(Request $req)
    {
        try {
            $qa_data = SavrFfa::find($req->id);
          //  return   $qa_data;
            $orignal_date=$qa_data->updated_at;
            $rec_id=$qa_data->id;
            $qa_data->qa_status = $req->status;
            if ($req->status == 'Reject') {
                $qa_data->reject_remarks = $req->reject_remakrs;
            }
            $user = Auth::user()->name;
            $qa_data->qc_by = $user;
            $qa_data->qc_at = now();

            $qa_data->update();
            DB::statement("update tbl_savr_ffa set updated_at='$orignal_date' where id = $rec_id ");

            // return redirect()->back();
        } catch (\Throwable $th) {
            // return $th->getMessage();
            return response()->json(['status' => 'Request failed']);
        }
        if ($req->ajax()) {
            return response()->json(['message'=>'Update Successfully','status' =>200]);
        }
        return redirect()->back();
    }
}
