<?php

namespace App\Http\Controllers\web\Substation;

use App\Http\Controllers\Controller;
use App\Models\Substation;
use App\Traits\Filter;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubstationExcelController extends Controller
{
    //
    use Filter;


    public function generateSubstationExcel(Request $req)
    {
        try
        {
            $result = Substation::query();
            $result = $this->filter($result , 'visit_date',$req);

            $result = $result->whereNotNull('visit_date')->select('*', DB::raw('ST_X(geom) as x'), DB::raw('ST_Y(geom) as y'))->get();

            if ($result)
            {
                $excelFile = public_path('assets/excel-template/substation.xlsx');
                $spreadsheet = IOFactory::load($excelFile);
                $worksheet = $spreadsheet->getActiveSheet();

                $i = 3;
                foreach ($result as $rec)
                {

                    $worksheet->setCellValue('A' . $i, $i - 2);
                    $worksheet->setCellValue('B' . $i, $rec->zone);
                    $worksheet->setCellValue('C' . $i, $rec->ba);
                    $worksheet->setCellValue('D' . $i, $rec->team);
                    $worksheet->setCellValue('E' . $i,  $rec->visit_date != '' ?date('Y-m-d', strtotime($rec->visit_date)) : '');
                    $worksheet->setCellValue('F' . $i,  $rec->patrol_time != '' ?date('H:i:s', strtotime($rec->patrol_time)) : '');
                    $worksheet->setCellValue('G' . $i, $rec->fl);
                    $worksheet->setCellValue('H' . $i, $rec->voltage);
                    $worksheet->setCellValue('I' . $i, $rec->name);
                    $worksheet->setCellValue('J' . $i, $rec->type);
                    $worksheet->setCellValue('K' . $i, number_format( $rec->y, 5) .",". number_format( $rec->x , 5));
                    if ($rec->gate_status)
                    {
                        $gate_status = json_decode($rec->gate_status);
                        $worksheet->setCellValue('L' . $i, substaionCheckBox('unlocked', $gate_status ) == 'checked' ? 'yes' : 'no' );
                        $worksheet->setCellValue('M' . $i, substaionCheckBox('demaged', $gate_status ) == 'checked' ? 'yes' : 'no' );
                        $worksheet->setCellValue('N' . $i, substaionCheckBox('other', $gate_status ) == 'checked' ? 'yes' : 'no' );
                    }
                    $worksheet->setCellValue('O' . $i, $rec->grass_status);
                    $worksheet->setCellValue('P' . $i, $rec->tree_branches_status);


                    if ($rec->building_status)
                    {
                        $building_status = json_decode($rec->building_status);
                        $worksheet->setCellValue('Q' . $i, substaionCheckBox('broken_roof', $building_status ) == 'checked' ? 'yes' : 'no' );
                        $worksheet->setCellValue('R' . $i, substaionCheckBox('broken_gutter', $building_status ) == 'checked' ? 'yes' : 'no' );
                        $worksheet->setCellValue('S' . $i,  substaionCheckBox('broken_base', $building_status ) == 'checked' ? 'yes' : 'no' );
                        $worksheet->setCellValue('T' . $i,  substaionCheckBox('other', $building_status ) == 'checked' ? 'yes' : 'no' );
                    }


                    $worksheet->setCellValue('U' . $i, $rec->advertise_poster_status);
                    $worksheet->setCellValue('V' . $i, $rec->total_defects);
                    $worksheet->setCellValue('W' . $i, $rec->repair_date != ''?date('Y-m-d', strtotime($rec->repair_date)) : '');
                    $worksheet->setCellValue('X' . $i, config('globals.APP_IMAGES_URL').$rec->substation_image_1
                    .' , '.config('globals.APP_IMAGES_URL').$rec->substation_image_2
                    .' , '.config('globals.APP_IMAGES_URL').$rec->image_gate
                    .' , '.config('globals.APP_IMAGES_URL').$rec->image_gate_2
                    .' , '.config('globals.APP_IMAGES_URL').$rec->images_gate_after_lock
                    .' , '.config('globals.APP_IMAGES_URL').$rec->images_gate_after_lock_2
                    .' , '.config('globals.APP_IMAGES_URL').$rec->image_grass
                    .' , '.config('globals.APP_IMAGES_URL').$rec->image_grass_2
                    .' , '.config('globals.APP_IMAGES_URL').$rec->image_tree_branches
                    .' , '.config('globals.APP_IMAGES_URL').$rec->image_tree_branches_2
                    .' , '.config('globals.APP_IMAGES_URL').$rec->image_building
                    .' , '.config('globals.APP_IMAGES_URL').$rec->image_building_2
                    .' , '.config('globals.APP_IMAGES_URL').$rec->image_advertisement_before_1
                    .' , '.config('globals.APP_IMAGES_URL').$rec->image_advertisement_before_2
                    .' , '.config('globals.APP_IMAGES_URL').$rec->image_advertisement_after_1
                    .' , '.config('globals.APP_IMAGES_URL').$rec->image_advertisement_after_2
                    .' , '.config('globals.APP_IMAGES_URL').$rec->other_image);

                    $i++;
                }

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                $filename = 'substation'.rand(2,10000).'.xlsx';
                $writer->save(public_path('assets/updated-excels/') . $filename);
                return response()->download(public_path('assets/updated-excels/') . $filename)->deleteFileAfterSend(true);
            }
            else
            {
                return redirect()->back() ->with('failed', 'No records found ');
            }
        }
        catch (\Throwable $th)
        {
            return redirect()->back()->with('failed', 'Request Failed '. $th->getMessage());
        }
    }
}
