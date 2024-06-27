<?php

namespace App\Http\Controllers\web\LinkBox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkPackage;
use Illuminate\Support\Facades\Auth;

class LinkBoxDocumentsController extends Controller
{
    //
    public function index(){

        $workPackages = WorkPackage::where('ba',Auth::user()->ba)->select('id','package_name')->get();

        $button=[
            ['url'=>'generate-link-box-lks' , 'name'=>'Generate LKS'],
        ];
        return view('Documents.generate-documents',[
                    'title'=>'link_box' ,
                    'buttons'=>$button,
                    'workPackages'=>$workPackages
                ]);

    }

}
