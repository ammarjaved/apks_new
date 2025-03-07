<?php

namespace App\Http\Controllers\web\Tiang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Filter;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Tiang;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\WorkPackage;


class TiangCurrentLeakageController extends Controller
{
    use Filter;


    public function generateTiangExcel(Request $req)
    {
        try
        {

            // return $
            $data = Tiang::query();
            $ba = Auth::user()->ba;
            $data->where('ba', $ba) ->whereNotNull('current_leakage_image');

           // return $data->get();




            $gateUnlocked       = clone $data;


         $totalCounts = $data->selectRaw("sum(CASE WHEN current_leakage_image is not null THEN 1 ELSE 0 END) AS current_leakage")
                ->selectRaw('review_date')
                ->groupBy('review_date')
                ->havingRaw('sum(CASE WHEN current_leakage_image is not null THEN 1 ELSE 0 END) <> 0');


                $totalCounts = $totalCounts->get();


            if ($totalCounts)
            {


                $spreadsheet = new Spreadsheet();

                $worksheet = $spreadsheet->getActiveSheet();



                $worksheet->getColumnDimension('A')->setWidth(20);
                $worksheet->getColumnDimension('B')->setWidth(20);
                $worksheet->mergeCells('A1:B1');

                $worksheet->setCellValue('A1' ,"PEMBERSIHAN TIANG( '2024-03-13' - 2024-10-10 )");
                $worksheet->getStyle('A1')->getFont()->setBold(true);

                $worksheet->setCellValue('A3' ,"TARIKH");
                $worksheet->setCellValue('B3' ,"CURRENT LEAKAGE");



                $i = 4;

                $color = '';
                $gateCount = 0;
                $advertiseCount = 0;
                foreach ($totalCounts as $count)
                {
                    if ($i %2 == 0) {
                        $color = 'a8a8a8';
                    }
                    else{
                        $color = 'CCCCCC';
                    }
                    $worksheet->setCellValue('A' . $i, $count->review_date);
                    $worksheet->setCellValue('B' . $i, $count->current_leakage);
                    $worksheet->getStyle('A' . $i . ':C' . $i)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);
                    $i++;

                    $gateCount += $count->current_leakage;

                }

                $worksheet->setCellValue('A' . $i, 'JUMLAH');
                $worksheet->setCellValue('B' . $i, $gateCount);
                $worksheet->getStyle('A4' . ':C' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


                // GET GATE DATA AND IMMAGES


                $gateWorkSheet = $spreadsheet->createSheet();
                $gateWorkSheet->setTitle('Current Leakage');

                $g = 4;
                $sr = 1;
                //return $gateUnlocked;

                // return $gateUnlocked->get();
                // exit();
                $gateUnlocked =$gateUnlocked->orderBy('tbl_savr.review_date')->get();

                foreach ($gateUnlocked as $gate)
                {
                    $gateWorkSheet->mergeCells('B'.$g.':H'.$g);
                    $gateWorkSheet->setCellValue('B'.$g, 'Current Leakage Image');


                    $g++;
                    // Add image to cell
                    $k = $g +15;
                    $gateWorkSheet->mergeCells('B'.$g.':H'.$k);
                    $gateWorkSheet->mergeCells('I'.$g.':O'.$k);
                    $gateWorkSheet->mergeCells('P'.$g.':V'.$k);

                    $imagePath = config('globals.APP_IMAGES_LOCALE_PATH').$gate->current_leakage_image; // Provide the path to your image file

                    if ($gate->current_leakage_image !='' && file_exists($imagePath))
                    {
                        $image = new Drawing();
                        $image->setPath($imagePath);
                        $image->setCoordinates('B' . $g); // Cell coordinate where you want to insert the image
                        $image->setWidth(300); // Set the width of the image (adjust as needed)
                        $image->setHeight(300); // Set the height of the image (adjust as needed)
                        $image->setWorksheet($gateWorkSheet);
                    }




                    $g += 16;
                    // return $g;
                    $gateWorkSheet->mergeCells('B'.$g.':O'.$g+3);

                    $cellValue = "SR : {$sr}\n ID : FP-{$gate->id}\n COORDINATE : {$gate->y} , {$gate->x}\n TARIKH RONDAAN : {$gate->review_date}";
                    $gateWorkSheet->getCell('B' . $g)->setValue($cellValue);
                    // $gateWorkSheet->getCell('B' . $g)->setValue("sdasd\ndsadasd\n");
                    // $spreadsheet->getActiveSheet()->getStyle('B' . $g)->getAlignment()->setWrapText(true);

                    $g += 5;
                    $sr++;

                }

                $writer = new Xlsx($spreadsheet);

                $filename = "TIANG_CURRENT LEAKAGE {$req->ba} - ".rand(2,10000).'.xlsx';
                $writer->save(public_path('assets/updated-excels/') . $filename);
                return response()->download(public_path('assets/updated-excels/') . $filename)->deleteFileAfterSend(true);
            }
            else
            {
                return redirect()->back()->with('failed', 'No records found ');
            }

        }
        catch (\Throwable $th)
        {
            return $th->getMessage();
            return redirect()->back()->with('failed', 'Request Failed ');
        }
    }
}
