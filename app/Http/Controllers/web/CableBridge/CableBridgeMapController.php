<?php

namespace App\Http\Controllers\web\CableBridge;

use App\Http\Controllers\Controller;
use App\Models\CableBridge;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CableBridgeRepo;
use Illuminate\Support\Facades\DB;


class CableBridgeMapController extends Controller
{
    //

    public function editMap($lang, $id)
    {

        $data = CableBridge::find($id);
        return $data ? view('cable-bridge.edit-form', ['data' => $data, 'disabled'=>false]) : abort(404);

    }

    public function update(Request $request, $language, $id , CableBridgeRepo $cableBridge)
    {
        try
        {
            $data = CableBridge::find($id);
            $user = Auth::user()->name;

            if ($data->qa_status != $request->qa_status)
            {
                $data->qa_status = $request->qa_status;
                $data->qc_by = $user;
                $data->qc_at = now();
            }

            if ($request->qa_status == 'Reject')
            {
                $data->reject_remarks = $request->reject_remakrs;
            }
            else
            {
                $data->reject_remarks = '';
            }

            $cableBridge->store($data,$request);
            $data->update();

            return view('components.map-messages',
                            [
                                'id'=>$id,
                                'success'=>true ,
                                'url'=>'cable-bridge'
                            ]
                        ) ->with('success', 'Form Update');

        }
        catch (\Throwable $th)
        {
            // return $th->getMessage();
            return view('components.map-messages',
                            [
                                'id'=>$id,
                                'success'=>false ,
                                'url'=>'cable-bridge'
                                ]
                        )->with('failed', 'Form Update Failed');
        }
    }


    public function seacrh($lang ,  $q, $cycle)
    {

        $ba = \Illuminate\Support\Facades\Auth::user()->ba;

        $data = CableBridge::where('ba', 'LIKE', '%' . $ba . '%')
                            ->where('id' , 'LIKE' , '%' . $q . '%')
                            ->where('cycle', $cycle)
                            ->select('id')
                            ->limit(10)
                            ->get();

        return response()->json($data, 200);
    }

    public function seacrhCoordinated($lang , $name)
    {
        $name = urldecode($name);
        $data = CableBridge::where('id' ,$name )
                            ->pluck('geom_id')
                            ->first();

        $geom =  DB::table('tbl_cable_bridge_geom')
                        ->where('id', $data)
                        ->select(
                             \DB::raw('ST_X(geom) as x'),
                             \DB::raw('ST_Y(geom) as y')
                        )->first();

        return response()->json($geom, 200);
    }

}
