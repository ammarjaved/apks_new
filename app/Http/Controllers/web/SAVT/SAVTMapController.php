<?php

namespace App\Http\Controllers\web\SAVT;

use App\Http\Controllers\Controller;
use App\Models\SAVT;
use App\Repositories\SAVTRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SAVTMapController extends Controller
{
    public function editMap($lang, $id)
    {
        $data = SAVT::find($id);
        if ($data) {
            return $data ? view('SAVT.edit-form', ['data' => $data, 'disabled' => false]) : abort(404);
        }
        return abort('404');
    }


    public function update(Request $request, $language, $id, SAVTRepo $sAVTRepo)
    {
        try {
            $data = SAVT::find($id);
            if (!$data) {
                return abort(404);
            }

            $user = Auth::user()->name;
            // $data->updated_by = $user;
            if ($data->qa_status != $request->qa_status) {
                $data->qa_status = $request->qa_status;
                $data->qc_by = $user;
                $data->qc_at = now();
            }
            if ($request->qa_status == 'Reject') {
                $data->reject_remarks = $request->reject_remakrs;
            } else{
                $data->reject_remarks = '';

            }
            $res =  $sAVTRepo->prepareData($data , $request);

            $res->update();

            return view('components.map-messages', ['id' => $id, 'success' => true, 'url' => 'savt'])->with('success', 'Form Update');
        } catch (\Throwable $th) {
            // return $th->getMessage();
            return view('components.map-messages', ['id' => $id, 'success' => false, 'url' => 'savt'])->with('failed', 'Form Update Failed');
        }
    }


    public function seacrh(Request $req)
    {

        $ba = \Illuminate\Support\Facades\Auth::user()->ba;

        $data = SAVT::where('ba', 'LIKE', '%' . $ba . '%')
                ->where('id' , 'LIKE' , '%' . $req->q . '%')
                ->select('id');

        $data = $data->limit(10)->get();

        return response()->json($data, 200);
    }

    public function seacrhCoordinated($lang , $name)
    {
        // return $searchBy;
        $name = urldecode($name);
        $data = SAVT::where('id' ,$name )
                    ->pluck('geom_id')
                    ->first();


        $geom =DB::table('tbl_savt_geom')
                    ->where('id',$data)
                    ->select(
                        \DB::raw('ST_X(geom) as x'),
                        \DB::raw('ST_Y(geom) as y')
                    )->first();

        return response()->json($geom, 200);
    }

}
