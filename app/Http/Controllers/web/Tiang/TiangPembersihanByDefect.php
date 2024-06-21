<?php

namespace App\Http\Controllers\web\Tiang;

use App\Constants\TiangConstants;
use App\Http\Controllers\Controller;
use App\Models\Tiang;
use App\Traits\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\WorkPackage;

class TiangPembersihanByDefect extends Controller
{
    use Filter;

    public function pembersihan(Request $req) {


        try {
            if (empty($req->defect)) {
                return redirect()->back()->with('failed', 'Request Failed Select Defect');
            }
         $defect = TiangConstants::TIANG_DEFECTS_DB_NAME[$req->defect];
        $images = array_slice(TiangConstants::TIANG_IMAGES , 0 , 5 , true);

        // return $defect;


        // $query = DB::table('tbl_savr_geom')
        //             ->join('tbl_savr', 'tbl_savr_geom.id', '=', 'tbl_savr.geom_id')
        //             ->whereRaw("ST_Intersects(tbl_savr_geom.geom, ST_GeomFromGeoJSON(?))", [$request->json])


        $query = $this->filter(Tiang::query() , 'review_date' , $req)
                    ->where('qa_status', 'Accept')
                    ->whereRaw("($defect)::text IN ('true', 'Yes')");

                    if ($req->filled('workPackages'))
                    {
                        // Fetch the geometry of the work package
                        $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');

                        // Execute the query
                        $query = $query
                            ->join('tbl_savr_geom as g', 'tbl_savr.geom_id', '=', 'g.id')
                            ->whereRaw('ST_Within(g.geom, ?)', [$workPackageGeom]);

                    }
        $totalCounts = clone $query;
         $totalCounts = $totalCounts->selectRaw('tbl_savr.review_date, COUNT(*) as count')
                    ->groupBy('tbl_savr.review_date')->orderBy('tbl_savr.review_date')
                    ->get();


                    // Create xlsx file
        $spreadsheet = new Spreadsheet();

        $worksheet = $spreadsheet->getActiveSheet();


        $worksheet->getColumnDimension('A')->setWidth(20);
        $worksheet->getColumnDimension('B')->setWidth(20);
        $worksheet->mergeCells('A1:B1');

        $worksheet->setCellValue('A1' ,"PEMBERSIHAN TIANG ".strtoupper($req->defect)." ( {$req->from_date} - {$req->to_date} )");
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
            $worksheet->setCellValue('A' . $i, $count->review_date);
            $worksheet->setCellValue('B' . $i, $count->count);
            $worksheet->getStyle('A' . $i . ':B' . $i)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);
            $i++;

            $defectsCounts += $count->count;
        }

        $worksheet->setCellValue('A' . $i, 'JUMLAH');
        $worksheet->setCellValue('B' . $i, $defectsCounts);
        $worksheet->getStyle('A4' . ':B' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $advertisePoster = $query->select("tbl_savr.pole_image_1" , "tbl_savr.pole_image_2" ,"tbl_savr.pole_image_3" ,"tbl_savr.pole_image_4" , "tbl_savr.pole_image_5",'tbl_savr.id','tbl_savr.review_date' , DB::raw('ST_X(tbl_savr.geom) as x' ), DB::raw('ST_Y(tbl_savr.geom) as y'))->orderBy('tbl_savr.review_date')->get();


        $advertiseSheet = $spreadsheet->createSheet();
                $advertiseSheet->setTitle( strtoupper($req->defect));
                $g = 4;
                $sr = 1;

                $imageDestiantionPath = config('globals.APP_IMAGES_LOCALE_PATH');
                foreach ($advertisePoster as $advertise)
                {
                    $advertiseSheet->mergeCells('B'.$g.':H'.$g);
                    $advertiseSheet->setCellValue('B'.$g, 'GAMBAR 1');

                    $advertiseSheet->mergeCells('I'.$g.':O'.$g);
                    $advertiseSheet->setCellValue('I'.$g , 'GAMBAR 2');

                    $advertiseSheet->mergeCells('P'.$g.':V'.$g);
                    $advertiseSheet->setCellValue('P'.$g , 'GAMBAR 3');

                    $advertiseSheet->mergeCells('W'.$g.':AC'.$g);
                    $advertiseSheet->setCellValue('W'.$g , 'GAMBAR 4');

                    $advertiseSheet->mergeCells('AD'.$g.':AJ'.$g);
                    $advertiseSheet->setCellValue('AD'.$g , 'GAMBAR 5');


                    $g++;
                    // Add image to cell
                    $k = $g +15;
                    $advertiseSheet->mergeCells('B'.$g.':H'.$k);
                    $advertiseSheet->mergeCells('I'.$g.':O'.$k);
                    $advertiseSheet->mergeCells('P'.$g.':V'.$k);
                    $advertiseSheet->mergeCells('W'.$g.':AC'.$k);
                    $advertiseSheet->mergeCells('AD'.$g.':AJ'.$k);

                    $imagePath = $imageDestiantionPath.$advertise->pole_image_1;
                    if ($advertise->pole_image_1 != '' && file_exists($imagePath))
                    {
                        $image = new Drawing();
                        $image->setPath($imagePath);
                        $image->setCoordinates('B' . $g); // Cell coordinate where you want to insert the image
                        $image->setWidth(300); // Set the width of the image (adjust as needed)
                        $image->setHeight(300); // Set the height of the image (adjust as needed)
                        $image->setWorksheet($advertiseSheet);
                    }

                    $imagePath1 = $imageDestiantionPath.$advertise->pole_image_2;
                    if ($advertise->pole_image_2 !='' && file_exists($imagePath1))
                    {
                        $image1 = new Drawing();
                        $image1->setPath($imagePath1);
                        $image1->setCoordinates('I' . $g); // Cell coordinate where you want to insert the image
                        $image1->setWidth(300); // Set the width of the image (adjust as needed)
                        $image1->setHeight(300); // Set the height of the image (adjust as needed)
                        $image1->setWorksheet($advertiseSheet);
                    }

                    $imagePath2 = $imageDestiantionPath.$advertise->pole_image_3;
                    if ($advertise->pole_image_3 !='' && file_exists($imagePath2))
                    {
                        $image1 = new Drawing();
                        $image1->setPath($imagePath2);
                        $image1->setCoordinates('I' . $g); // Cell coordinate where you want to insert the image
                        $image1->setWidth(300); // Set the width of the image (adjust as needed)
                        $image1->setHeight(300); // Set the height of the image (adjust as needed)
                        $image1->setWorksheet($advertiseSheet);
                    }

                    $imagePath3 = $imageDestiantionPath.$advertise->pole_image_4;
                    if ($advertise->pole_image_4 !='' && file_exists($imagePath3))
                    {
                        $image1 = new Drawing();
                        $image1->setPath($imagePath3);
                        $image1->setCoordinates('I' . $g); // Cell coordinate where you want to insert the image
                        $image1->setWidth(300); // Set the width of the image (adjust as needed)
                        $image1->setHeight(300); // Set the height of the image (adjust as needed)
                        $image1->setWorksheet($advertiseSheet);
                    }

                    $imagePath4 = $imageDestiantionPath.$advertise->pole_image_5;
                    if ($advertise->pole_image_5 !='' && file_exists($imagePath4))
                    {
                        $image1 = new Drawing();
                        $image1->setPath($imagePath4);
                        $image1->setCoordinates('I' . $g); // Cell coordinate where you want to insert the image
                        $image1->setWidth(300); // Set the width of the image (adjust as needed)
                        $image1->setHeight(300); // Set the height of the image (adjust as needed)
                        $image1->setWorksheet($advertiseSheet);
                    }

                    $g += 16;
                    // return $g;
                    $advertiseSheet->mergeCells('B'.$g.':AJ'.$g+3);

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
        //code...
        } catch (\Throwable $th) {
            return $th->getMessage();
            return redirect()->back()->with('failed', 'Request Failed');
        }


    }
}
