<?php

namespace App\Http\Controllers\web\excel;

use App\Http\Controllers\Controller;
use App\Models\Road;
use App\Models\ThirdPartyDiging;
use App\Models\WorkPackage;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DigingExcelController extends Controller
{
    //
    public function generateDigingExcel($language , $id)
    {
        $recored = ThirdPartyDiging::where('workpackage_id', $id)->get();
        // return $recored;
        if (sizeof($recored) > 0) {
            ob_end_clean(); // this

            $work = WorkPackage::find($id);

            $excelFile = public_path('assets/excel-template/test.xlsx');

            $spreadsheet = IOFactory::load($excelFile);

            $worksheet = $spreadsheet->getActiveSheet();
            $cs = $worksheet->getCell('C3')->getValue();


            $i = 3;
            foreach ($recored as $rec) {

              //  if ($rec->image1 != '' || $rec->image2 != '' || $rec->image3 != '') {
                    $worksheet->setCellValue('A' . $i, $i - 2);
                    $worksheet->setCellValue('B' . $i, $work->package_name);
                    $worksheet->setCellValue('C' . $i, $work->zone);
                    $worksheet->setCellValue('D' . $i, $work->ba);

                    if ($work->road_id != '') {
                        $road = Road::find($id);
                        if ($road) {
                            $worksheet->setCellValue('H' . $i, $work->ba);
                        }
                    }
                //}
                $i++;
            }
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');


            $writer->save(public_path('assets/updated-excels/') . $work->package_name . '.xlsx');
            // ob_end_clean();
            return response()->download(public_path('assets/updated-excels/') . $work->package_name . '.xlsx');
        }else{
            return redirect()->back()->with('failed','No records found ');
        }
    }
}
