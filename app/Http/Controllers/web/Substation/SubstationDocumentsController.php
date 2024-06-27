<?php

namespace App\Http\Controllers\web\Substation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Constants\SubstationConstants;
use App\Models\WorkPackage;

class SubstationDocumentsController extends Controller
{
    //

    public function index()
    {

        $defects      = SubstationConstants::PE_DEFECTS;        //GET ALL DEFECTS FROM CONTANTS
        $workPackages = WorkPackage::where('ba',Auth::user()->ba)->select('id','package_name')->get();

        // ALL BUTTONS AND URLS
        $button=[
            ['url'=>'generate-substation-lks' , 'name'=>'Generate LKS'],
            ['url'=>'generate-substation-toc-claim' , 'name'=>'TOC Claim'],
            ['url'=>'generate-substation-pembersihan' , 'name'=>'Generate Pembersihan'],
        ];

        return view('Documents.generate-documents',[
            'title'=>'substation' ,
            'buttons'=>$button,
            'defects'=>$defects ,
            'modalButton'=>'generate-substation-pembersihan-by-defect',
            'workPackages'=>$workPackages
        ]);
    }
}
