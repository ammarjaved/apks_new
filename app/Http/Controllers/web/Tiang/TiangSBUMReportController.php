<?php

namespace App\Http\Controllers\web\Tiang;

use App\Http\Controllers\Controller;
use App\Models\Tiang;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TiangSBUMReportController extends Controller
{
    //
    public function generateSBUMReport(Request $req)
    {
        try {
            $query = Tiang::selectRaw(
                "
        SUM(CASE WHEN talian_utama_connection = 'm,s' THEN 1 ELSE 0 END) as count_m_s,
        SUM(CASE WHEN talian_utama_connection = 's' THEN 1 ELSE 0 END) as count_s,
        SUM(CASE WHEN (tiang_defect->>'cracked')::text = 'true' THEN 1 ELSE 0 END) as tiang_cracked_reput_retak,
        SUM(CASE WHEN (tiang_defect->>'leaning')::text = 'true' THEN 1 ELSE 0 END) as tiang_leaning_condong,
        SUM(CASE WHEN (tiang_defect->>'dim')::text = 'true' THEN 1 ELSE 0 END) as tiang_dim_nombor_pudar_tiada,

        SUM(CASE WHEN (talian_defect->>'joint')::text = 'true' THEN 1 ELSE 0 END) as talian_joint_murku_joint,
        SUM(CASE WHEN (talian_defect->>'ground')::text = 'true' THEN 1 ELSE 0 END) as talian_ground,
        SUM(CASE WHEN (talian_defect->>'need_rentis')::text = 'true' THEN 1 ELSE 0 END) as talian_need_rentis_perlu_rentis
    ",
            )->first();

            if ($query) {
                $excelFile = public_path('assets/excel-template/SBUM-template.xlsx');

                $spreadsheet = IOFactory::load($excelFile);

                $worksheet = $spreadsheet->getSheet(0);

                $worksheet->setCellValue('L6', $query->count_m_s);
                $worksheet->setCellValue('L7', $query->count_s);
                $worksheet->setCellValue('L13', $query->tiang_cracked_reput_retak);

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                $filename = 'SBUM-tiang-talian - ' . $req->ba . ' - ' . $req->from_date . ' - ' . $req->to_date . ' ' . rand(2, 10000) . '.xlsx';
                $writer->save(public_path('assets/updated-excels/') . $filename);
                return response()
                    ->download(public_path('assets/updated-excels/') . $filename)
                    ->deleteFileAfterSend(true);
            } else {
                return redirect()->back()->with('failed', 'No records found ');
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
            return redirect()->back()->with('failed', 'Request Failed');
        }
    }
}
