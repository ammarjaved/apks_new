<?php

namespace App\Http\Controllers\web\Tiang;

use App\Http\Controllers\Controller;
use App\Models\Tiang;
use App\Models\TiangRepairDate;
use App\Traits\Filter;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use App\Models\WorkPackage;

class TiangExcelController extends Controller
{
    use Filter;

    private const CACHE_TTL = 3600; // 1 hour cache
    private const DEFECTS_IMG = ['pole_image_1', 'pole_image_2', 'pole_image_3', 'pole_image_4', 'pole_image_5'];

    public function generateTiangExcel(Request $req)
    {
        try {
            $startTime = microtime(true);

            $ba = $req->filled('ba') ? $req->ba : Auth::user()->ba;

            // Generate cache key for this specific request
            $cacheKey = $this->generateCacheKey($req, $ba);

            // Try to get from cache first (this alone can save 90% of time)
            if (!$req->has('force_refresh')) {
                $cachedFile = Cache::get($cacheKey . '_file');
                if ($cachedFile && file_exists($cachedFile)) {
                    Log::info('Serving cached Excel file', ['time' => microtime(true) - $startTime]);
                    return response()->download($cachedFile)->deleteFileAfterSend(false);
                }
            }

            // Get data with single optimized query
            $data = $this->getSuperOptimizedData($req, $ba);

            if (empty($data)) {
                return redirect()->back()->with('failed', 'No records found');
            }

            Log::info('Data fetched', ['time' => microtime(true) - $startTime, 'count' => count($data)]);

            // Generate Excel file
            $filename = $this->generateExcelFile($data, $ba, $req);

            // Cache the file path
            Cache::put($cacheKey . '_file', $filename, self::CACHE_TTL);

            Log::info('Excel generation completed', ['total_time' => microtime(true) - $startTime]);

            return response()->download($filename)->deleteFileAfterSend(true);

        } catch (\Throwable $th) {
            Log::error('Excel generation failed: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString(),
                'file' => $th->getFile(),
                'line' => $th->getLine()
            ]);
            return redirect()->back()->with('failed', 'Request Failed: ' . $th->getMessage());
        }
    }

    private function getSuperOptimizedData(Request $req, string $ba): array
    {
        // Build single massive query that gets ALL data we need in one go
        $query = "
            WITH road_stats AS (
                SELECT
                    fp_road as road,
                    string_agg(DISTINCT fp_name, ' , ') as fp_names,
                    string_agg(DISTINCT review_date::text, ' , ') as review_dates,
                    SUM(CASE WHEN size_tiang = '7.5' THEN 1 ELSE 0 END) as size_75,
                    SUM(CASE WHEN size_tiang = '9' THEN 1 ELSE 0 END) as size_9,
                    SUM(CASE WHEN size_tiang = '10' THEN 1 ELSE 0 END) as size_10,
                    SUM(CASE WHEN jenis_tiang = 'iron' THEN 1 ELSE 0 END) as jenis_iron,
                    SUM(CASE WHEN jenis_tiang = 'concrete' THEN 1 ELSE 0 END) as jenis_concrete,
                    SUM(CASE WHEN jenis_tiang = 'spun' THEN 1 ELSE 0 END) as jenis_spun,
                    SUM(CASE WHEN jenis_tiang = 'wood' THEN 1 ELSE 0 END) as jenis_wood,
                    SUM(COALESCE((abc_span->>'s3_185')::integer, 0)) as abc_s3185,
                    SUM(COALESCE((abc_span->>'s3_95')::integer, 0)) as abc_s3195,
                    SUM(COALESCE((abc_span->>'s3_16')::integer, 0)) as abc_s316,
                    SUM(COALESCE((abc_span->>'s1_16')::integer, 0)) as abc_s116,
                    SUM(COALESCE((pvc_span->>'s19_064')::integer, 0)) as pvc_s9064,
                    SUM(COALESCE((pvc_span->>'s7_083')::integer, 0)) as pvc_s7083,
                    SUM(COALESCE((pvc_span->>'s7_044')::integer, 0)) as pvc_s7044,
                    SUM(COALESCE((bare_span->>'s7_173')::integer, 0)) as bare_s7173,
                    SUM(COALESCE((bare_span->>'s7_122')::integer, 0)) as bare_s7122,
                    SUM(COALESCE((bare_span->>'s3_132')::integer, 0)) as bare_s7132,
                    SUM(COALESCE(NULLIF(bil_black_box, '')::integer, 0)) as blackbox,
                    SUM(COALESCE(NULLIF(bil_lvpt, '')::integer, 0)) as ipc,
                    SUM(COALESCE(NULLIF(bil_umbang, '')::integer, 0)) as umbagan,
                    SUM(CASE WHEN talian_utama_connection = 'one' THEN 1 ELSE 0 END) as service,
                    SUM(CASE WHEN talian_utama_connection = 'main_line' THEN 1 ELSE 0 END) as main_line,
                    MIN(section_from) as section_from,
                    MAX(section_to) as section_to
                FROM tbl_savr t
                WHERE t.review_date IS NOT NULL
                    AND t.fp_road IS NOT NULL
                    " . $this->buildWhereConditions($req, $ba) . "
                GROUP BY fp_road
            ),
            detailed_data AS (
                SELECT
                    t.*,
                    COALESCE(
                        (SELECT string_agg(tr.name || ':' || tr.date, '|')
                         FROM tbl_tiang_repair_dates tr
                         WHERE tr.savr_id = t.id),
                        ''
                    ) as repair_dates_concat
                FROM tbl_savr t
                WHERE t.review_date IS NOT NULL
                    AND t.fp_road IS NOT NULL
                    " . $this->buildWhereConditions($req, $ba) . "
                ORDER BY t.fp_name
            )
            SELECT 'road_stats' as data_type,
                   row_to_json(road_stats.*) as data
            FROM road_stats
            UNION ALL
            SELECT 'detailed' as data_type,
                   row_to_json(detailed_data.*) as data
            FROM detailed_data
        ";

        $results = DB::select($query);

        $data = [
            'road_stats' => [],
            'detailed' => []
        ];

        foreach ($results as $row) {
            $rowData = json_decode($row->data, true);
            $data[$row->data_type][] = $rowData;
        }

        return $data;
    }

    private function buildWhereConditions(Request $req, string $ba): string
    {
        $conditions = [];

        // BA condition
        $conditions[] = "AND t.ba = '" . addslashes($ba) . "'";

        // Date filters
        if ($req->filled('review_date_from')) {
            $conditions[] = "AND t.review_date >= '" . addslashes($req->review_date_from) . "'";
        }
        if ($req->filled('review_date_to')) {
            $conditions[] = "AND t.review_date <= '" . addslashes($req->review_date_to) . "'";
        }

        // Cycle filter
        if ($req->filled('cycle')) {
            $conditions[] = "AND t.cycle = " . intval($req->cycle);
        }

        // Work package filter
        if ($req->filled('workPackages')) {
            $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');
            if ($workPackageGeom) {
                $conditions[] = "AND ST_Within(
                    (SELECT geom FROM tbl_savr_geom WHERE id = t.geom_id),
                    '" . addslashes($workPackageGeom) . "'
                )";
            }
        }

        return implode(' ', $conditions);
    }

    private function generateExcelFile(array $data, string $ba, Request $req): string
    {
        $startTime = microtime(true);

        // Create new spreadsheet instead of loading template (faster)
        $spreadsheet = new Spreadsheet();

        if ($ba === 'KUALA LUMPUR PUSAT') {
            $this->createKLPExcel($spreadsheet, $data, $ba, $req);
        } else {
            $this->createStandardExcel($spreadsheet, $data, $ba, $req);
        }

        // Use faster writer settings
        $writer = new Xlsx($spreadsheet);
        $writer->setPreCalculateFormulas(false);

        $filename = public_path('assets/updated-excels/tiang-' . time() . '-' . rand(1000, 9999) . '.xlsx');

        // Ensure directory exists
        $dir = dirname($filename);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $writer->save($filename);

        Log::info('Excel file created', ['time' => microtime(true) - $startTime]);

        return $filename;
    }

    private function createStandardExcel($spreadsheet, array $data, string $ba, Request $req)
    {
        // Sheet 1 - Road Statistics
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Road Statistics');

        // Headers
        $headers = ['No', 'FP Name', 'Road', 'Section From', 'Section To', 'Size 7.5', 'Size 9', 'Size 10',
                   'Spun', 'Concrete', 'Iron', 'Wood', 'ABC S3185', 'ABC S3195', 'ABC S316', 'ABC S116',
                   'PVC S9064', 'PVC S7083', 'PVC S7044', 'Bare S7173', 'Bare S7122', 'Bare S7132',
                   'Total Span', 'Line Type', 'Umbagan', 'Blackbox', 'IPC', 'Service', 'Company'];

        $sheet1->fromArray([$headers], NULL, 'A4');

        // Data
        $rowData = [];
        foreach ($data['road_stats'] as $index => $row) {
            $spanCount = $row['abc_s3185'] + $row['abc_s3195'] + $row['abc_s316'] + $row['abc_s116'] +
                        $row['pvc_s9064'] + $row['pvc_s7083'] + $row['pvc_s7044'] +
                        $row['bare_s7173'] + $row['bare_s7122'] + $row['bare_s7132'];

            $rowData[] = [
                $index + 1, $row['fp_names'], $row['road'], $row['section_from'], $row['section_to'],
                $row['size_75'], $row['size_9'], $row['size_10'],
                $row['jenis_spun'], $row['jenis_concrete'], $row['jenis_iron'], $row['jenis_wood'],
                $row['abc_s3185'], $row['abc_s3195'], $row['abc_s316'], $row['abc_s116'],
                $row['pvc_s9064'], $row['pvc_s7083'], $row['pvc_s7044'],
                $row['bare_s7173'], $row['bare_s7122'], $row['bare_s7132'],
                $spanCount, $row['main_line'] > 0 ? 'M' : 'S',
                $row['umbagan'], $row['blackbox'], $row['ipc'], $row['service'], 'AEROSYNERGY'
            ];
        }

        if (!empty($rowData)) {
            $sheet1->fromArray($rowData, NULL, 'A5');
        }

        // Sheet 2 - Detailed Data
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Detailed Data');

        $detailHeaders = ['No', 'FP Name', 'Road', 'Section From', 'Section To', 'Tiang No', 'Review Date', 'Total Defects', 'Hazard', 'Coordinates', 'Remarks'];
        $sheet2->fromArray([$detailHeaders], NULL, 'A8');

        $detailData = [];
        foreach ($data['detailed'] as $index => $row) {
            $detailData[] = [
                $index + 1, $row['fp_name'], $row['fp_road'], $row['section_from'], $row['section_to'],
                $row['tiang_no'], $row['review_date'], $row['total_defects'], $row['hazard_defect'],
                $row['coords1'], $row['remarks']
            ];
        }

        if (!empty($detailData)) {
            $sheet2->fromArray($detailData, NULL, 'A9');
        }

        // Sheet 3 - Location Data
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Location Data');

        $locationHeaders = ['No', 'Review Date', 'FP Name', 'Section From', 'Section To', 'Road', 'Side Walk', 'Vehicle Entry', 'Bend', 'Forest', 'Other'];
        $sheet3->fromArray([$locationHeaders], NULL, 'A4');

        $locationData = [];
        foreach ($data['detailed'] as $index => $row) {
            $tapakCondition = json_decode($row['tapak_condition'] ?? '{}', true);
            $kawasan = json_decode($row['kawasan'] ?? '{}', true);

            $locationData[] = [
                $index + 1, $row['review_date'], $row['fp_name'], $row['section_from'], $row['section_to'],
                $tapakCondition['road'] ?? false ? '/' : '',
                $tapakCondition['side_walk'] ?? false ? '/' : '',
                $tapakCondition['vehicle_entry'] ?? false ? '/' : '',
                $kawasan['bend'] ?? false ? '/' : '',
                $kawasan['forest'] ?? false ? '/' : '',
                $kawasan['other'] ?? false ? '/' : ''
            ];
        }

        if (!empty($locationData)) {
            $sheet3->fromArray($locationData, NULL, 'A5');
        }
    }

    private function createKLPExcel($spreadsheet, array $data, string $ba, Request $req)
    {
        // Similar structure but optimized for KLP format
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('KLP Road Statistics');

        // Use similar approach but with KLP-specific layout
        $headers = ['No', 'FP Name', 'Road', 'Section From', 'Section To', 'Size 7.5', 'Size 9', 'Size 10'];
        $sheet1->fromArray([$headers], NULL, 'B2');

        $rowData = [];
        foreach ($data['road_stats'] as $index => $row) {
            $rowData[] = [
                $index + 1, $row['fp_names'], $row['road'], $row['section_from'], $row['section_to'],
                $row['size_75'], $row['size_9'], $row['size_10']
            ];
        }

        if (!empty($rowData)) {
            $sheet1->fromArray($rowData, NULL, 'B3');
        }

        // Add other KLP sheets as needed...
    }

    private function generateCacheKey(Request $req, string $ba): string
    {
        $keyParts = [
            'tiang_excel_v2',
            $ba,
            $req->get('cycle', 'default'),
            $req->get('workPackages', 'none'),
            $req->get('review_date_from', ''),
            $req->get('review_date_to', ''),
            Auth::id()
        ];

        return 'excel_' . md5(implode('_', $keyParts));
    }

    public function getReviewDateAgainstGeomId($geomid, $cycle)
    {
        return Cache::remember("review_date_{$geomid}_{$cycle}", 1800, function () use ($geomid, $cycle) {
            return Tiang::where('geom_id', $geomid)->where('cycle', $cycle)->value('review_date');
        });
    }
}
