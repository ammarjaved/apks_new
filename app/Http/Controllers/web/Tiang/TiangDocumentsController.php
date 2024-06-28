<?php

namespace App\Http\Controllers\web\Tiang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkPackage;
use App\Constants\TiangConstants;
use Illuminate\Support\Facades\Auth;

class TiangDocumentsController extends Controller
{
    public function index()
    {
        $defects = TiangConstants::TIANG_DEFECTS_KEYS;
        $workPackages = WorkPackage::where('ba',Auth::user()->ba)->select('id','package_name')->get();
        $button =[];
        $button=[
            ['url'=>'generate-tiang-talian-vt-and-vr-lks' , 'name'=>'Generate LKS'],
            ['url'=>'generate-tiang-talian-vt-and-vr-pembersihan' , 'name'=>'Generate Pembersihan'],

            // ['url'=>'tiang-talian-vt-and-vr-SBUM-report' , 'name'=>'SBUM Report'],
        ];
        return view('Documents.generate-documents',[
            'title'=>'tiang' ,
            'url'=>'tiang-talian-vt-and-vr-lks',
            'buttons'=>$button,
            'defects'=>$defects ,
            'modalButton'=>'generate-tiang-talian-vt-and-vr-pembersihan-by-defect',
            'workPackages'=>$workPackages
        ]);
    }
}
