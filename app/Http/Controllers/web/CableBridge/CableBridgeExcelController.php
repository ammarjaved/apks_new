<?php

namespace App\Http\Controllers\web\CableBridge;

use App\Http\Controllers\Controller;
use App\Models\CableBridge;
use App\Traits\Filter;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CableBridgeExcelController extends Controller
{
    use Filter;
    
    public function generateCableBridgeExcel(Request $req)
    {
        try
        {
            $result = CableBridge::query();
            $result = $this->filter($result , 'visit_date',$req);
            $result = $result->whereNotNull('visit_date')->select('*', DB::raw("st_x(geom) as x") ,DB::raw("st_y(geom) as y"))->get();
 
            if ($result) 
            {
                $excelFile = public_path('assets/excel-template/cable-bridge.xlsx');
                $spreadsheet = IOFactory::load($excelFile);
                $worksheet = $spreadsheet->getActiveSheet();

                $i = 4;
                foreach ($result as $rec) 
                {
                    $worksheet->setCellValue('A' . $i, $i - 3);
                    $worksheet->setCellValue('B' . $i, $rec->zone);
                    $worksheet->setCellValue('C' . $i, $rec->ba);
                    $worksheet->setCellValue('D' . $i, $rec->team);
                    $worksheet->setCellValue('E' . $i, date('Y-m-d', strtotime($rec->visit_date))  );
                    $worksheet->setCellValue('F' . $i, date('H:i:s', strtotime($rec->patrol_time)) );
                    $worksheet->setCellValue('G' . $i, $rec->feeder_involved);
                    $worksheet->setCellValue('H' . $i, $rec->aera);
                    $worksheet->setCellValue('I' . $i, $rec->start_date);
                    $worksheet->setCellValue('J' . $i, $rec->end_date);
                    $worksheet->setCellValue('K' . $i, $rec->voltage);
                    $worksheet->setCellValue('L' . $i, $rec->y. ' ,'.$rec->x);
                    $worksheet->setCellValue('M' . $i, $rec->danger_sign);
                    $worksheet->setCellValue('N' . $i, $rec->anti_crossing_device);
                    $worksheet->setCellValue('O' . $i, $rec->vandalism_status);
                    $worksheet->setCellValue('P' . $i, $rec->kebersihan_jabatan);
                    $worksheet->setCellValue('Q' . $i, $rec->pipe_staus);
                    $worksheet->setCellValue('R' . $i, $rec->collapsed_status);
                    $worksheet->setCellValue('S' . $i, $rec->condong);
                    $worksheet->setCellValue('T' . $i, $rec->rust_status);
                    $worksheet->setCellValue('U' . $i, '');
                    $worksheet->setCellValue('V' . $i, $rec->bushes_status);

                    $i++;
                }

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                
                $filename = 'qr-cable-bridge'.rand(2,10000).'.xlsx';
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
            return redirect()->back()->with('failed', 'Request Failed');
        }
    }

}
