<?php


namespace App\Http\Controllers\web\SavrFFA;

use App\Http\Controllers\Controller;
use App\Models\SavrFfaSub1;
use App\Traits\Filter;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FFAExcelController extends Controller
{
    //
    use Filter;


    public function generateFFAExcel(Request $req)
    {
        try
        {
           // return $req;
            $result = SavrFfaSub1::query();
            $result = $this->filter($result , 'visit_date',$req);

            $result = $result->whereNotNull('visit_date')->select('*', DB::raw('ST_X(geom) as x'), DB::raw('ST_Y(geom) as y'))->get();
            // return $req;
            if ($result)
            {
                $excelFile = public_path('assets/excel-template/ffa_sk5.xlsx');
                $spreadsheet = IOFactory::load($excelFile);
                $worksheet = $spreadsheet->getSheet(0);

                $i = 4;
                foreach ($result as $rec)
                {
                 //   return $rec;
                     $coords=$rec->x.','.$rec->y;

                    $worksheet->setCellValue('A' . $i, $i - 3);
                    $worksheet->setCellValue('B' . $i, $rec->pole_id);
                    $worksheet->setCellValue('C' . $i, $rec->ba);
                    $worksheet->setCellValue('D' . $i, $rec->visit_date);
                    $worksheet->setCellValue('E' . $i,  $rec->nama_jalan);
                    $worksheet->setCellValue('F' . $i,  $rec->pole_no);

                    try {
                        $worksheet->setCellValue('G' . $i, $coords);
                    } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                        echo "Error setting cell G$i: " . $e->getMessage();
                    }
                    $worksheet->setCellValue('H' . $i, 'Yes');
                    $worksheet->setCellValue('I' . $i, $rec->id);
                    $worksheet->setCellValue('J' . $i, $rec->house_number);
                    $worksheet->setCellValue('K' . $i, $rec->wayar_tertanggal);
                    $worksheet->setCellValue('L' . $i, $rec->joint_box);
                    $worksheet->setCellValue('M' . $i, $rec->ipc_terbakar);
                    $worksheet->setCellValue('N' . $i, $rec->house_renovation);
                    $worksheet->setCellValue('O' . $i, $rec->other);
                    $worksheet->setCellValue('P' . $i, $rec->house_image);
                    $worksheet->setCellValue('Q' . $i, $rec->image2);
                    $worksheet->setCellValue('S' . $i, $rec->image3);
                    // $worksheet->setCellValue('U' . $i, $rec->bare_panjang_meter);

                    $i++;
                }



                //   return   $worksheet;

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                $filename = "sk5 ( $req->to_date - $req->from_date ) ".rand(2,10000).".xlsx";
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
