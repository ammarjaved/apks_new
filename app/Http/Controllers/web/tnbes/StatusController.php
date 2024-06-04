<?php

namespace App\Http\Controllers\web\tnbes;

use App\Http\Controllers\Controller;
use App\Models\WorkPackage;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    //

    public function sendToTnbes($language ,$id)
    {
        WorkPackage::find($id)->update(['wp_status' => 'pending']);
        return redirect()
            ->back()
            ->with('success', 'Successfully send to SBUM');
    }



    public function statusSUBM($language,$id, $status)
    {
        try {
            WorkPackage::find($id)->update(['wp_status' => $status]);

            return redirect()
                ->back()
                ->with('success', 'Status Update Successfully');
        } catch (\Throwable $th) {
             return $th->getMessage();
            return redirect()
                ->back()
                ->with('failed', 'Request Failed');
        }
    }
}
