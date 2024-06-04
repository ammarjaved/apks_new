<?php

namespace App\Http\Controllers\web\SAVT;

use App\Http\Controllers\Controller;
use App\Models\SAVT;
use App\Repositories\SAVTRepo;
use App\Traits\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Traits\RemoveImages;


class SAVTController extends Controller
{
    use Filter;
    use RemoveImages;

    private $savtRepository;

    public function __construct(SAVTRepo $savtRepository)
    {
        $this->savtRepository = $savtRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $ba = $request->filled('ba') ? $request->ba : Auth::user()->ba;
            $result = SAVT::query();

           $result = $this->filter($result , 'visit_date' , $request);

            $result->when(true, function ($query) {
                return $query->select('id', 'ba' ,'qa_status' , 'reject_remarks', 'visit_date',  'total_defects' );
            });

            return datatables()
                ->of($result->get())->addColumn('savt_id', function ($row) {

                    return "SAVT-" .$row->id;
                })
                ->make(true);
        }

        return view('SAVT.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('SAVT.create');

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
        try {

            $create = $this->savtRepository->store($request);
            $data = $this->savtRepository->prepareData($create , $request);
            $data->save();
            // return $request;

            Session::flash('success', 'Request Success');
        } catch (\Throwable $th) {
            return $th->getMessage();
            Session::flash('failed', 'Request Failed');
        }
        return redirect()->route('savt.index', app()->getLocale());

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($lang, $id)
    {
        //
        $data = SAVT::find($id);
        if ($data)
        {
            return view("SAVT.show",['data'=>$data, 'disabled' => true]);
        }
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang , $id)
    {
        //
        // return $id;
        $data = SAVT::find($id);
        if ($data)
        {
            return view("SAVT.edit",['data'=>$data, 'disabled' => false]);
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
    public function update(Request $request, $lang, $id)
    {
        try {
            $recored = SAVT::find($id);
            if ($recored) {
                $user = Auth::user()->name;
                // $recored->updated_by = $user;
                $data = $this->savtRepository->prepareData($recored , $request);
                $data->update();

                Session::flash('success', 'Request Success');
            }else{
                Session::flash('failed', 'Request Failed');
            }

        } catch (\Throwable $th) {
            Session::flash('failed', 'Request Failed');
        }
        return redirect()->route('savt.index', app()->getLocale());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang , $id)
    {
        try {
            $rec = SAVT::find($id);
            if ($rec) {

                $imagesArray = \App\Constants\SAVTConstants::SAVT_IMAGES;
                $this->removeImages($imagesArray , $rec);

                $rec->delete();

            }

            Session::flash('success', 'Request Success');

        } catch (\Throwable $th) {
            // return $th->getMessage();
            Session::flash('failed', 'Request Failed');

        }
        return redirect()->route('savt.index', app()->getLocale());

    }


    public function destroySAVT ($language, $id)
    {
        try {
            $rec = SAVT::find($id);
            if ($rec) {

                $imagesArray = \App\Constants\SAVTConstants::SAVT_IMAGES;
                $this->removeImages($imagesArray , $rec);

                $rec->delete();

            }
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
            $qa_data = SAVT::find($req->id);
            $qa_data->qa_status = $req->status;
            if ($req->status == 'Reject') {
                $qa_data->reject_remarks = $req->reject_remakrs;
            }
            $user = Auth::user()->name;
            $qa_data->qc_by = $user;
            $qa_data->update();

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
