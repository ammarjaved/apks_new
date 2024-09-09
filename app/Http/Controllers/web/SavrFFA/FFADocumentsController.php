<?php

namespace App\Http\Controllers\web\SavrFFA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkPackage;

class FFADocumentsController extends Controller
{
    //
    public function index()
    {
        $workPackages = WorkPackage::where('ba',Auth::user()->ba)->select('id','package_name')->get();

        $button=[
            ['url'=>'generate-ffa-lks' , 'name'=>'Generate FFA LKS'],
        ];
        return view('Documents.generate-documents',[
            'title'=>'savr_ffa',
            'buttons'=>$button,
            'workPackages'=>$workPackages
        ]);

    }

}
