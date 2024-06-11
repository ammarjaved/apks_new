<?php

namespace App\Http\Controllers\web\LinkBox;

use App\Http\Controllers\Controller;
use App\Models\LinkBox;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LinkBoxMapController extends Controller
{
    //

    public function editMap($lang, $id)
    {
        $data = LinkBox::find($id);
        return $data ? view('link-box.edit-form', ['data' => $data, 'disabled'=>false]) : abort(404);
    }

    public function update(Request $request, $language, $id)
    {
        //

        $currentDate = Carbon::now()->toDateString();
        $combinedDateTime = $currentDate . ' ' . $request->patrol_time;
        try {
            $defects = [];
            $defects =['leaning_status','vandalism_status','advertise_poster_status','rust_status','bushes_status' ,'cover_status' , 'paint_status'];
            $total_defects = 0;
            $data = LinkBox::find($id);
            $data->zone = $request->zone;
            $data->ba = $request->ba;
            // $data->team = $request->team;
            $data->visit_date = $request->visit_date;
            $data->patrol_time = $combinedDateTime;
            $data->feeder_involved = $request->feeder_involved;
            $user = Auth::user()->name;

            if ($data->qa_status != $request->qa_status) {
                $data->qa_status = $request->qa_status;
                $data->qc_by = $user;
                $data->qc_at = now();
            }
            if ($request->qa_status == 'Reject') {
                $data->reject_remarks = $request->reject_remakrs;
            } else{
                $data->reject_remarks = '';

            }

            // $data->updated_by = $user;
            if ($data->qa_status == '') {
                $data->qa_status = 'pending';
            }
            $data->start_date = $request->start_date;
            $data->end_date = $request->end_date;
            $data->type = $request->type;
            $data->coordinate = $request->coordinate;
            foreach ($defects as  $value) {
                $data->{$value} = $request->{$value};
               $request->has($value)&& $request->{$value} == 'Yes' ? $total_defects++ : '';
            }
            $data->total_defects = $total_defects;
            $data->leaning_angle = $request->leaning_angle;
            $destinationPath = 'assets/images/link-box/';
            $imageStoreUrlPath = config('globals.APP_IMAGES_STORE_URL').$destinationPath;


            foreach ($request->all() as $key => $file) {
                // Check if the input is a file and it is valid
                if ($request->hasFile($key) && $request->file($key)->isValid()) {
                    $uploadedFile = $request->file($key);
                    $img_ext = $uploadedFile->getClientOriginalExtension();
                    $filename = $key . '-' . strtotime(now()).rand(10,100)  . '.' . $img_ext;
                    $filePath = $imageStoreUrlPath   . $filename;

                    // Move the file to the first location
                    $fileContents = file_get_contents($uploadedFile->getRealPath());
                    file_put_contents($filePath, $fileContents);
                    // $uploadedFile->move($destinationPath, $filename);
                    $data->{$key} = $destinationPath . $filename;
                }
            }

            $data->save();

            return view(
                        'components.map-messages',
                            [
                                'id' => $id,
                                'success' => true,
                                'url' => 'link-box-pelbagai-voltan'
                            ]
                        )->with('success', 'Form Update');

        } catch (\Throwable $th) {
            // return $th->getMessage();
            return view('components.map-messages', ['id' => $id, 'success' => false, 'url' => 'link-box-pelbagai-voltan'])->with('failed', 'Form Update Failed');
        }
    }


    public function seacrh($lang ,  $q, $cycle)
    {

        $ba = \Illuminate\Support\Facades\Auth::user()->ba;

        $data = LinkBox::where('ba', 'LIKE', '%' . $ba . '%')
                        ->where('id' , 'LIKE' , '%' . $q . '%')
                        ->where('cycle',$cycle)
                        ->select('id')
                        ->limit(10)
                        ->get();

        return response()->json($data, 200);
    }

    public function seacrhCoordinated($lang , $name)
    {
        $name = urldecode($name);
        $data = LinkBox::where('id' ,$name )->select('id', \DB::raw('ST_X(geom) as x'),\DB::raw('ST_Y(geom) as y'),)->first();

        return response()->json($data, 200);
    }

}
