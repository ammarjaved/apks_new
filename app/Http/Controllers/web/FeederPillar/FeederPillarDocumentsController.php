<?php

namespace App\Http\Controllers\web\FeederPillar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Constants\FeederPillarConstants;
use App\Models\WorkPackage;
use Illuminate\Support\Facades\Auth;


class FeederPillarDocumentsController extends Controller
{
    //

    public function index()
    {
        $defects = FeederPillarConstants::FP_DEFECTS;
        $workPackages = WorkPackage::where('ba',Auth::user()->ba)->select('id','package_name')->get();

        $button=[
            ['url'=>'generate-feeder-pillar-lks' , 'name'=>'Generate LKS'],
            // ['url'=>'generate-feeder-pillar-ops' , 'name'=>'Generate OPS'],
            ['url'=>'generate-feeder-pillar-pembersihan' , 'name'=>'Generate Pembersihan'],
        ];
        return view('Documents.generate-documents',[
                            'title'=>'feeder_pillar',
                            'buttons'=>$button,
                            'defects'=>$defects ,
                            'modalButton'=>'generate-feeder-pillar-pembersihan-by-defect',
                            'workPackages'=>$workPackages
                    ]);

    }
}
