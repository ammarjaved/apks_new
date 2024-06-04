<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ThirdPartyDiging;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GenerateNoticeController extends Controller
{
    //
    public function generateNotice($id){
        return view('third-party.generatenotice');
    }

    public function index()  {
        $ba = Auth::user()->ba;
        $datas = ThirdPartyDiging::where('ba','LIKE', '%' . $ba . '%')->where('notice','yes')->get();

        // return $datas;
        return view('notice.index',['datas'=>$datas]);

    }

    public function store(Request $req)
    {
        try {
            //code...

        $req->validate([
            'upload_notice' => 'required|file|mimes:jpeg,png,pdf',
            'id' =>'required',
        ]);
        if ($req->hasFile('upload_notice')) {
        $data = ThirdPartyDiging::find($req->id);
        if ($data) {

            if ($data->digging_notice && file_exists(public_path($data->digging_notice))) {
                unlink(public_path($data->digging_notice));
            }
        $destinationPath = '/assets/images/notice/';

        $uploadedFile = $req->file('upload_notice');
        $img_ext = $uploadedFile->getClientOriginalExtension();
        $filename = $req->wp_name . '-' . strtotime(now()) . '.' . $img_ext;
        $uploadedFile->move(public_path($destinationPath), $filename);
        $data->digging_notice = $destinationPath . $filename;
        $data->save();
            return redirect()->route('notice',  app()->getLocale() )->with('success',"Request Success");

        }
    }

    } catch (\Throwable $th) {

        return redirect()->route('notice',  app()->getLocale() )->with('failed',"Request Failed ". $th->getMessage() );
    }
}

    public function download($lang,$id)
    {
        $data = ThirdPartyDiging::find($id);
        if ($data &&   $data->digging_notice && file_exists(public_path($data->digging_notice))) {

            return response()->download(public_path($data->digging_notice));
        }
        return redirect()->route('notice',  app()->getLocale() )->with('failed',"Request Failed "  );

    }


}
