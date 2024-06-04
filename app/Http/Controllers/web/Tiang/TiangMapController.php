<?php

namespace App\Http\Controllers\web\Tiang;

use App\Http\Controllers\Controller;
use App\Models\Tiang;
use Illuminate\Http\Request;
use App\Repositories\TiangRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class TiangMapController extends Controller
{
    //
    private $tiangRepository;

    public function __construct(TiangRepository $tiaRepository)
    {
        $this->tiangRepository = $tiaRepository;
    }
    public function editMap($lang, $id)
    {
        // return $id;
        $data = $this->tiangRepository->getRecoreds($id);
        $fromPoleImage1 = '';
        $fromPoleImage2 = '';

        if ($data && $data->section_from != '') {
           $from= Tiang::where('tiang_no' , $data->section_from)->first();
            if ($from) {
                $fromPoleImage1 = $from->pole_image_1;
                $fromPoleImage2 = $from->pole_image_2;
            }
        }
        // return $data->jenis_tiang;

        return $data ? view('Tiang.edit-form', ['data' => $data , 'fromPoleImage1' => $fromPoleImage1 , 'fromPoleImage2'=>$fromPoleImage2]) : abort(404);
    }


    public function editMapStore(Request $request, $language,  $id)
    {
        try {
            $recored = Tiang::find($id);
            if ($recored) {
                $user = Auth::user()->name;
                // $recored->updated_by = $user;
                // return $request->qa_status;
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
                $data = $this->tiangRepository->prepareData($recored , $request);
                $data->update();
                
                Session::flash('success', 'Request Success');
                return view('components.map-messages',['id'=>$id,'success'=>true , 'url'=>'tiang-talian-vt-and-vr']);

            }else{
                Session::flash('failed', 'Request Failed');
            }

        } catch (\Throwable $th) {
            return $th->getMessage();
            Session::flash('failed', 'Request Failed');
            
        }
        return view('components.map-messages',['id'=>$id,'success'=>false , 'url'=>'tiang-talian-vt-and-vr']);
        
    }

    public function seacrh(Request $req)
    {

        $ba = \Illuminate\Support\Facades\Auth::user()->ba;

        $data = Tiang::where('ba', 'LIKE', '%' . $ba . '%');
        if ($req->type == 'tiang_no') {
           $data->where('tiang_no' , 'LIKE' , '%' . $req->q . '%')->select('tiang_no');
        }else{
            $data->where('id' , 'LIKE' ,  $req->q . '%')->select(DB::raw('id as tiang_no'));
        }
        $data = $data->limit(10)->get();

        return response()->json($data, 200);
    }

    public function seacrhCoordinated($lang , $name, $searchBy)
    {
        // return $searchBy;
        $name = urldecode($name);
        $data = Tiang::query();
        if ($searchBy == 'tiang_no') {
          $data =  $data->where('tiang_no' ,$name );
        }
        if ($searchBy == 'tiang_id') {
            $data = $data->where('id' ,$name );
        }
        $data =$data->select( \DB::raw('ST_X(geom) as x'),\DB::raw('ST_Y(geom) as y'))->first();

        return response()->json($data, 200);
    }
}
