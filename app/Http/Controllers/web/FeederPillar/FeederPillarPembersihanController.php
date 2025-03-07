<?php

namespace App\Http\Controllers\web\FeederPillar;

use App\Http\Controllers\Controller;
use App\Models\FeederPillar;
use App\Traits\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\WorkPackage;

class FeederPillarPembersihanController extends Controller
{
    //
    use Filter;

    public function pembersihan(Request $req)
    {
        try
        {
            $data = FeederPillar::query();
            $data = $this->filter($data , 'visit_date' , $req)->where('qa_status', 'Accept');

            $data = $data ->join('tbl_feeder_pillar_geom as g', 'tbl_feeder_pillar.geom_id', '=', 'g.id');

            if ($req->filled('workPackages'))
            {
                // Fetch the geometry of the work package
                $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');

                // Execute the query
                $data = $data ->whereRaw('ST_Within(g.geom, ?)', [$workPackageGeom]);

            }

            $gateUnlocked       = clone $data;
            $advertisePoster    = clone $data;
            $totalCounts        = clone $data;

            $totalCounts = $data->selectRaw("sum(CASE WHEN (gate_status->>'unlocked')::text = 'true' THEN 1 ELSE 0 END) AS gate_unlocked")
                ->selectRaw("sum(CASE WHEN advertise_poster_status = 'Yes' THEN 1 ELSE 0 END) AS advertise_banner")
                ->selectRaw('visit_date')
                ->groupBy('visit_date')
                ->havingRaw('sum(CASE WHEN (gate_status->>\'unlocked\')::text = \'true\' THEN 1 ELSE 0 END) <> 0 OR sum(CASE WHEN advertise_poster_status = \'Yes\' THEN 1 ELSE 0 END) <> 0')
                ->get();

            //    return $sql = $data->toSql();

            if ($totalCounts)
            {

                $excelFile = public_path('assets/excel-template/FP_PEMBERSIHAN.xlsx');
                $spreadsheet = IOFactory::load($excelFile);
                $worksheet = $spreadsheet->getSheet(0);
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

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
                    $worksheet->setCellValue('A' . $i, $count->visit_date);
                    $worksheet->setCellValue('B' . $i, $count->gate_unlocked);
                    $worksheet->setCellValue('C' . $i, $count->advertise_banner);
                    $worksheet->getStyle('A' . $i . ':C' . $i)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);
                    $i++;

                    $gateCount += $count->gate_unlocked;
                    $advertiseCount += $count->advertise_banner;

                }



                $worksheet->setCellValue('A' . $i, 'JUMLAH');
                $worksheet->setCellValue('B' . $i, $gateCount);
                $worksheet->setCellValue('C' . $i, $advertiseCount);
                $worksheet->getStyle('A4' . ':C' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


                // GET GATE DATA AND IMMAGES
                $gateUnlocked = $gateUnlocked
                                    ->whereRaw("(gate_status->>'unlocked')::text = 'true'")
                                    ->select('image_gate','tbl_feeder_pillar.id','visit_date' ,  DB::raw('ST_X(g.geom) as x') ,'image_gate_2' , DB::raw('ST_Y(g.geom) as y'))
                                    ->orderBy('visit_date')
                                    ->get();

                $gateWorkSheet = $spreadsheet->getSheet(1);
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

                    $imagePath = config('globals.APP_IMAGES_LOCALE_PATH').$gate->image_gate; // Provide the path to your image file
                    if ($gate->image_gate !='' && file_exists($imagePath))
                    {
                        $image = new Drawing();
                        $image->setPath($imagePath);
                        $image->setCoordinates('B' . $g); // Cell coordinate where you want to insert the image
                        $image->setWidth(300); // Set the width of the image (adjust as needed)
                        $image->setHeight(300); // Set the height of the image (adjust as needed)
                        $image->setWorksheet($gateWorkSheet);
                    }

                      $imagePath1 = config('globals.APP_IMAGES_LOCALE_PATH').$gate->image_gate_2; // Provide the path to your image file
                    if ($gate->image_gate_2 !='' && file_exists($imagePath1))
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

                    $cellValue = "SR : {$sr}\n ID : FP-{$gate->id}\n COORDINATE : {$gate->y} , {$gate->x}\n TARIKH RONDAAN : {$gate->visit_date}";
                    $gateWorkSheet->getCell('B' . $g)->setValue($cellValue);
                    // $gateWorkSheet->getCell('B' . $g)->setValue("sdasd\ndsadasd\n");
                    // $spreadsheet->getActiveSheet()->getStyle('B' . $g)->getAlignment()->setWrapText(true);

                    $g += 5;
                    $sr++;

                }




                // GET POSTER DATA AND IMMAGES
                $advertisePoster = $advertisePoster
                                            ->where('advertise_poster_status', 'Yes')
                                            ->select('images_advertise_poster','image_advertisement_during_1','tbl_feeder_pillar.id','visit_date' , DB::raw('ST_X(g.geom) as x' ) , DB::raw('ST_Y(g.geom) as y'), 'image_advertisement_after_1')
                                            ->orderBy('visit_date')
                                            ->get();


                $advertiseSheet = $spreadsheet->getSheet(2);
                $g = 4;
                $sr = 1;
                foreach ($advertisePoster as $advertise)
                {
                    $advertiseSheet->mergeCells('B'.$g.':H'.$g);
                    $advertiseSheet->setCellValue('B'.$g, 'SEBELUM');

                    $advertiseSheet->mergeCells('I'.$g.':O'.$g);
                    $advertiseSheet->setCellValue('I'.$g , 'SEMASA');

                    $advertiseSheet->mergeCells('P'.$g.':V'.$g);
                    $advertiseSheet->setCellValue('P'.$g , 'SELEPAS');


                    $g++;
                    // Add image to cell
                    $k = $g +15;
                    $advertiseSheet->mergeCells('B'.$g.':H'.$k);
                    $advertiseSheet->mergeCells('I'.$g.':O'.$k);
                    $advertiseSheet->mergeCells('P'.$g.':V'.$k);


                    $imagePath = config('globals.APP_IMAGES_LOCALE_PATH').$advertise->images_advertise_poster;
                    if ($advertise->images_advertise_poster != '' && file_exists($imagePath))
                    {
                        $image = new Drawing();
                        $image->setPath($imagePath);
                        $image->setCoordinates('B' . $g); // Cell coordinate where you want to insert the image
                        $image->setWidth(300); // Set the width of the image (adjust as needed)
                        $image->setHeight(300); // Set the height of the image (adjust as needed)
                        $image->setWorksheet($advertiseSheet);
                    }

                    $imagePath2 = config('globals.APP_IMAGES_LOCALE_PATH').$advertise->image_advertisement_during_1;
                    if ($advertise->image_advertisement_during_1 !='' && file_exists($imagePath2))
                    {
                        $image2 = new Drawing();
                        $image2->setPath($imagePath1);
                        $image2->setCoordinates('I' . $g); // Cell coordinate where you want to insert the image
                        $image2->setWidth(300); // Set the width of the image (adjust as needed)
                        $image2->setHeight(300); // Set the height of the image (adjust as needed)
                        $image2->setWorksheet($advertiseSheet);
                    }

                    $imagePath1 = config('globals.APP_IMAGES_LOCALE_PATH').$advertise->image_advertisement_after_1;
                    if ($advertise->image_advertisement_after_1 !='' && file_exists($imagePath1))
                    {
                        $image1 = new Drawing();
                        $image1->setPath($imagePath1);
                        $image1->setCoordinates('P' . $g); // Cell coordinate where you want to insert the image
                        $image1->setWidth(300); // Set the width of the image (adjust as needed)
                        $image1->setHeight(300); // Set the height of the image (adjust as needed)
                        $image1->setWorksheet($advertiseSheet);
                    }

                    $g += 16;
                    // return $g;
                    $advertiseSheet->mergeCells('B'.$g.':O'.$g+3);

                    $cellValue = "SR : {$sr}\n ID : FP-{$advertise->id}\n COORDINATE : {$advertise->y} , {$advertise->x}\n TARIKH RONDAAN : {$advertise->visit_date}";
                    $advertiseSheet->getCell('B' . $g)->setValue($cellValue);
                    // $gateWorkSheet->getCell('B' . $g)->setValue("sdasd\ndsadasd\n");
                    // $spreadsheet->getActiveSheet()->getStyle('B' . $g)->getAlignment()->setWrapText(true);

                    $g += 5;
                    $sr++;

                }

                $filename = "FEEDER_PILLAR_PEMBERSIHAN {$req->ba} {$req->from_date} - {$req->to_date} ".rand(2,10000).'.xlsx';

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
        return  $data;
    }
}
