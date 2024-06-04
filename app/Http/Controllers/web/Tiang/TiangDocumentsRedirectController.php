<?php

namespace App\Http\Controllers\web\Tiang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TiangDocumentsRedirectController extends Controller
{
    //

    public function redirectFunction(Request $req)  {
        return redirect()->action([TiangLKSController::class, 'generateByVisitDate'], ['locale' => 'en']); 
    }
}
