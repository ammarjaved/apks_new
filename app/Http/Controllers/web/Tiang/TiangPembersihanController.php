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


class TiangPembersihanController extends Controller
{
    use Filter;


    public function generateTiangExcel(Request $req)
    {
        try
        {

            // return $
            $data = Tiang::query();
            $data = $this->filter($data , 'review_date' , $req)->where('qa_status', 'Accept');

            $gateUnlocked       = clone $data;
            $advertisePoster    = clone $data;
            $totalCounts        = clone $data;

         $totalCounts = $data->selectRaw("sum(CASE WHEN (tiang_defect->>'creepers')::text = 'true' THEN 1 ELSE 0 END) AS creepers")
                ->selectRaw("sum(CASE WHEN clean_banner_image is not null THEN 1 ELSE 0 END) AS advertise_banner")
                ->selectRaw('review_date')
                ->groupBy('review_date')
                ->havingRaw('sum(CASE WHEN (tiang_defect->>\'creepers\')::text = \'true\' THEN 1 ELSE 0 END) <> 0 OR sum(CASE WHEN clean_banner_image is not null THEN 1 ELSE 0 END) <> 0')
                ->get();


            if ($totalCounts)
            {


                $spreadsheet = new Spreadsheet();

                $worksheet = $spreadsheet->getActiveSheet();



                $worksheet->getColumnDimension('A')->setWidth(20);
                $worksheet->getColumnDimension('B')->setWidth(20);
                $worksheet->mergeCells('A1:B1');

                $worksheet->setCellValue('A1' ,"PEMBERSIHAN TIANG( {$req->from_date} - {$req->to_date} )");
                $worksheet->getStyle('A1')->getFont()->setBold(true);

                $worksheet->setCellValue('A3' ,"TARIKH");
                $worksheet->setCellValue('B3' ,"CREEPERS");
                $worksheet->setCellValue('C3' ,"BANNER");


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
                    $worksheet->setCellValue('B' . $i, $count->creepers);
                    $worksheet->setCellValue('C' . $i, $count->advertise_banner);
                    $worksheet->getStyle('A' . $i . ':C' . $i)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);
                    $i++;

                    $gateCount += $count->creepers;
                    $advertiseCount += $count->advertise_banner;
                }

                $worksheet->setCellValue('A' . $i, 'JUMLAH');
                $worksheet->setCellValue('B' . $i, $gateCount);
                $worksheet->setCellValue('C' . $i, $advertiseCount);
                $worksheet->getStyle('A4' . ':C' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


                // GET GATE DATA AND IMMAGES
                $gateUnlocked = $gateUnlocked->whereRaw("(tiang_defect->>'creepers')::text = 'true'")
                        ->select('pole_image_1','id','review_date' , DB::raw('ST_X(geom) as x' ), 'remove_creepers_image' , DB::raw('ST_Y(geom) as y'))->orderBy('review_date')->get();




                $gateWorkSheet = $spreadsheet->createSheet();
                $gateWorkSheet->setTitle('PEMBERSIHAN CREEPERS');

                $g = 4;
                $sr = 1;
                foreach ($gateUnlocked as $gate)
                {
                    $gateWorkSheet->mergeCells('B'.$g.':H'.$g);
                    $gateWorkSheet->setCellValue('B'.$g, 'SEBELUM');

                    $gateWorkSheet->mergeCells('I'.$g.':O'.$g);
                    $gateWorkSheet->setCellValue('I'.$g , 'SELEPAS');

                    $g++;
                    // Add image to cell
                    $k = $g +15;
                    $gateWorkSheet->mergeCells('B'.$g.':H'.$k);
                    $gateWorkSheet->mergeCells('I'.$g.':O'.$k);

                    $imagePath = config('globals.APP_IMAGES_LOCALE_PATH').$gate->pole_image_1; // Provide the path to your image file
                    if ($gate->pole_image_1 !='' && file_exists($imagePath))
                    {
                        $image = new Drawing();
                        $image->setPath($imagePath);
                        $image->setCoordinates('B' . $g); // Cell coordinate where you want to insert the image
                        $image->setWidth(300); // Set the width of the image (adjust as needed)
                        $image->setHeight(300); // Set the height of the image (adjust as needed)
                        $image->setWorksheet($gateWorkSheet);
                    }

                    $imagePath1 = config('globals.APP_IMAGES_LOCALE_PATH').$gate->remove_creepers_image; // Provide the path to your image file
                    if ($gate->remove_creepers_image !='' && file_exists($imagePath1))
                    {
                        $image1 = new Drawing();
                        $image1->setPath($imagePath1);
                        $image1->setCoordinates('I' . $g); // Cell coordinate where you want to insert the image
                        $image1->setWidth(300); // Set the width of the image (adjust as needed)
                        $image1->setHeight(300); // Set the height of the image (adjust as needed)
                        $image1->setWorksheet($gateWorkSheet);
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





                // GET POSTER DATA AND IMMAGES
                $advertisePoster = $advertisePoster->whereNotNull("clean_banner_image")
                        ->select('pole_image_2','id','review_date' , DB::raw('ST_X(geom) as x' ) , DB::raw('ST_Y(geom) as y'), 'clean_banner_image')->orderBy('review_date')->get();

                $advertiseSheet = $spreadsheet->createSheet();
                $advertiseSheet->setTitle('PEMBERSIHAN IKLAN HARAM');
                $g = 4;
                $sr = 1;
                foreach ($advertisePoster as $advertise)
                {
                    $advertiseSheet->mergeCells('B'.$g.':H'.$g);
                    $advertiseSheet->setCellValue('B'.$g, 'SEBELUM');

                    $advertiseSheet->mergeCells('I'.$g.':O'.$g);
                    $advertiseSheet->setCellValue('I'.$g , 'SELEPAS');

                    $g++;
                    // Add image to cell
                    $k = $g +15;
                    $advertiseSheet->mergeCells('B'.$g.':H'.$k);
                    $advertiseSheet->mergeCells('I'.$g.':O'.$k);

                    $imagePath = config('globals.APP_IMAGES_LOCALE_PATH').$advertise->pole_image_2;
                    if ($advertise->pole_image_2 != '' && file_exists($imagePath))
                    {
                        $image = new Drawing();
                        $image->setPath($imagePath);
                        $image->setCoordinates('B' . $g); // Cell coordinate where you want to insert the image
                        $image->setWidth(300); // Set the width of the image (adjust as needed)
                        $image->setHeight(300); // Set the height of the image (adjust as needed)
                        $image->setWorksheet($advertiseSheet);
                    }

                    $imagePath1 = config('globals.APP_IMAGES_LOCALE_PATH').$advertise->clean_banner_image;
                    if ($advertise->clean_banner_image !='' && file_exists($imagePath1))
                    {
                        $image1 = new Drawing();
                        $image1->setPath($imagePath1);
                        $image1->setCoordinates('I' . $g); // Cell coordinate where you want to insert the image
                        $image1->setWidth(300); // Set the width of the image (adjust as needed)
                        $image1->setHeight(300); // Set the height of the image (adjust as needed)
                        $image1->setWorksheet($advertiseSheet);
                    }

                    $g += 16;
                    // return $g;
                    $advertiseSheet->mergeCells('B'.$g.':O'.$g+3);

                    $cellValue = "SR : {$sr}\n ID : FP-{$advertise->id}\n COORDINATE : {$advertise->y} , {$advertise->x}\n TARIKH RONDAAN : {$advertise->review_date}";
                    $advertiseSheet->getCell('B' . $g)->setValue($cellValue);
                    // $gateWorkSheet->getCell('B' . $g)->setValue("sdasd\ndsadasd\n");
                    // $spreadsheet->getActiveSheet()->getStyle('B' . $g)->getAlignment()->setWrapText(true);

                    $g += 5;
                    $sr++;

                }

                $writer = new Xlsx($spreadsheet);

                $filename = "TIANG_PEMBERSIHAN {$req->ba} {$req->from_date} - {$req->to_date} ".rand(2,10000).'.xlsx';
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
            return redirect()->back()->with('failed', 'Request Failed '. $th->getMessage());
        }
    }
}
