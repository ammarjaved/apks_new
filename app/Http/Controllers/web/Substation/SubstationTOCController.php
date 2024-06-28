<?php

namespace App\Http\Controllers\web\Substation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Substation;
use App\Traits\Filter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use App\Models\WorkPackage;

class SubstationTOCController extends Controller
{
    //

    use Filter;

    public function generateTOC(Request $req)
    {
        try
        {
            $data = Substation::query();
            $data = $this->filter($data , 'visit_date' , $req)->where('qa_status', 'Accept');

            if ($req->filled('workPackages'))
            {
                // Fetch the geometry of the work package
                $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');

                // Execute the query
                $data = $data ->join('tbl_substation_geom as g', 'tbl_substation.geom_id', '=', 'g.id');
                $data = $data->whereRaw('ST_Within(g.geom, ?)', [$workPackageGeom]);

            }
            $totalCounts = $data->select('visit_date' , 'name')->orderBy('visit_date')->get();

            if ($totalCounts)
            {

                $spreadsheet = new Spreadsheet();

                $workSheet =  $spreadsheet->getActiveSheet();

                $workSheet->getColumnDimension('B')->setWidth(15);
                $workSheet->getColumnDimension('D')->setWidth(60);
                $workSheet->mergeCells('B2:D2');

                $workSheet->setCellValue('B2', "{$req->ba} LKS ( {$req->from_date} - {$req->to_date} )");

                $workSheet->getStyle('B2')->getFont()->setSize(26)->setBold(true);
                $workSheet->mergeCells('B4:D4');

                $workSheet->setCellValue('B4', "JUMLAH YANG DICATAT");
                $workSheet->getStyle('B2')->getFont()->setSize(26)->setBold(true);

                $workSheet->setCellValue('B5', "TARIKH");
                $workSheet->setCellValue('C5', "JUMLAH");
                $workSheet->setCellValue('D5', "PENCAWANG");
                // $workSheet->getStyle('B5:D5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('5b5b5b');

                $i = 6;
                $preVisitDate = '';
                $startCell = 6;
                $totalRec = 0 ;
                foreach ($totalCounts as $count)
                {

                    // $workSheet->setCellValue('B'.$i, $count->visit_date);
                    // $workSheet->setCellValue('C'.$i, $count->visit_date);
                    $workSheet->setCellValue('D'.$i, $count->name);
                    $totalRec++;
                    if ($preVisitDate != '' && $preVisitDate != $count->visit_date)
                    {
                        $workSheet->mergeCells('B'.$startCell.':B'.$i);
                        $workSheet->mergeCells('C'.$startCell.':C'.$i);

                        $workSheet->setCellValue('B'.$startCell, $count->visit_date);
                        $workSheet->setCellValue('C'.$startCell, $totalRec);

                        // $workSheet->getStyle('B'.$startCell.':C'.$i)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('5b5b5b');

                        $totalRec = 0;
                        $startCell = $i +1;


                    }
                    $preVisitDate = $count->visit_date;
                    $i++;

                }




                // Save the spreadsheet to a file
                $writer = new Xlsx($spreadsheet);

                $filename = "PENCAWANG_TOC {$req->ba} {$req->from_date} - {$req->to_date} ".rand(2,10000).'.xlsx';
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
            return redirect()->back()->with('failed', 'Request Failed '. $th->getMessage());
        }
    }
}
