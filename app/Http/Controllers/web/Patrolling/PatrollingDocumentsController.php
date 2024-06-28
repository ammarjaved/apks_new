<?php

namespace App\Http\Controllers\web\Patrolling;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatrollingDocumentsController extends Controller
{
    //
    public function index(){
        $button =[];
        $button=[
            ['url'=>'generate-patrolling-lks' , 'name'=>'Generate LKS'],
        ];
        return view('Documents.generate-documents',['title'=>'patrolling','buttons'=>$button]);
    }

}
