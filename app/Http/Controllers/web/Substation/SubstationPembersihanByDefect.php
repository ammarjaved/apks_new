<?php

namespace App\Http\Controllers\web\Substation;

use App\Constants\SubstationConstants;
use App\Http\Controllers\Controller;
use App\Models\Substation;
use App\Traits\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\WorkPackage;



class SubstationPembersihanByDefect extends Controller
{
    //
    use Filter;

    public function pembersihan(Request $req) {


        try {
            if (empty($req->defect)) {
                return redirect()->back()->with('failed', 'Request Failed Select Defect');
            }
        $defect = SubstationConstants::PE_DEFECTS_DB_NAME[$req->defect];
        $images = SubstationConstants::PE_DEFECTS_AND_IMAGES[$req->defect];

        // return $defect;
        $query = $this->filter(Substation::query() , 'visit_date' , $req)
                    ->where('qa_status', 'Accept')
                    ->whereNotNull($images[0])
                    ->whereNotNull($images[1])
                    ->whereRaw("($defect)::text IN ('true', 'Yes')");

        $query = $query ->join('tbl_substation_geom as g', 'tbl_substation.geom_id', '=', 'g.id');

        if ($req->filled('workPackages'))
        {
            // Fetch the geometry of the work package
            $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');
            $query = $query->whereRaw('ST_Within(g.geom, ?)', [$workPackageGeom]);

        }
        $totalCounts = clone $query;
        $totalCounts = $totalCounts->selectRaw('visit_date, COUNT(*) as count')
                    ->groupBy('visit_date')->orderBy('visit_date')
                    ->get();


                    // Create xlsx file
        $spreadsheet = new Spreadsheet();

        $worksheet = $spreadsheet->getActiveSheet();


        $worksheet->getColumnDimension('A')->setWidth(20);
        $worksheet->getColumnDimension('B')->setWidth(20);
        $worksheet->mergeCells('A1:B1');

        $worksheet->setCellValue('A1' ,"PEMBERSIHAN SUBSTATION ".strtoupper($req->defect)." ( {$req->from_date} - {$req->to_date} )");
        $worksheet->getStyle('A1')->getFont()->setBold(true);

        $worksheet->setCellValue('A3' ,"TARIKH");
        $worksheet->setCellValue('B3', strtoupper($req->defect));


        $i = 4;

        $color = '';
        $defectsCounts = 0;

        foreach ($totalCounts as $count)
        {
            if ($i %2 == 0) {
                $color = 'a8a8a8';
            }
            else{
                $color = 'CCCCCC';
            }
            $worksheet->setCellValue('A' . $i, $count->visit_date);
            $worksheet->setCellValue('B' . $i, $count->count);
            $worksheet->getStyle('A' . $i . ':B' . $i)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);
            $i++;

            $defectsCounts += $count->count;
        }

        $worksheet->setCellValue('A' . $i, 'JUMLAH');
        $worksheet->setCellValue('B' . $i, $defectsCounts);
        $worksheet->getStyle('A4' . ':B' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $advertisePoster = $query->select(
                                        "substation_image_1",
                                        "substation_image_2",
                                        "$images[0] as image_1",
                                        "$images[1] as image_2",
                                        'tbl_substation.id',
                                        'visit_date' ,
                                        DB::raw('ST_X(g.geom) as x' ),
                                        DB::raw('ST_Y(g.geom) as y')
                                    )
                                    ->orderBy('visit_date')
                                    ->get();


        $advertiseSheet = $spreadsheet->createSheet();
                $advertiseSheet->setTitle( strtoupper($req->defect));
                $g = 4;
                $sr = 1;
                foreach ($advertisePoster as $advertise)
                {



                    $advertiseSheet->mergeCells('B'.$g.':H'.$g);
                    $advertiseSheet->setCellValue('B'.$g , 'SUBSTATION 1');

                    $advertiseSheet->mergeCells('I'.$g.':O'.$g);
                    $advertiseSheet->setCellValue('I'.$g , 'SUBSTATION 2');

                    $advertiseSheet->mergeCells('P'.$g.':V'.$g);
                    $advertiseSheet->setCellValue('P'.$g, 'SEBELUM');

                    $advertiseSheet->mergeCells('W'.$g.':AC'.$g);
                    $advertiseSheet->setCellValue('W'.$g , 'SELEPAS');

                    $g++;
                    // Add image to cell
                    $k = $g +15;
                    $advertiseSheet->mergeCells('B'.$g.':H'.$k);
                    $advertiseSheet->mergeCells('I'.$g.':O'.$k);

                    $subImagePath = config('globals.APP_IMAGES_LOCALE_PATH').$advertise->substation_image_1;
                    if ($advertise->substation_image_1 != '' && file_exists($subImagePath))
                    {

                        $image = new Drawing();
                        $image->setPath($subImagePath);
                        $image->setCoordinates('B' . $g); // Cell coordinate where you want to insert the image
                        $image->setWidth(300); // Set the width of the image (adjust as needed)
                        $image->setHeight(300); // Set the height of the image (adjust as needed)
                        $image->setWorksheet($advertiseSheet);
                    }

                    $subImagePath1 = config('globals.APP_IMAGES_LOCALE_PATH').$advertise->substation_image_2;
                    if ($advertise->substation_image_2 != '' && file_exists($subImagePath1))
                    {
                        $image = new Drawing();
                        $image->setPath($subImagePath1);
                        $image->setCoordinates('I' . $g); // Cell coordinate where you want to insert the image
                        $image->setWidth(300); // Set the width of the image (adjust as needed)
                        $image->setHeight(300); // Set the height of the image (adjust as needed)
                        $image->setWorksheet($advertiseSheet);
                    }


                    $imagePath = config('globals.APP_IMAGES_LOCALE_PATH').$advertise->image_1;
                    if ($advertise->image_1 != '' && file_exists($imagePath))
                    {
                        $advertiseSheet->mergeCells('P'.$g.':V'.$k);
                        $image = new Drawing();
                        $image->setPath($imagePath);
                        $image->setCoordinates('P' . $g); // Cell coordinate where you want to insert the image
                        $image->setWidth(300); // Set the width of the image (adjust as needed)
                        $image->setHeight(300); // Set the height of the image (adjust as needed)
                        $image->setWorksheet($advertiseSheet);
                    }

                    $imagePath1 = config('globals.APP_IMAGES_LOCALE_PATH').$advertise->image_2;
                    if ($advertise->image_2 !='' && file_exists($imagePath1) )
                    {

                        $advertiseSheet->mergeCells('W'.$g.':AC'.$k);
                        $image1 = new Drawing();
                        $image1->setPath($imagePath1);
                        $image1->setCoordinates('W' . $g); // Cell coordinate where you want to insert the image
                        $image1->setWidth(300); // Set the width of the image (adjust as needed)
                        $image1->setHeight(300); // Set the height of the image (adjust as needed)
                        $image1->setWorksheet($advertiseSheet);
                    }
                    // return $advertise->image_2;
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


            $writer = new Xlsx($spreadsheet);

            $filename = "TIANG_PEMBERSIHAN {$req->ba} {$req->from_date} - {$req->to_date} ".rand(2,10000).'.xlsx';
            $writer->save(public_path('assets/updated-excels/') . $filename);
            return response()->download(public_path('assets/updated-excels/') . $filename)->deleteFileAfterSend(true);
        //code...
        } catch (\Throwable $th) {
            return $th->getMessage();
            return redirect()->back()->with('failed', 'Request Failed');
        }


    }
}
