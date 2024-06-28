<?php

namespace App\Http\Controllers\web\CableBridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkPackage;

class CableBridgeDocumentsController extends Controller
{
    //
    public function index()
    {
        $workPackages = WorkPackage::where('ba',Auth::user()->ba)->select('id','package_name')->get();

        $button=[
            ['url'=>'generate-cable-bridge-lks' , 'name'=>'Generate LKS'],
        ];
        return view('Documents.generate-documents',[
            'title'=>'cable_bridge',
            'buttons'=>$button,
            'workPackages'=>$workPackages
        ]);

    }

}
