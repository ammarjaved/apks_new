<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CableBridge;
use App\Models\FeederPillar;
use App\Models\LinkBox;
use App\Models\Patroling;
use App\Models\Substation;
use App\Models\ThirdPartyDiging;
use App\Models\Tiang;
use App\Traits\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboard extends Controller
{
    use Filter;
    //


    public function index(Request $request){
        if (Auth::user()->ba == '') {
        return view('admin-dashboard');

        }
        return view('dashboard');
    }


    public function getAllCounts(Request $request)
    {
        try {
            // $this->filter  is trait  this function taking 3 params (1) model query (2)column name (3) $request that contains  visit_date from-to date nad ba
            // traits return filtered orms and after filtered count

            $tables = [
                'substation' => 'tbl_substation',
                'feeder_pillar' => 'tbl_feeder_pillar',
                'tiang' => 'tbl_savr',
                'link_box' => 'tbl_link_box',
                'cable_bridge' => 'tbl_cable_bridge',
                'savt' => 'tbl_savt',
            ];
        //    if ($request->ajax()) {
                $data = [];

                foreach ($tables as $key => $tableName) {
                    $query = DB::table($tableName);
                    $column = $key == 'tiang' ? 'review_date' : 'visit_date';


                    $query = $this->filter($query, $column, $request);

                   // print_r($query);

                    $data[$key] = $query->count(); // Count records

                    // Sum total_defects
                    $data[$key . '_defect'] = $query->where('total_defects', '>', 0)->sum('total_defects');
                }

                $data['total_km'] = $this->filterWithOutAccpet(Patroling::select(DB::raw('sum(km)')), 'vist_date', $request)->first()->sum;
                $data['total_notice'] = $this->filterWithOutAccpet(ThirdPartyDiging::where('notice', 'yes'), 'survey_date', $request)->count();
                $data['total_supervision'] = $this->filterWithOutAccpet(ThirdPartyDiging::where('supervision', 'yes'), 'survey_date', $request)->count();

                return $data;
          //  }
       //     return view('admin-dashboard');
        } catch (\Throwable $th) {
            return $th->getMessage();
            return redirect()->route('third-party-digging.index', app()->getLocale());
        }
    }



    function statsTable(Request $request){
        $bas = [
            'RAWANG',
            'KUALA LUMPUR PUSAT',
            'KLANG',
            'KUALA SELANGOR',
            'PELABUHAN KLANG',
            'BANGI',
            'CHERAS',
            'BANTING',
            'PUTRAJAYA & CYBERJAYA',
            'PETALING JAYA',
            'SEPANG',
            'PUCHONG'
        ];


        if ( $request->ba_name != 'null') {
            $bas = [];
                $bas = [$request->ba_name];
        }
        $from_date  = $request->from_date;
        $to_date    = $request->to_date;
        $data = [];

        foreach($bas as $key => $ba){


            $query="select   ba, COALESCE(km,0) as patroling  ,substation, feeder_pillar , tiang , link_box , cable_bridge   from
        (
            select '$ba' as ba,
                    (select count(*) from tbl_substation where total_defects is not null
                     and substation_image_1 is not null and substation_image_2 is not null  and ba='$ba' and visit_date >= '$from_date'
                     AND visit_date <=  ' $to_date' and  qa_status='Accept') as substation,
                    (select count(*) from tbl_feeder_pillar where feeder_pillar_image_1 is not null and
                     feeder_pillar_image_2 is not null  and ba='$ba' and visit_date >= '$from_date'
                     AND visit_date <=  ' $to_date'  and  qa_status='Accept' ) as feeder_pillar,
                    (select count(*) from tbl_savr  where ba='$ba' and pole_image_1 is not null and review_date is not null  and review_date >= '$from_date'
                    AND review_date <=  ' $to_date' and   qa_status='Accept') as tiang,
                    (select count(*) from tbl_link_box  where ba='$ba' and link_box_image_1 is not null and visit_date is not null  and visit_date >= '$from_date'
                    AND visit_date <=  ' $to_date'  and  qa_status='Accept') as link_box,
                    (select count(*) from tbl_cable_bridge   where ba='$ba' and cable_bridge_image_1 is not null and visit_date is not null  and visit_date >= '$from_date'
                    AND visit_date <=  ' $to_date' and  qa_status='Accept') as cable_bridge,
                    (select round(sum(km),2) from patroling  where ba='$ba' and vist_date is not null  and vist_date >= '$from_date'
                    AND vist_date <=  ' $to_date' ) as km) as stats";
                $res = DB::select($query);

                   $data[] =$res[0];

        }

                   return $data;

                   if ($data) {
                return $data;
                }

    }




}
