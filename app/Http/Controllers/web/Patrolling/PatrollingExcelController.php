<?php

namespace App\Http\Controllers\web\Patrolling;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patroling;
use App\Traits\Filter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PatrollingExcelController extends Controller
{
    use Filter;

    public function generateExcel(Request $req)
    {
        // return $req;
        try{
             
        $result = Patroling::query();

        $result = $this->filter($result , 'vist_date',$req);


        $result = $result->where('km' ,'!=' , '0')-> whereNotNull('vist_date')->select('*', DB::raw('ST_X(geom) as x'), DB::raw('ST_Y(geom) as y'))->get();

         
        if ($result) {
                $excelFile = public_path('assets/excel-template/patrolling-template.xlsx');


                $spreadsheet = IOFactory::load($excelFile);

                $worksheet = $spreadsheet->getActiveSheet();

                $i = 3;
                foreach ($result as $rec) {
                    $worksheet->setCellValue('A' . $i, $i - 2);
                    $worksheet->setCellValue('B' . $i, $rec->wp_name);
                    $worksheet->setCellValue('C' . $i, $rec->zone);
                    $worksheet->setCellValue('D' . $i, $rec->ba);
                    $worksheet->setCellValue('E' . $i, date('Y-m-d', strtotime($rec->date)));
                    $worksheet->setCellValue('F' . $i, date('H:i:s', strtotime($rec->time)));
                    $worksheet->setCellValue('G' . $i, number_format( $rec->km , 2));

                    $i++;
                }
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                $writer->save(public_path('assets/updated-excels/') . 'patrolling.xlsx');
                // return $recored;
               // ob_end_clean();
                return response()->download(public_path('assets/updated-excels/') . 'patrolling.xlsx');
            } else {
                return redirect()
                    ->back()
                    ->with('failed', 'No records found ');
            }
        } catch (\Throwable $th) {
        //    return $th->getMessage();
            return redirect()
                ->back()
                ->with('failed', 'Request Failed');
        }
    }
}
