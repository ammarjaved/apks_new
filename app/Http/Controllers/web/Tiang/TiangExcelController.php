<?php

namespace App\Http\Controllers\web\Tiang;

use App\Http\Controllers\Controller;
use App\Models\Tiang;
use App\Models\TiangRepairDate;
use App\Traits\Filter;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\WorkPackage;

class TiangExcelController extends Controller
{
    use Filter;

    public function generateTiangExcel(Request $req)
    {
        try {
            $startTime = microtime(true);
            ini_set('memory_limit', '4096M');
            set_time_limit(300);

            Log::info('Excel generation started');

            $ba = $req->filled('ba') ? $req->ba : Auth::user()->ba;
            $result = Tiang::query();
            $result = $this->filter($result, 'review_date', $req);

            $defectsImg = ['pole_image_1', 'pole_image_2', 'pole_image_3', 'pole_image_4', 'pole_image_5'];

            if ($req->filled('workPackages')) {
                $result = $result->join('tbl_savr_geom as g', 'tbl_savr.geom_id', '=', 'g.id');
                $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');
                $result = $result->whereRaw('ST_Within(g.geom, ?)', [$workPackageGeom]);
            }

            $queryStart = microtime(true);
            $res = $result->whereNotNull('review_date')->orderBy('fp_name')->get();
            Log::info('Records fetched', ['count' => $res->count(), 'time' => round(microtime(true) - $queryStart, 2) . 's']);

            $query = Tiang::select('fp_road as road')
                ->selectRaw("string_agg(distinct fp_name, ' , ') as fp_name")
                ->selectRaw("string_agg(distinct review_date::text, ' , ') as review_date")
                ->selectRaw("SUM(CASE WHEN size_tiang = '7.5' THEN 1 ELSE 0 END) as size_tiang_75")
                ->selectRaw("SUM(CASE WHEN size_tiang = '9' THEN 1 ELSE 0 END) as size_tiang_9")
                ->selectRaw("SUM(CASE WHEN size_tiang = '10' THEN 1 ELSE 0 END) as size_tiang_10")
                ->selectRaw("SUM(CASE WHEN jenis_tiang = 'iron' THEN 1 ELSE 0 END) as jenis_tiang_iron")
                ->selectRaw("SUM(CASE WHEN jenis_tiang = 'concrete' THEN 1 ELSE 0 END) as jenis_tiang_concrete")
                ->selectRaw("SUM(CASE WHEN jenis_tiang = 'spun' THEN 1 ELSE 0 END) as jenis_tiang_spun")
                ->selectRaw("SUM(CASE WHEN jenis_tiang = 'wood' THEN 1 ELSE 0 END) as jenis_tiang_wood")
                ->selectRaw("SUM(CASE WHEN (abc_span->'s3_185')::text <> '' AND (abc_span->'s3_185')::text <> 'null' AND (abc_span->'s3_185')::text <> '\"\"' THEN (abc_span->>'s3_185')::integer ELSE 0 END) as abc_s3186")
                ->selectRaw("SUM(CASE WHEN (abc_span->'s3_95')::text <> '' AND (abc_span->'s3_95')::text <> 'null' AND (abc_span->'s3_95')::text <> '\"\"' THEN (abc_span->>'s3_95')::integer ELSE 0 END) as abc_s3195")
                ->selectRaw("SUM(CASE WHEN (abc_span->'s3_16')::text <> '' AND (abc_span->'s3_16')::text <> 'null' AND (abc_span->'s3_16')::text <> '\"\"' THEN (abc_span->>'s3_16')::integer ELSE 0 END) as abc_s316")
                ->selectRaw("SUM(CASE WHEN (abc_span->'s1_16')::text <> '' AND (abc_span->'s1_16')::text <> 'null' AND (abc_span->'s1_16')::text <> '\"\"' THEN (abc_span->>'s1_16')::integer ELSE 0 END) as abc_s116")
                ->selectRaw("SUM(CASE WHEN (pvc_span->'s19_064')::text <> '' AND (pvc_span->'s19_064')::text <> 'null' AND (pvc_span->'s19_064')::text <> '\"\"' THEN (pvc_span->>'s19_064')::integer ELSE 0 END) as pvc_s9064")
                ->selectRaw("SUM(CASE WHEN (pvc_span->'s7_083')::text <> '' AND (pvc_span->'s7_083')::text <> 'null' AND (pvc_span->'s7_083')::text <> '\"\"' THEN (pvc_span->>'s7_083')::integer ELSE 0 END) as pvc_s7083")
                ->selectRaw("SUM(CASE WHEN (pvc_span->'s7_044')::text <> '' AND (pvc_span->'s7_044')::text <> 'null' AND (pvc_span->'s7_044')::text <> '\"\"' THEN (pvc_span->>'s7_044')::integer ELSE 0 END) as pvc_s7044")
                ->selectRaw("SUM(CASE WHEN (bare_span->'s7_173')::text <> '' AND (bare_span->'s7_173')::text <> 'null' AND (bare_span->'s7_173')::text <> '\"\"' THEN (bare_span->>'s7_173')::integer ELSE 0 END) as bare_s7173")
                ->selectRaw("SUM(CASE WHEN (bare_span->'s7_122')::text <> '' AND (bare_span->'s7_122')::text <> 'null' AND (bare_span->'s7_122')::text <> '\"\"' THEN (bare_span->>'s7_122')::integer ELSE 0 END) as bare_s7122")
                ->selectRaw("SUM(CASE WHEN (bare_span->'s3_132')::text <> '' AND (bare_span->'s3_132')::text <> 'null' AND (bare_span->'s3_132')::text <> '\"\"' THEN (bare_span->>'s3_132')::integer ELSE 0 END) as bare_s7132")
                ->selectRaw("SUM(COALESCE(NULLIF(bil_black_box, '')::integer, 0)) AS blackbox")
                ->selectRaw("SUM(COALESCE(NULLIF(bil_lvpt, '')::integer, 0)) AS ipc")
                ->selectRaw("SUM(COALESCE(NULLIF(bil_umbang, '')::integer, 0)) AS umbagan")
                ->selectRaw("SUM(CASE WHEN (talian_utama_connection)::text = 'one' THEN 1 ELSE 0 END) as service")
                ->selectRaw("SUM(CASE WHEN talian_utama_connection = 'main_line' THEN 1 ELSE 0 END) as main_line_count")
                ->selectRaw("MIN(section_from) as section_from")
                ->selectRaw("MAX(section_to) as section_to")
                ->selectRaw("string_agg(DISTINCT geom_id::text, ',') as geom_ids")
                ->whereNotNull('review_date')
                ->whereNotNull('fp_road');

            $query = $this->filter($query, 'review_date', $req);

            if ($req->filled('workPackages')) {
                $query = $query->join('tbl_savr_geom as g', 'tbl_savr.geom_id', '=', 'g.id');
                $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');
                $query = $query->whereRaw('ST_Within(g.geom, ?)', [$workPackageGeom]);
            }

            $roadStatistics = $query->groupBy('fp_road')->get();
            Log::info('Road statistics calculated', ['count' => $roadStatistics->count()]);

            if ($roadStatistics->isEmpty()) {
                return redirect()->back()->with('failed', 'No records found');
            }

            $savrIds = $res->pluck('id')->toArray();
            $repairStart = microtime(true);
            $repairDates = TiangRepairDate::whereIn('savr_id', $savrIds)
                ->select('savr_id', 'name', 'date')
                ->get()
                ->groupBy('savr_id');
            Log::info('Repair dates fetched', ['time' => round(microtime(true) - $repairStart, 2) . 's']);

            $reviewDatesByGeomCycle = [];
            if ($req->filled('cycle') && $req->cycle > 1) {
                $geomIds = $res->pluck('geom_id')->unique()->toArray();
                $cycles = range(1, $req->cycle - 1);

                $reviewStart = microtime(true);
                $reviewDatesByGeomCycle = Tiang::whereIn('geom_id', $geomIds)
                    ->whereIn('cycle', $cycles)
                    ->select('geom_id', 'cycle', 'review_date')
                    ->get()
                    ->groupBy(function($item) {
                        return $item->geom_id . '_' . $item->cycle;
                    })
                    ->map(function($group) {
                        return $group->first()->review_date;
                    });
                Log::info('Review dates fetched', ['time' => round(microtime(true) - $reviewStart, 2) . 's']);
            }

            if ($ba == 'KUALA LUMPUR PUSAT') {
                Log::info('Generating KLP Excel', ['total_time' => round(microtime(true) - $startTime, 2) . 's']);
                return $this->generateTiangKLPExcel($roadStatistics, $res, $ba, $defectsImg, $req, $repairDates, $reviewDatesByGeomCycle);
            }

            Log::info('Generating Standard Excel', ['total_time' => round(microtime(true) - $startTime, 2) . 's']);
            return $this->generateStandardExcel($roadStatistics, $res, $ba, $defectsImg, $repairDates);

        } catch (\Throwable $th) {
            Log::error('Excel Generation Error: ' . $th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()->back()->with('failed', 'Request Failed: ' . $th->getMessage());
        }
    }

    private function generateStandardExcel($roadStatistics, $res, $ba, $defectsImg, $repairDates)
    {
        $excelFile = public_path('assets/excel-template/QR TIANG.xlsx');
        $spreadsheet = IOFactory::load($excelFile);
        $worksheet = $spreadsheet->getSheet(0);

        $worksheet->getStyle('B:AK')->getAlignment()->setHorizontal('center');
        $worksheet->getStyle('B:AL')->getFont()->setSize(9);
        $worksheet->setCellValue('D4', $ba);

        $i = 5;
        foreach ($roadStatistics as $rec) {
            $spanCount = $rec->abc_s3186 + $rec->abc_s3195 + $rec->abc_s316 + $rec->abc_s116 +
                        $rec->pvc_s9064 + $rec->pvc_s7083 + $rec->pvc_s7044 +
                        $rec->bare_s7173 + $rec->bare_s7122 + $rec->bare_s7132;

            $worksheet->setCellValue('B' . $i, $i - 4);
            $worksheet->setCellValue('G' . $i, $rec->fp_name);
            $worksheet->setCellValue('H' . $i, $rec->road);
            $worksheet->setCellValue('I' . $i, $rec->section_from);
            $worksheet->setCellValue('J' . $i, $rec->section_to);
            $worksheet->setCellValue('K' . $i, $rec->size_tiang_75);
            $worksheet->setCellValue('L' . $i, $rec->size_tiang_9);
            $worksheet->setCellValue('M' . $i, $rec->size_tiang_10);
            $worksheet->setCellValue('N' . $i, $rec->jenis_tiang_spun);
            $worksheet->setCellValue('O' . $i, $rec->jenis_tiang_concrete);
            $worksheet->setCellValue('P' . $i, $rec->jenis_tiang_iron);
            $worksheet->setCellValue('Q' . $i, $rec->jenis_tiang_wood);
            $worksheet->setCellValue('R' . $i, $rec->abc_s3186);
            $worksheet->setCellValue('S' . $i, $rec->abc_s3195);
            $worksheet->setCellValue('T' . $i, $rec->abc_s316);
            $worksheet->setCellValue('U' . $i, $rec->abc_s116);
            $worksheet->setCellValue('V' . $i, $rec->pvc_s9064);
            $worksheet->setCellValue('W' . $i, $rec->pvc_s7083);
            $worksheet->setCellValue('X' . $i, $rec->pvc_s7044);
            $worksheet->setCellValue('Y' . $i, $rec->bare_s7173);
            $worksheet->setCellValue('Z' . $i, $rec->bare_s7122);
            $worksheet->setCellValue('AA' . $i, $rec->bare_s7132);
            $worksheet->setCellValue('AB' . $i, $spanCount);
            $worksheet->setCellValue('AC' . $i, $rec->main_line_count > 0 ? 'M' : 'S');
            $worksheet->setCellValue('AD' . $i, $rec->umbagan);
            $worksheet->setCellValue('AE' . $i, $rec->blackbox);
            $worksheet->setCellValue('AF' . $i, $rec->ipc);
            $worksheet->setCellValue('AG' . $i, $rec->service);
            $worksheet->setCellValue('AI' . $i, 'AEROSYNERGY');
            $i++;
        }

        // Sheet 2
        $secondWorksheet = $spreadsheet->getSheet(1);
        $secondWorksheet->getStyle('B:AL')->getAlignment()->setHorizontal('center');
        $secondWorksheet->getStyle('B:AL')->getFont()->setSize(9);
        $secondWorksheet->setCellValue('C1', $ba);
        $secondWorksheet->setCellValue('B3', 'Tarikh Pemeriksaan : ' . date('Y-m-d'));

        $i = 9;
        $imageBaseUrl = config('globals.APP_IMAGES_URL');

        foreach ($res as $secondRec) {
            $other_defects = '';

            $secondWorksheet->setCellValue('A' . $i, $i - 8);
            $secondWorksheet->setCellValue('F' . $i, $secondRec->fp_name);
            $secondWorksheet->setCellValue('G' . $i, $secondRec->fp_road);
            $secondWorksheet->setCellValue('H' . $i, $secondRec->section_from);
            $secondWorksheet->setCellValue('I' . $i, $secondRec->section_to);
            $secondWorksheet->setCellValue('J' . $i, $secondRec->tiang_no);

            if ($secondRec->tiang_defect) {
                $tiang_defect = json_decode($secondRec->tiang_defect);
                $secondWorksheet->setCellValue('K' . $i, excelCheckBOc('cracked', $tiang_defect));
                $secondWorksheet->setCellValue('M' . $i, excelCheckBOc('leaning', $tiang_defect));
                $secondWorksheet->setCellValue('O' . $i, excelCheckBOc('dim', $tiang_defect));
                if (excelCheckBOc('other_value', $tiang_defect) == '1') {
                    $other_defects .= $tiang_defect->other_value ?? '';
                }
            }

            if ($secondRec->talian_defect) {
                $talian_defect = json_decode($secondRec->talian_defect);
                $secondWorksheet->setCellValue('Q' . $i, excelCheckBOc('joint', $talian_defect));
                $secondWorksheet->setCellValue('S' . $i, excelCheckBOc('need_rentis', $talian_defect));
                $secondWorksheet->setCellValue('U' . $i, excelCheckBOc('ground', $talian_defect));
                $secondWorksheet->setCellValue('W' . $i, excelCheckBOc('talian_sbum', $talian_defect));
                if (excelCheckBOc('other_value', $talian_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($talian_defect->other_value ?? '');
                }
            }

            if ($secondRec->umbang_defect) {
                $umbang_defect = json_decode($secondRec->umbang_defect);
                $secondWorksheet->setCellValue('Y' . $i, excelCheckBOc('breaking', $umbang_defect));
                $secondWorksheet->setCellValue('AA' . $i, excelCheckBOc('creepers', $umbang_defect));
                $secondWorksheet->setCellValue('AC' . $i, excelCheckBOc('cracked', $umbang_defect));
                $secondWorksheet->setCellValue('AE' . $i, excelCheckBOc('stay_palte', $umbang_defect));
                if (excelCheckBOc('other_value', $umbang_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($umbang_defect->other_value ?? '');
                }
            }

            if ($secondRec->ipc_defect) {
                $ipc_defect = json_decode($secondRec->ipc_defect);
                $secondWorksheet->setCellValue('AG' . $i, excelCheckBOc('ipc_n_krg2', $ipc_defect));
                $secondWorksheet->setCellValue('AI' . $i, excelCheckBOc('ec_tiada', $ipc_defect));
                if (excelCheckBOc('other_value', $ipc_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($ipc_defect->other_value ?? '');
                }
            }

            if ($secondRec->blackbox_defect) {
                $blackbox_defect = json_decode($secondRec->blackbox_defect);
                $secondWorksheet->setCellValue('AK' . $i, excelCheckBOc('cracked', $blackbox_defect));
                if (excelCheckBOc('other_value', $blackbox_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($blackbox_defect->other_value ?? '');
                }
            }

            if ($secondRec->jumper) {
                $jumper = json_decode($secondRec->jumper);
                $secondWorksheet->setCellValue('AM' . $i, excelCheckBOc('sleeve', $jumper));
                $secondWorksheet->setCellValue('AO' . $i, excelCheckBOc('burn', $jumper));
                if (excelCheckBOc('other_value', $jumper) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($jumper->other_value ?? '');
                }
            }

            if ($secondRec->kilat_defect) {
                $kilat_defect = json_decode($secondRec->kilat_defect);
                $secondWorksheet->setCellValue('AQ' . $i, excelCheckBOc('broken', $kilat_defect));
                if (excelCheckBOc('other_value', $kilat_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($kilat_defect->other_value ?? '');
                }
            }

            if ($secondRec->servis_defect) {
                $servis_defect = json_decode($secondRec->servis_defect);
                $secondWorksheet->setCellValue('AS' . $i, excelCheckBOc('won_piece', $servis_defect));
                if (excelCheckBOc('other_value', $servis_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($servis_defect->other_value ?? '');
                }
            }

            if ($secondRec->pembumian_defect) {
                $pembumian_defect = json_decode($secondRec->pembumian_defect);
                $secondWorksheet->setCellValue('AU' . $i, excelCheckBOc('netural', $pembumian_defect));
                if (excelCheckBOc('other_value', $pembumian_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($pembumian_defect->other_value ?? '');
                }
            }

            if ($secondRec->bekalan_dua_defect) {
                $bekalan_dua_defect = json_decode($secondRec->bekalan_dua_defect);
                $secondWorksheet->setCellValue('AW' . $i, excelCheckBOc('damage', $bekalan_dua_defect));
                if (excelCheckBOc('other_value', $bekalan_dua_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($bekalan_dua_defect->other_value ?? '');
                }
            }

            if ($secondRec->kaki_lima_defect) {
                $kaki_lima_defect = json_decode($secondRec->kaki_lima_defect);
                $secondWorksheet->setCellValue('AY' . $i, excelCheckBOc('date_wire', $kaki_lima_defect));
                $secondWorksheet->setCellValue('BA' . $i, excelCheckBOc('burn', $kaki_lima_defect));
                $secondWorksheet->setCellValue('BC' . $i, excelCheckBOc('usikan_pengguna', $kaki_lima_defect));
                if (excelCheckBOc('other_value', $kaki_lima_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($kaki_lima_defect->other_value ?? '');
                }
            }

            $secondWorksheet->setCellValue('BE' . $i, $secondRec->hazard_defect);
            $secondWorksheet->setCellValue('BG' . $i, $other_defects);
            $secondWorksheet->setCellValue('BI' . $i, $secondRec->coords1);
            $secondWorksheet->setCellValue('BJ' . $i, $secondRec->total_defects);

            $images = [];
            foreach ($defectsImg as $defImg) {
                if (!empty($secondRec->{$defImg})) {
                    $images[] = $imageBaseUrl . $secondRec->{$defImg};
                }
            }
            $secondWorksheet->setCellValue('BT' . $i, implode(' ', $images));

            $repair_date = $secondRec->repair_date ? date('Y-m-d', strtotime($secondRec->repair_date)) : '';
            $secondWorksheet->setCellValue('BK' . $i, $repair_date);
            $secondWorksheet->setCellValue('BM' . $i, $secondRec->remarks);
            $secondWorksheet->setCellValue('BR' . $i, $secondRec->id);
            $secondWorksheet->setCellValue('BS' . $i, $secondRec->review_date);

            $sortedDates = [];
            if (isset($repairDates[$secondRec->id])) {
                foreach ($repairDates[$secondRec->id] as $dateRec) {
                    $sortedDates[$dateRec->name] = $dateRec->date;
                }
            }

            $secondWorksheet->setCellValue('L' . $i, getRepairDate('tiang_defect_cracked', $sortedDates));
            $secondWorksheet->setCellValue('N' . $i, getRepairDate('tiang_defect_leaning', $sortedDates));
            $secondWorksheet->setCellValue('P' . $i, getRepairDate('tiang_defect_dim', $sortedDates));
            $secondWorksheet->setCellValue('R' . $i, getRepairDate('talian_defect_joint', $sortedDates));
            $secondWorksheet->setCellValue('T' . $i, getRepairDate('talian_defect_need_rentis', $sortedDates));
            $secondWorksheet->setCellValue('V' . $i, getRepairDate('talian_defect_ground', $sortedDates));
            $secondWorksheet->setCellValue('X' . $i, getRepairDate('umbang_defect_breaking', $sortedDates));
            $secondWorksheet->setCellValue('Z' . $i, getRepairDate('umbang_defect_creepers', $sortedDates));
            $secondWorksheet->setCellValue('AB' . $i, getRepairDate('umbang_defect_cracked', $sortedDates));
            $secondWorksheet->setCellValue('AD' . $i, getRepairDate('umbang_defect_stay_palte', $sortedDates));
            $secondWorksheet->setCellValue('AF' . $i, getRepairDate('ipc_defect_burn', $sortedDates));
            $secondWorksheet->setCellValue('AH' . $i, getRepairDate('blackbox_defect_cracked', $sortedDates));
            $secondWorksheet->setCellValue('AJ' . $i, getRepairDate('jumper_sleeve', $sortedDates));
            $secondWorksheet->setCellValue('AL' . $i, getRepairDate('jumper_burn', $sortedDates));
            $secondWorksheet->setCellValue('AN' . $i, getRepairDate('kilat_defect_broken', $sortedDates));
            $secondWorksheet->setCellValue('AP' . $i, getRepairDate('servis_defect_roof', $sortedDates));
            $secondWorksheet->setCellValue('AR' . $i, getRepairDate('servis_defect_won_piece', $sortedDates));
            $secondWorksheet->setCellValue('AT' . $i, getRepairDate('pembumian_defect_netural', $sortedDates));
            $secondWorksheet->setCellValue('AV' . $i, getRepairDate('bekalan_dua_defect_damage', $sortedDates));
            $secondWorksheet->setCellValue('AX' . $i, getRepairDate('kaki_lima_defect_date_wire', $sortedDates));
            $secondWorksheet->setCellValue('AZ' . $i, getRepairDate('kaki_lima_defect_burn', $sortedDates));

            $i++;
        }

        // Sheet 3
        $thirdWorksheet = $spreadsheet->getSheet(2);
        $thirdWorksheet->getStyle('A:O')->getAlignment()->setHorizontal('center');
        $thirdWorksheet->getStyle('B:AL')->getFont()->setSize(9);

        $i = 5;
        foreach ($res as $rec) {
            $thirdWorksheet->setCellValue('A' . $i, $i - 4);
            $thirdWorksheet->setCellValue('B' . $i, $rec->review_date);
            $thirdWorksheet->setCellValue('C' . $i, $rec->fp_name);
            $thirdWorksheet->setCellValue('D' . $i, $rec->section_from);
            $thirdWorksheet->setCellValue('E' . $i, $rec->section_to);

            if ($rec->tapak_condition) {
                $tapak_condition = json_decode($rec->tapak_condition);
                $thirdWorksheet->setCellValue('F' . $i, excelCheckBOc('road', $tapak_condition) == '1' ? '/' : '');
                $thirdWorksheet->setCellValue('G' . $i, excelCheckBOc('side_walk', $tapak_condition) == '1' ? '/' : '');
                $thirdWorksheet->setCellValue('H' . $i, excelCheckBOc('vehicle_entry', $tapak_condition) == '1' ? '/' : '');
            }

            if ($rec->kawasan) {
                $kawasan = json_decode($rec->kawasan);
                $thirdWorksheet->setCellValue('I' . $i, excelCheckBOc('bend', $kawasan) == '1' ? '/' : '');
                $thirdWorksheet->setCellValue('J' . $i, excelCheckBOc('raod', $kawasan) == '1' ? '/' : '');
                $thirdWorksheet->setCellValue('K' . $i, excelCheckBOc('forest', $kawasan) == '1' ? '/' : '');
                $thirdWorksheet->setCellValue('L' . $i, excelCheckBOc('other', $kawasan) == '1' ? '/' : '');
            }

            $thirdWorksheet->setCellValue('M' . $i, $rec->jarak_kelegaan);

            if ($rec->talian_spec) {
                $thirdWorksheet->setCellValue('N' . $i, $rec->talian_spec == "comply" ? '/' : '');
                $thirdWorksheet->setCellValue('O' . $i, $rec->talian_spec == "uncomply" ? '/' : '');
            }

            $thirdWorksheet->setCellValue('P' . $i, $rec->arus_pada_tiang == "Yes" ? '/' : '');
            $thirdWorksheet->setCellValue('S' . $i, 'AEROSYNERGY SOLUTIONS');
            $thirdWorksheet->setCellValue('T' . $i, $rec->fp_road);
            $thirdWorksheet->setCellValue('U' . $i, $rec->coords1);

            $i++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'qr-tiang-talian' . rand(2, 10000) . '.xlsx';
        $writer->save(public_path('assets/updated-excels/') . $filename);

        return response()->download(public_path('assets/updated-excels/') . $filename)->deleteFileAfterSend(true);
    }

    public function generateTiangKLPExcel($roadStatistics, $res, $ba, $defectsImg, $req, $repairDates, $reviewDatesByGeomCycle)
    {
        $excelFile = public_path('assets/excel-template/TIANG KL PUSAT.xlsx');
        $spreadsheet = IOFactory::load($excelFile);

        // Sheet 0
        $worksheet = $spreadsheet->getSheet(0);
        $worksheet->getStyle('B:AK')->getAlignment()->setHorizontal('center');
        $worksheet->getStyle('B:AL')->getFont()->setSize(9);
        $worksheet->setCellValue('D4', $ba);

        $i = 3;
        foreach ($roadStatistics as $rec) {
            $spanCount = $rec->abc_s3186 + $rec->abc_s3195 + $rec->abc_s316 + $rec->abc_s116 +
                        $rec->pvc_s9064 + $rec->pvc_s7083 + $rec->pvc_s7044 +
                        $rec->bare_s7173 + $rec->bare_s7122 + $rec->bare_s7132;

            $worksheet->setCellValue('B' . $i, $i - 2);
            $worksheet->setCellValue('C' . $i, $rec->fp_name);
            $worksheet->setCellValue('D' . $i, $rec->road);
            $worksheet->setCellValue('E' . $i, $rec->section_from);
            $worksheet->setCellValue('F' . $i, $rec->section_to);
            $worksheet->setCellValue('G' . $i, $rec->size_tiang_75);
            $worksheet->setCellValue('H' . $i, $rec->size_tiang_9);
            $worksheet->setCellValue('I' . $i, $rec->size_tiang_10);
            $worksheet->setCellValue('J' . $i, $rec->jenis_tiang_spun);
            $worksheet->setCellValue('K' . $i, $rec->jenis_tiang_concrete);
            $worksheet->setCellValue('L' . $i, $rec->jenis_tiang_iron);
            $worksheet->setCellValue('M' . $i, $rec->jenis_tiang_wood);
            $worksheet->setCellValue('N' . $i, $rec->abc_s3186);
            $worksheet->setCellValue('O' . $i, $rec->abc_s3195);
            $worksheet->setCellValue('P' . $i, $rec->abc_s316);
            $worksheet->setCellValue('Q' . $i, $rec->abc_s116);
            $worksheet->setCellValue('R' . $i, $rec->pvc_s9064);
            $worksheet->setCellValue('S' . $i, $rec->pvc_s7083);
            $worksheet->setCellValue('T' . $i, $rec->pvc_s7044);
            $worksheet->setCellValue('U' . $i, $rec->bare_s7173);
            $worksheet->setCellValue('V' . $i, $rec->bare_s7122);
            $worksheet->setCellValue('W' . $i, $rec->bare_s7132);
            $worksheet->setCellValue('X' . $i, $spanCount);
            $worksheet->setCellValue('Y' . $i, $rec->main_line_count > 0 ? 'M' : 'S');
            $worksheet->setCellValue('Z' . $i, $rec->umbagan);
            $worksheet->setCellValue('AA' . $i, $rec->blackbox);
            $worksheet->setCellValue('AB' . $i, $rec->ipc);
            $worksheet->setCellValue('AC' . $i, $rec->service);
            $i++;
        }

        // Sheet 1
        $worksheetOne = $spreadsheet->getSheet(1);
        $i = 3;

        foreach ($res as $rec) {
            $worksheetOne->setCellValue('A' . $i, $i - 2);
            $worksheetOne->setCellValue('B' . $i, $rec->fp_name);
            $worksheetOne->setCellValue('C' . $i, $rec->road);
            $worksheetOne->setCellValue('D' . $i, $rec->section_from);
            $worksheetOne->setCellValue('E' . $i, $rec->section_to);
            $worksheetOne->setCellValue('F' . $i, $rec->review_date);
            $worksheetOne->setCellValue('G' . $i, $rec->review_date);

            if ($req->filled('cycle')) {
                $cycle = (int)$req->cycle;

                for ($c = 1; $c <= $cycle; $c++) {
                    $col = chr(71 + $c); // H, I, J, K
                    if ($c < $cycle) {
                        $key = $rec->geom_id . '_' . $c;
                        $reviewDate = $reviewDatesByGeomCycle[$key] ?? '';
                        $worksheetOne->setCellValue($col . $i, $reviewDate);
                    } else {
                        $worksheetOne->setCellValue($col . $i, $rec->review_date);
                    }
                }
            } else {
                $worksheetOne->setCellValue('H' . $i, $rec->review_date);
            }

            $worksheetOne->setCellValue('N' . $i, $rec->id);
            $i++;
        }

        // Sheet 2
        $secondWorksheet = $spreadsheet->getSheet(2);
        $secondWorksheet->getStyle('B:AL')->getAlignment()->setHorizontal('center');
        $secondWorksheet->getStyle('B:AL')->getFont()->setSize(9);
        $secondWorksheet->setCellValue('C1', $ba);
        $secondWorksheet->setCellValue('B3', 'Tarikh Pemeriksaan : ' . date('Y-m-d'));

        $i = 3;
        $imageBaseUrl = config('globals.APP_IMAGES_URL');

        foreach ($res as $secondRec) {
            $other_defects = '';

            $secondWorksheet->setCellValue('A' . $i, $secondRec->id);
            $secondWorksheet->setCellValue('B' . $i, $secondRec->review_date);
            $secondWorksheet->setCellValue('C' . $i, $i - 2);
            $secondWorksheet->setCellValue('D' . $i, $secondRec->fp_name);
            $secondWorksheet->setCellValue('E' . $i, $secondRec->fp_road);
            $secondWorksheet->setCellValue('F' . $i, $secondRec->section_from);
            $secondWorksheet->setCellValue('G' . $i, $secondRec->section_to);
            $secondWorksheet->setCellValue('H' . $i, $secondRec->tiang_no);

            $tiang_defect = $secondRec->tiang_defect ? json_decode($secondRec->tiang_defect) : null;
            $talian_defect = $secondRec->talian_defect ? json_decode($secondRec->talian_defect) : null;
            $umbang_defect = $secondRec->umbang_defect ? json_decode($secondRec->umbang_defect) : null;
            $ipc_defect = $secondRec->ipc_defect ? json_decode($secondRec->ipc_defect) : null;
            $blackbox_defect = $secondRec->blackbox_defect ? json_decode($secondRec->blackbox_defect) : null;
            $jumper = $secondRec->jumper ? json_decode($secondRec->jumper) : null;
            $kilat_defect = $secondRec->kilat_defect ? json_decode($secondRec->kilat_defect) : null;
            $servis_defect = $secondRec->servis_defect ? json_decode($secondRec->servis_defect) : null;
            $pembumian_defect = $secondRec->pembumian_defect ? json_decode($secondRec->pembumian_defect) : null;
            $bekalan_dua_defect = $secondRec->bekalan_dua_defect ? json_decode($secondRec->bekalan_dua_defect) : null;
            $kaki_lima_defect = $secondRec->kaki_lima_defect ? json_decode($secondRec->kaki_lima_defect) : null;

            if ($tiang_defect) {
                $secondWorksheet->setCellValue('I' . $i, excelCheckBOc('cracked', $tiang_defect));
                $secondWorksheet->setCellValue('J' . $i, excelCheckBOc('leaning', $tiang_defect));
                $secondWorksheet->setCellValue('K' . $i, excelCheckBOc('dim', $tiang_defect));
                if (excelCheckBOc('other_value', $tiang_defect) == '1') {
                    $other_defects .= $tiang_defect->other_value ?? '';
                }
            }

            $secondWorksheet->setCellValue('L' . $i, $secondRec->tiang_defect_current_leakage == 'Yes' ? '1' : '0');

            if ($talian_defect) {
                $secondWorksheet->setCellValue('M' . $i, excelCheckBOc('joint', $talian_defect));
                $secondWorksheet->setCellValue('N' . $i, excelCheckBOc('need_rentis', $talian_defect));
                $secondWorksheet->setCellValue('O' . $i, excelCheckBOc('ground', $talian_defect));
                $secondWorksheet->setCellValue('P' . $i, excelCheckBOc('talian_sbum', $talian_defect));
                if (excelCheckBOc('other_value', $talian_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($talian_defect->other_value ?? '');
                }
            }

            if ($umbang_defect) {
                $secondWorksheet->setCellValue('Q' . $i, excelCheckBOc('breaking', $umbang_defect));
                $secondWorksheet->setCellValue('R' . $i, excelCheckBOc('creepers', $umbang_defect));
                $secondWorksheet->setCellValue('S' . $i, excelCheckBOc('cracked', $umbang_defect));
                $secondWorksheet->setCellValue('T' . $i, excelCheckBOc('stay_palte', $umbang_defect));
                $secondWorksheet->setCellValue('U' . $i, excelCheckBOc('current_leakage', $umbang_defect));
                if (excelCheckBOc('other_value', $umbang_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($umbang_defect->other_value ?? '');
                }
            }

            $secondWorksheet->setCellValue('T' . $i, $secondRec->umbang_defect_current_leakage == 'Yes' ? '1' : '0');

            if ($ipc_defect) {
                $secondWorksheet->setCellValue('V' . $i, excelCheckBOc('ipc_n_krg2', $ipc_defect));
                $secondWorksheet->setCellValue('W' . $i, excelCheckBOc('ec_tiada', $ipc_defect));
                if (excelCheckBOc('other_value', $ipc_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($ipc_defect->other_value ?? '');
                }
            }

            if ($blackbox_defect) {
                $secondWorksheet->setCellValue('X' . $i, excelCheckBOc('cracked', $blackbox_defect));
                if (excelCheckBOc('other_value', $blackbox_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($blackbox_defect->other_value ?? '');
                }
            }

            if ($jumper) {
                $secondWorksheet->setCellValue('Y' . $i, excelCheckBOc('sleeve', $jumper));
                $secondWorksheet->setCellValue('Z' . $i, excelCheckBOc('burn', $jumper));
                if (excelCheckBOc('other_value', $jumper) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($jumper->other_value ?? '');
                }
            }

            if ($kilat_defect) {
                $secondWorksheet->setCellValue('AA' . $i, excelCheckBOc('broken', $kilat_defect));
                if (excelCheckBOc('other_value', $kilat_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($kilat_defect->other_value ?? '');
                }
            }

            if ($servis_defect) {
                $secondWorksheet->setCellValue('AB' . $i, excelCheckBOc('won_piece', $servis_defect));
                if (excelCheckBOc('other_value', $servis_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($servis_defect->other_value ?? '');
                }
            }

            if ($pembumian_defect) {
                $secondWorksheet->setCellValue('AC' . $i, excelCheckBOc('netural', $pembumian_defect));
                if (excelCheckBOc('other_value', $pembumian_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($pembumian_defect->other_value ?? '');
                }
            }

            if ($bekalan_dua_defect) {
                $secondWorksheet->setCellValue('AD' . $i, excelCheckBOc('damage', $bekalan_dua_defect));
                if (excelCheckBOc('other_value', $bekalan_dua_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($bekalan_dua_defect->other_value ?? '');
                }
            }

            if ($kaki_lima_defect) {
                $secondWorksheet->setCellValue('AE' . $i, excelCheckBOc('date_wire', $kaki_lima_defect));
                $secondWorksheet->setCellValue('AF' . $i, excelCheckBOc('burn', $kaki_lima_defect));
                $secondWorksheet->setCellValue('BG' . $i, excelCheckBOc('usikan_pengguna', $kaki_lima_defect));
                if (excelCheckBOc('other_value', $kaki_lima_defect) == '1') {
                    $other_defects .= ($other_defects ? ', ' : '') . ($kaki_lima_defect->other_value ?? '');
                }
            }

            $secondWorksheet->setCellValue('AH' . $i, $secondRec->hazard_defect);
            $secondWorksheet->setCellValue('AI' . $i, $secondRec->total_defects);
            $secondWorksheet->setCellValue('AL' . $i, $other_defects);
            $secondWorksheet->setCellValue('AM' . $i, $secondRec->coords1);

            $images = [];
            foreach ($defectsImg as $defImg) {
                if (!empty($secondRec->{$defImg})) {
                    $images[] = $imageBaseUrl . $secondRec->{$defImg};
                }
            }
            $secondWorksheet->setCellValue('AN' . $i, implode(' ', $images));

            $i++;
        }

        // Sheet 3
        $thirdWorksheet = $spreadsheet->getSheet(3);
        $thirdWorksheet->getStyle('A:O')->getAlignment()->setHorizontal('center');
        $thirdWorksheet->getStyle('B:AL')->getFont()->setSize(9);

        $i = 4;
        foreach ($res as $rec) {
            $thirdWorksheet->setCellValue('A' . $i, $i - 3);
            $thirdWorksheet->setCellValue('B' . $i, $rec->review_date);
            $thirdWorksheet->setCellValue('C' . $i, $rec->fp_name);
            $thirdWorksheet->setCellValue('D' . $i, $rec->section_from);
            $thirdWorksheet->setCellValue('E' . $i, $rec->section_to);

            $tapak_condition = $rec->tapak_condition ? json_decode($rec->tapak_condition) : null;
            if ($tapak_condition) {
                $thirdWorksheet->setCellValue('F' . $i, excelCheckBOc('road', $tapak_condition) == '1' ? '/' : '');
                $thirdWorksheet->setCellValue('G' . $i, excelCheckBOc('side_walk', $tapak_condition) == '1' ? '/' : '');
                $thirdWorksheet->setCellValue('H' . $i, excelCheckBOc('vehicle_entry', $tapak_condition) == '1' ? '/' : '');
            }

            $kawasan = $rec->kawasan ? json_decode($rec->kawasan) : null;
            if ($kawasan) {
                $thirdWorksheet->setCellValue('I' . $i, excelCheckBOc('bend', $kawasan) == '1' ? '/' : '');
                $thirdWorksheet->setCellValue('J' . $i, excelCheckBOc('raod', $kawasan) == '1' ? '/' : '');
                $thirdWorksheet->setCellValue('K' . $i, excelCheckBOc('forest', $kawasan) == '1' ? '/' : '');
                $thirdWorksheet->setCellValue('L' . $i, excelCheckBOc('other', $kawasan) == '1' ? '/' : '');
            }

            $thirdWorksheet->setCellValue('M' . $i, $rec->jarak_kelegaan);

            if ($rec->talian_spec) {
                $thirdWorksheet->setCellValue('N' . $i, $rec->talian_spec == "comply" ? '/' : '');
                $thirdWorksheet->setCellValue('O' . $i, $rec->talian_spec == "uncomply" ? '/' : '');
            }

            $thirdWorksheet->setCellValue('P' . $i, $rec->arus_pada_tiang == "Yes" ? '/' : '');
            $thirdWorksheet->setCellValue('S' . $i, 'AEROSYNERGY SOLUTIONS');
            $thirdWorksheet->setCellValue('T' . $i, $rec->fp_road);
            $thirdWorksheet->setCellValue('U' . $i, $rec->coords1);

            $i++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'qr-tiang-talian-klp-' . time() . '.xlsx';
        $filePath = public_path('assets/updated-excels/') . $filename;
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
