<?php

namespace App\Http\Controllers\web\SavrFFA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SAVRFFARepo;
use Illuminate\Support\Facades\Auth;
use App\Models\SavrFfa;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;


class SavrFfaMapController extends Controller
{
    //

    public function editMap($lang, $id)
    {

        $data = SavrFfa::find($id);

        return $data ?
                view('Savr-ffa.edit-form',
                        [
                            'data' => $data ,
                            'disabled' => false
                        ])
                : abort(404);

    }


    public function editMapStore(Request $request, $language,  $id, SAVRFFARepo $savr_repo)
    {
        try {
            $recored = SavrFfa::find($id);
            $orignal_date=$recored->updated_at;
            if ($recored) {
                $user = Auth::user()->name;
                if ($recored->qa_status != $request->qa_status) {
                    $recored->qa_status = $request->qa_status;
                    $recored->qc_by = $user;
                    $recored->qc_at = now();

                }
                if ($request->qa_status == 'Reject') {
                    $recored->reject_remarks = $request->reject_remakrs;
                } else{
                    $recored->reject_remarks = '';

                }
                $data = $savr_repo->store($recored,$request);
             //   return $data;
                $data->update();
                DB::statement("update tbl_savr_ffa set updated_at='$orignal_date' where id =$recored->id ");


                Session::flash('success', 'Request Success');
                return view('components.map-messages',['id'=>$id,'success'=>true , 'url'=>'savr-ffa']);

            }else{
                Session::flash('failed', 'Request Failed');
            }

        } catch (\Throwable $th) {
             return $th->getMessage();
            Session::flash('failed', 'Request Failed');

        }
        return view('components.map-messages',['id'=>$id,'success'=>false , 'url'=>'savr-ffa']);

    }


    public function seacrh(Request $req)
    {


        $ba = Auth::user()->ba;

        $data = SavrFfa::where('ba', 'LIKE', '%' . $ba . '%');

        if ($req->type == 'ffa_house_no')
        {
            $data
                ->where('house_number' , 'LIKE' , '%' . $req->q . '%')
                ->select('house_number');

        }
        else
        {
            $data
                ->where('id' , 'LIKE' ,  $req->q . '%')
                ->select(\DB::raw('id as house_number'));

        }

        $data = $data
                    ->where('cycle',$req->cycle)
                    ->limit(10)
                    ->get();

        return response()->json($data, 200);
    }

    public function seacrhCoordinated($lang , $name, $searchBy)
    {

        $name = urldecode($name);
        $data = SavrFfa::query();

        if ($searchBy == 'ffa_house_no')
        {
          $data =  $data->where('house_number' ,$name );

        }

        if ($searchBy == 'ffa_id')
        {
            $data = $data->where('id' ,$name );
        }
        // $geomId = $data->pluck('geom_id');

        $geom =  $data->select(
                        \DB::raw('ST_X(geom) as x'),
                        \DB::raw('ST_Y(geom) as y')
                    )->first();


        return response()->json($geom, 200);
    }
}
