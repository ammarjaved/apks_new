<?php

namespace App\Http\Controllers\web\FeederPillar;

use App\Constants\FeederPillarConstants;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeederPillar;
use App\Traits\Filter;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class FeederPillarPembersihanByDefect extends Controller
{
    use Filter;

    public function pembersihan(Request $req)
    {
        try {
            if (empty($req->defect)) {
                return redirect()->back()->with('failed', 'Request Failed Select Defect');
            }

            $defect = FeederPillarConstants::FP_DEFECTS_DB_NAME[$req->defect];
            $images = FeederPillarConstants::FP_DEFECTS_AND_IMAGES[$req->defect];

            // return $defect;
            $query = $this->filter(FeederPillar::query(), 'visit_date', $req)
                ->where('qa_status', 'Accept')
                ->whereNotNull($images[0])
                ->whereNotNull($images[1])
                ->whereRaw("($defect)::text IN ('true', 'Yes')");
            $totalCounts = clone $query;
            $totalCounts = $totalCounts->selectRaw('visit_date, COUNT(*) as count')->groupBy('visit_date')->orderBy('visit_date')->get();

            // Create xlsx file
            $spreadsheet = new Spreadsheet();

            $worksheet = $spreadsheet->getActiveSheet();

            $worksheet->getColumnDimension('A')->setWidth(20);
            $worksheet->getColumnDimension('B')->setWidth(20);
            $worksheet->mergeCells('A1:B1');

            $worksheet->setCellValue('A1', 'PEMBERSIHAN FEEDER PILLAR ' . strtoupper($req->defect) . " ( {$req->from_date} - {$req->to_date} )");
            $worksheet->getStyle('A1')->getFont()->setBold(true);

            $worksheet->setCellValue('A3', 'TARIKH');
            $worksheet->setCellValue('B3', strtoupper($req->defect));

            $i = 4;

            $color = '';
            $defectsCounts = 0;
            foreach ($totalCounts as $count) {
                if ($i % 2 == 0) {
                    $color = 'a8a8a8';
                } else {
                    $color = 'CCCCCC';
                }
                $worksheet->setCellValue('A' . $i, $count->visit_date);
                $worksheet->setCellValue('B' . $i, $count->count);
                $worksheet
                    ->getStyle('A' . $i . ':B' . $i)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB($color);
                $i++;

                $defectsCounts += $count->count;
            }

            $worksheet->setCellValue('A' . $i, 'JUMLAH');
            $worksheet->setCellValue('B' . $i, $defectsCounts);
            $worksheet
                ->getStyle('A4' . ':B' . $i)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $advertisePoster = $query
                ->select("$images[0] as image_1", "$images[1] as image_2", 'id', 'visit_date', DB::raw('ST_X(geom) as x'), DB::raw('ST_Y(geom) as y'))
                ->orderBy('visit_date')
                ->get();

            $advertiseSheet = $spreadsheet->createSheet();
            $advertiseSheet->setTitle(strtoupper($req->defect));
            $g = 4;
            $sr = 1;
            foreach ($advertisePoster as $advertise) {

                $advertiseSheet->mergeCells('B' . $g . ':H' . $g);
                $advertiseSheet->setCellValue('B' . $g, 'SEBELUM');

                $advertiseSheet->mergeCells('I' . $g . ':O' . $g);
                $advertiseSheet->setCellValue('I' . $g, 'SELEPAS');

                $g++;
                // Add image to cell
                $k = $g + 15;
                $advertiseSheet->mergeCells('B' . $g . ':H' . $k);
                $advertiseSheet->mergeCells('I' . $g . ':O' . $k);

                $imagePath = config('globals.APP_IMAGES_LOCALE_PATH').$advertise->image_1;
                if ($advertise->image_1 != '' && file_exists($imagePath)) {
                    $image = new Drawing();
                    $image->setPath($imagePath);
                    $image->setCoordinates('B' . $g); // Cell coordinate where you want to insert the image
                    $image->setWidth(300); // Set the width of the image (adjust as needed)
                    $image->setHeight(300); // Set the height of the image (adjust as needed)
                    $image->setWorksheet($advertiseSheet);
                }

                $imagePath1 = config('globals.APP_IMAGES_LOCALE_PATH').$advertise->image_2;
                if ($advertise->image_2 != '' && file_exists($imagePath1)) {
                    $image1 = new Drawing();
                    $image1->setPath($imagePath1);
                    $image1->setCoordinates('I' . $g); // Cell coordinate where you want to insert the image
                    $image1->setWidth(300); // Set the width of the image (adjust as needed)
                    $image1->setHeight(300); // Set the height of the image (adjust as needed)
                    $image1->setWorksheet($advertiseSheet);
                }

                $g += 16;
                // return $g;
                $advertiseSheet->mergeCells('B' . $g . ':O' . $g + 3);

                $cellValue = "SR : {$sr}\n ID : FP-{$advertise->id}\n COORDINATE : {$advertise->y} , {$advertise->x}\n TARIKH RONDAAN : {$advertise->visit_date}";
                $advertiseSheet->getCell('B' . $g)->setValue($cellValue);
                // $gateWorkSheet->getCell('B' . $g)->setValue("sdasd\ndsadasd\n");
                // $spreadsheet->getActiveSheet()->getStyle('B' . $g)->getAlignment()->setWrapText(true);

                $g += 5;
                $sr++;
            }

            $writer = new Xlsx($spreadsheet);

            $filename = "TIANG_PEMBERSIHAN {$req->ba} {$req->from_date} - {$req->to_date} " . rand(2, 10000) . '.xlsx';
            $writer->save(public_path('assets/updated-excels/') . $filename);
            return response()
                ->download(public_path('assets/updated-excels/') . $filename)
                ->deleteFileAfterSend(true);
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', 'Request Failed');
        }
    }
}
