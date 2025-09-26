<?php

namespace App\Http\Controllers\web\SavrFFA;

use App\Http\Controllers\Controller;
use App\Models\SavrFfa;
use App\Traits\Filter;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\WorkPackage;

class FFALKSController extends Controller
{
    use Filter;

    public function generateByVisitDate(Fpdf $fpdf, Request $req)
    {
        $result = SavrFfa::query();

        $result = SavrFfa::where('ba', Auth::user()->ba)
                            ->whereRaw("DATE(visit_date) = ?::date", [$req->visit_date])
                           // ->where('qa_status', 'Accept')
                            ->where('cycle', $req->cycle);

        if ($req->filled('workPackages')) {
            $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');
            $result = $result->whereRaw('ST_Within(geom, ?)', [$workPackageGeom]);
        }

        $data = $result->select(
                            'id',
                            'pole_id',
                            'wayar_tertanggal',
                            'ipc_terbakar',
                            'other',
                            'other_name',
                            'pole_no',
                            'house_image',
                            'image2',
                            'image3',
                            'visit_date',
                            'ba',
                            'cycle',
                            'joint_box',
                            'house_renovation',
                            'house_number',
                            DB::raw('ST_X(geom) as X'),
                            DB::raw('ST_Y(geom) as Y')
                        )->get();


        $fpdf->AddPage('L', 'A4');
        $fpdf->SetFont('Arial', 'B', 22);

        $fpdf->Cell(180, 25, Auth::user()->ba .' ' .$req->visit_date );
        $fpdf->Ln();

        $fpdf->SetFont('Arial', 'B', 14);

        $fpdf->Cell(50,7,'Jumlah Rekod',1);
        $fpdf->Cell(20,7,sizeof($data),1);

        $fpdf->Ln();
        $fpdf->Ln();

        $imagePath = public_path('assets/web-images/main-logo.png');
        $fpdf->Image($imagePath, 190, 20, 57, 0);
        $fpdf->SetFont('Arial', 'B', 9);

        $sr_no = 0;
        $entriesPerPage = 2;
        $entryCount = 0;

        foreach ($data as $row) {
            $entryCount++;

            if ($entryCount > $entriesPerPage) {
                $fpdf->AddPage('L', 'A4');
                $entryCount = 1;
            }

            $sr_no++;
            $col1Width = 90;
            $col2Width = 90;
            $cellHeight = 6;

            // Row 1
            $fpdf->Cell($col1Width/2, $cellHeight, 'SR # : ' . $sr_no, 0);
            $fpdf->Cell($col2Width/2, $cellHeight, 'Tarikh Lawatan : ' . $row->visit_date, 0);
            $fpdf->Cell($col1Width/2, $cellHeight, 'POLE ID : ' . $row->pole_id, 0);
            $fpdf->Cell($col2Width/2, $cellHeight, 'NAMA JALAN : ' , 0);
            $fpdf->Ln($cellHeight);

            // Row 2
            $fpdf->Cell($col1Width/2, $cellHeight, 'POLE NO : ' . $row->pole_no, 0);
            $fpdf->Cell($col2Width, $cellHeight, 'Koordinat : ' . $row->y . ' , ' . $row->x, 0);
            $fpdf->Cell($col1Width/2, $cellHeight, 'No Rumah : ' . $row->house_number, 0);
            $fpdf->Cell($col2Width/2, $cellHeight, 'FFW ID : ' . $row->id, 0);
            $fpdf->Ln($cellHeight);

            $fpdf->Ln(2);

            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->SetFillColor(169, 169, 169);

            $fpdf->Cell(54, 7, 'Wayar Tanggal', 1, 0, 'L', true);
            $fpdf->Cell(54, 7, 'Joint Box', 1, 0, 'L', true);
            $fpdf->Cell(54, 7, 'IPC Terbakar', 1, 0, 'L', true);
            $fpdf->Cell(54, 7, 'House Renovation', 1, 0, 'L', true);
            $fpdf->Cell(54, 7, 'Lain-Lain', 1, 0, 'L', true);

            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->Ln();

            $fpdf->Cell(54, 7, $row->wayar_tertanggal=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(54, 7, $row->joint_box=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(54, 7, $row->ipc_terbakar=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(54, 7, $row->house_renovation=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(54, 7, $row->other_name, 1);

            $fpdf->Ln();
            $fpdf->Ln();

            // Handle images
            $house_image = config('globals.APP_IMAGES_LOCALE_PATH').$row->house_image;
            if ($row->house_image != '' && file_exists($house_image)) {
                $fpdf->Image($house_image, $fpdf->GetX(), $fpdf->GetY(), 60, 60);
                $fpdf->Cell(40);
            } else {
                $fpdf->Cell(40, 7, '');
            }

            $image2 = config('globals.APP_IMAGES_LOCALE_PATH').$row->image2;
            if ($row->image2 != '' && file_exists($image2)) {
                $fpdf->Image($image2, $fpdf->GetX(), $fpdf->GetY(), 60, 60);
                $fpdf->Cell(40);
            } else {
                $fpdf->Cell(40, 7, '');
            }

            $image3 = config('globals.APP_IMAGES_LOCALE_PATH').$row->image3;
            if ($row->image3 != '' && file_exists($image3)) {
                $fpdf->Image($image3, $fpdf->GetX(), $fpdf->GetY(), 60, 60);
                $fpdf->Cell(40);
            } else {
                $fpdf->Cell(40, 7, '');
            }

            if ($entryCount < $entriesPerPage) {
                $fpdf->Ln(40);
                $fpdf->Ln(20);
            }
        }

        // FIX: Clean filename and ensure proper path construction
        $visitDate = date('Y-m-d', strtotime($req->visit_date));
        $baName = preg_replace('/[^A-Za-z0-9\-_]/', '_', Auth::user()->ba);
        $pdfFileName = $baName . '_FFW_' . $visitDate . '.pdf';

        // FIX 1: Ensure proper folder path construction using DIRECTORY_SEPARATOR
        $basePath = 'D:' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
        $fullFolderPath = $basePath . $req->folder_name;

        // FIX 2: Use Laravel's File facade to create directory
        if (!File::isDirectory($fullFolderPath)) {
            try {
                File::makeDirectory($fullFolderPath, 0755, true, true);

                // Verify directory was created
                if (!File::isDirectory($fullFolderPath)) {
                    return response()->json([
                        'error' => 'Directory creation failed - directory does not exist after creation attempt',
                        'path' => $fullFolderPath
                    ], 500);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to create directory: ' . $e->getMessage(),
                    'path' => $fullFolderPath
                ], 500);
            }
        }

        // Check if directory is writable
        if (!File::isWritable($fullFolderPath)) {
            return response()->json([
                'error' => 'Directory is not writable',
                'path' => $fullFolderPath
            ], 500);
        }

        // FIX 3: Use proper path separator consistently
        $pdfFilePath = $fullFolderPath . DIRECTORY_SEPARATOR . $pdfFileName;

        // FIX 4: Add error handling for file output
        try {
            $fpdf->output('F', $pdfFilePath);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create PDF file: ' . $e->getMessage(),
                'path' => $pdfFilePath
            ], 500);
        }

        // Set headers for download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFileName . '"');

        $response = [
            'pdfPath' => $pdfFileName,
        ];

        return response()->json($response);
    }

    public function gene(Fpdf $fpdf, Request $req)
    {
        if ($req->ajax()) {
            $result = SavrFfa::query();

            $result = $this->filter($result, 'visit_date', $req)
                          // ->where('qa_status', 'Accept')
                           ->whereNotNull('visit_date');

            if ($req->filled('workPackages')) {
                $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');
                $result = $result->whereRaw('ST_Within(geom, ?)', [$workPackageGeom]);
            }

            $getResultByVisitDate = $result->select(DB::raw('DATE(visit_date) as visit_date'), DB::raw('count(*)'))
                                          ->whereNotNull('visit_date')
                                          ->groupBy(DB::raw('DATE(visit_date)'))
                                          ->get();



            $fpdf->AddPage('L', 'A4');
            $fpdf->SetFont('Arial', 'B', 22);

            $fpdf->Cell(180, 15, strtoupper(Auth::user()->ba) .' FFW', 0, 1);
            $fpdf->Cell(180, 25, 'PO NO :');
            $fpdf->Ln();
            $fpdf->SetFont('Arial', 'B', 16);

            $fpdf->Cell(100, 7, 'JUMLAH YANG DICATAT BERHADAPAN TARIKH LAWATAN', 0, 1);
            $fpdf->SetFillColor(169, 169, 169);

            $totalRecords = 0;
            $visitDates = [];

            foreach ($getResultByVisitDate as $visit_date) {
                $fpdf->SetFont('Arial', 'B', 9);
                $fpdf->Cell(50, 7, $visit_date->visit_date, 1, 0, 'C', true);
                $fpdf->Cell(50, 7, $visit_date->count, 1, 0, 'C');
                $fpdf->Ln();
                $totalRecords += $visit_date->count;
                $visitDates[] = $visit_date->visit_date;
            }

            $fpdf->Cell(50, 7, 'JUMLAH REKOD', 1, 0, 'C', true);
            $fpdf->Cell(50, 7, $totalRecords, 1, 0, 'C');
            $fpdf->Ln();
            $fpdf->Ln();

            // FIX: Clean filename by removing invalid characters and formatting dates
            $fromDate = $req->from_date ? date('Y-m-d', strtotime($req->from_date)) : 'All';
            $toDate = $req->to_date ? date('Y-m-d', strtotime($req->to_date)) : 'All';
            $baName = preg_replace('/[^A-Za-z0-9\-_]/', '_', Auth::user()->ba);

            $pdfFileName = $baName . '_FFW_Table_Of_Contents_' . $fromDate . '_to_' . $toDate . '.pdf';

            // FIX 5: Improved folder creation and path handling
            $userID = Auth::user()->id;
            $folderName = 'temporary-FFA-folder-' . $userID;

            // Use forward slashes consistently or use DIRECTORY_SEPARATOR
            $basePath = 'D:' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
            $folderPath = $basePath . $folderName;

            // Ensure the directory exists with proper permissions
            if (!File::isDirectory($folderPath)) {
                try {
                    File::makeDirectory($folderPath, 0755, true, true);

                    // Verify directory was created
                    if (!File::isDirectory($folderPath)) {
                        return response()->json([
                            'error' => 'Directory creation failed - directory does not exist after creation attempt',
                            'path' => $folderPath
                        ], 500);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'error' => 'Failed to create directory: ' . $e->getMessage(),
                        'path' => $folderPath
                    ], 500);
                }
            }

            // Check if directory is writable
            if (!File::isWritable($folderPath)) {
                return response()->json([
                    'error' => 'Directory is not writable',
                    'path' => $folderPath
                ], 500);
            }

            // Normalize the file path using DIRECTORY_SEPARATOR consistently
            $pdfFilePath = $folderPath . DIRECTORY_SEPARATOR . $pdfFileName;

            // FIX 6: Add error handling for PDF output
            try {
                $fpdf->output('F', $pdfFilePath);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to create PDF file: ' . $e->getMessage(),
                    'path' => $pdfFilePath
                ], 500);
            }

            // Set headers
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $pdfFileName . '"');

            $response = [
                'pdfPath' => $pdfFileName,
                'visit_dates' => $visitDates,
                'folder_name' => $folderName
            ];

            return response()->json($response);
        }

        // Handle non-AJAX requests
        if (empty($req->from_date)) {
            $req['from_date'] = SavrFfa::min('visit_date');
        }

        if (empty($req->to_date)) {
            $req['to_date'] = SavrFfa::max('visit_date');
        }

        return view('Documents.download-lks', [
            'ba' => $req->ba,
            'from_date' => $req->from_date,
            'cycle' => $req->cycle,
            'to_date' => $req->to_date,
            'url' => 'ffa',
            'workPackage' => $req->workPackages
        ]);
    }
}
