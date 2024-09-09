<?php

namespace App\Http\Controllers\web\SavrFFA;

use App\Http\Controllers\Controller;
use App\Models\SavrFfaSub1;
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
        $result = SavrFfaSub1::query();


        $result = SavrFfaSub1::where('ba',$req->ba)
                            ->whereRaw("DATE(visit_date) = ?::date", [$req->visit_date])
                            ->where('qa_status','Accept')
                            ->where('cycle',$req->cycle);
                            //->join('tbl_cable_bridge_geom as g', 'tbl_cable_bridge.geom_id', '=', 'g.id');


        if ($req->filled('workPackages'))
        {
            // Fetch the geometry of the work package
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
                            'nama_jalan',
                            DB::raw('ST_X(geom) as X'),
                            DB::raw('ST_Y(geom) as Y')
                        )->get();


        $fpdf->AddPage('L', 'A4');
        $fpdf->SetFont('Arial', 'B', 22);

        $fpdf->Cell(180, 25, $req->ba .' ' .$req->visit_date );
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
        foreach ($data as $row) {
           // if ($sr_no > 0 && $sr_no % 2 == 0) {
                $fpdf->AddPage('L', 'A4');

            $sr_no++;
            $col1Width = 90;
            $col2Width = 90;

            // First row
            $fpdf->Cell($col1Width, 6, 'SR # : ' . $sr_no, 0);
            $fpdf->Cell($col2Width, 6, 'Tarikh Lawatan : ' . $row->visit_date, 0);
            $fpdf->Ln();

            // Second row
            $fpdf->Cell($col1Width, 6, 'POLE ID : ' . $row->pole_id, 0);
            $fpdf->Cell($col2Width, 6, 'NAMA JALAN : ' . $row->nama_jalan, 0);
            $fpdf->Ln();

            // Third row
            $fpdf->Cell($col1Width, 6, 'POLE NO : ' . $row->pole_no, 0);
            $fpdf->Cell($col2Width, 6, 'Koordinat : ' . $row->y . ' , ' . $row->x, 0);
            $fpdf->Ln();

            // Fourth row
            $fpdf->Cell($col1Width, 6, 'No Rumah : ' . $row->house_number, 0);
            $fpdf->Cell($col2Width, 6, 'FFW ID : ' . $row->id, 0);
            $fpdf->Ln();
            $fpdf->Ln();
            $fpdf->Ln();
            $fpdf->Ln();

            $fpdf->SetFont('Arial', 'B', 8);

            $fpdf->SetFillColor(169, 169, 169);

            $fpdf->Cell(54, 7, 'Wayar Tanggal', 1, 0, 'L', true);
            $fpdf->Cell(54, 7, 'Joint Box', 1, 0, 'L', true); // colapsed
            $fpdf->Cell(54, 7, 'IPC Terbakar', 1, 0, 'L', true); // Rsuty
            $fpdf->Cell(54, 7, 'House Renovation', 1, 0, 'L', true); //BUSHES STATUS
            $fpdf->Cell(54, 7, 'Lain-Lain', 1, 0, 'L', true);  //PIPE STATUS

            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->Ln();

            $fpdf->Cell(54, 7, $row->wayar_tertanggal=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(54, 7, $row->joint_box=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(54, 7, $row->ipc_terbakar=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(54, 7, $row->house_renovation=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(54, 7, $row->other_name, 1);

            $fpdf->Ln();
            $fpdf->Ln();

            $house_image = config('globals.APP_IMAGES_LOCALE_PATH').$row->house_image;
            if ($row->house_image != '' && file_exists($house_image))
            {
                $fpdf->Image($house_image, $fpdf->GetX(), $fpdf->GetY(), 50, 50);
                $fpdf->Cell(60);
            } else {
                $fpdf->Cell(60, 7, '');
            }

            // $fpdf->Ln();

            $image2 = config('globals.APP_IMAGES_LOCALE_PATH').$row->image2;
            if ($row->image2 != '' && file_exists($image2))
            {
                $fpdf->Image($image2, $fpdf->GetX(), $fpdf->GetY(), 50, 50);
                $fpdf->Cell(60);
            } else {
                $fpdf->Cell(60, 7, '');
            }

            $image3 = config('globals.APP_IMAGES_LOCALE_PATH').$row->image3;
            if ($row->image3 != '' && file_exists($image3))
            {
                $fpdf->Image($image3, $fpdf->GetX(), $fpdf->GetY(), 50, 50);
                $fpdf->Cell(60);
            } else {
                $fpdf->Cell(60, 7, '');
            }



            $fpdf->Ln();
            $fpdf->Ln();
            // $fpdf->Ln();
            // $fpdf->Ln();
            // $fpdf->Ln();

            // Move to the next line for the next row
        }

        $pdfFileName = $req->ba.' - FFA - '.$req->visit_date.'.pdf';
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFileName . '"');
        $folderPath = $req->folder_name .'/'. $pdfFileName;
        $pdfFilePath = $folderPath;
        $fpdf->output('F', $pdfFilePath);


        $response = [
            'pdfPath' => $pdfFileName,
        ];

        return response()->json($response);
    }

    public function gene(Fpdf $fpdf, Request $req)
    {
       // return $req;
        if ($req->ajax())
        {
            $result = SavrFfaSub1::query();

            $result = $this->filter($result , 'visit_date',$req)->where('qa_status','Accept')->whereNotNull('visit_date');
            if ($req->filled('workPackages'))
            {
                // Fetch the geometry of the work package
                $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');
                $result = $result->whereRaw('ST_Within(geom, ?)', [$workPackageGeom]);

            }
        //    $getResultByVisitDate= $result->select('visit_date',DB::raw("count(*)"))->groupBy('visit_date')->get();  //get total count against visit_date
        $getResultByVisitDate=$result->select(DB::raw('DATE(visit_date) as visit_date'), DB::raw('count(*)'))
        ->whereNotNull('visit_date')
        ->groupBy(DB::raw('DATE(visit_date)'))
        ->get();

            $fpdf->AddPage('L', 'A4');
            $fpdf->SetFont('Arial', 'B', 22);
                //add Heading
                $fpdf->Cell(180, 15, strtoupper($req->ba) .' FFA',0,1);
                $fpdf->Cell(180, 25, 'PO NO :');
            // $fpdf->Cell(180, 25, $req->ba .' LKS ( '. ($req->from_date?? ' All ') . ' - ' . ($req->to_date?? ' All ').' )');
            $fpdf->Ln();
            $fpdf->SetFont('Arial', 'B', 16);
                // visit date table start
            $fpdf->Cell(100,7,'JUMLAH YANG DICATAT BERHADAPAN TARIKH LAWATAN',0,1);

            $fpdf->SetFillColor(169, 169, 169);
            $totalRecords = 0;

            $visitDates = [];
            foreach ($getResultByVisitDate as $visit_date)
            {
                $fpdf->SetFont('Arial', 'B', 9);
                $fpdf->Cell(50,7,$visit_date->visit_date,1,0,'C',true);
                $fpdf->Cell(50,7,$visit_date->count,1,0,'C');
                $fpdf->Ln();
                $totalRecords += $visit_date->count;
                $visitDates[]=$visit_date->visit_date;


            }
            $fpdf->Cell(50,7,'JUMLAH REKOD',1,0,'C',true);
            $fpdf->Cell(50,7,$totalRecords,1,0,'C');
            // visit date table end
            $fpdf->Ln();
            $fpdf->Ln();

            $pdfFileName = $req->ba.' - FFA - Table - Of - Contents - '.$req->from_date.' - '.$req->to_date.'.pdf';

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $pdfFileName . '"');
            $userID = Auth::user()->id;
            $folderName = 'D:/temp/temporary-FFA-folder-'.$userID;
            $folderPath = $folderName;
            // $folderName = 'temporary-substation-folder-'.$userID;
            // $folderPath = public_path('temp/'.$folderName);

            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            $pdfFilePath = $folderPath.'/'. $pdfFileName;

            $fpdf->output('F', $pdfFilePath);



            $response = [
                'pdfPath' => $pdfFileName,
                'visit_dates'=>$visitDates,
                'folder_name'=>$folderName
            ];

            return response()->json($response);
        }
        if (empty($req->from_date)) {
            $req['from_date'] = SavrFfaSub1::min('visit_date');
        }

        if (empty($req->to_date)) {
            $req['to_date'] = SavrFfaSub1::max('visit_date');
        }
      //  return $req;
        return view('Documents.download-lks',[
            'ba'=>$req->ba,
            'from_date'=>$req->from_date,
            'cycle'=>$req->cycle,
            'to_date'=>$req->to_date,
            'url'=>'ffa',
            'workPackage' =>$req->workPackages
        ]);

    }

}
