<?php

namespace App\Http\Controllers\web\CableBridge;

use App\Http\Controllers\Controller;
use App\Models\CableBridge;
use App\Traits\Filter;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\WorkPackage;

class CableBridgeLKSController extends Controller
{
    use Filter;



    public function generateByVisitDate(Fpdf $fpdf, Request $req)
    {
        $result = CableBridge::query();


        $result = LinkBox::where('ba',$req->ba)
                            ->where('visit_date', $req->visit_date)
                            ->where('qa_status','Accept')
                            ->where('cycle',$req->cycle)
                            ->join('tbl_cable_bridge_geom as g', 'tbl_cable_bridge.geom_id', '=', 'g.id');


        if ($req->filled('workPackages'))
        {
            // Fetch the geometry of the work package
            $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');
            $result = $result->whereRaw('ST_Within(g.geom, ?)', [$workPackageGeom]);

        }

        $data = $result->select(
                            'id',
                            'ba',
                            'cable_bridge_image_1',
                            'cable_bridge_image_2',
                            'bushes_status',
                            'vandalism_status',
                            'pipe_staus',
                            'collapsed_status',
                            'rust_status',
                            'start_date',
                            'end_date',
                            'visit_date',
                            'voltage',
                            'coordinate',
                            'image_pipe',
                            'image_pipe_2',
                            'total_defects',
                            'image_vandalism',
                            'image_vandalism_2',
                            'image_collapsed',
                            'image_collapsed_2',
                            'image_rust',
                            'image_rust_2',
                            'images_bushes',
                            'images_bushes_2',
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
            if ($sr_no > 0 && $sr_no % 2 == 0) {
                $fpdf->AddPage('L', 'A4');
            }
            $sr_no++;
            $fpdf->Cell(150, 6, 'SR # : '.$sr_no ,0);

            // add substation image 1 and substation image 2
            $fpdf->Cell(50, 6, 'CABLE BRIDGE Gambar 1' ,0);
            $fpdf->Cell(50, 6, 'CABLE BRIDGE Gambar 2' ,0);
            $fpdf->Ln();

            $fpdf->Cell(155, 6, 'ID : CB-' . $row->id);

            $cable_bridge_image_1 = config('globals.APP_IMAGES_LOCALE_PATH').$row->cable_bridge_image_1;
            if ($row->cable_bridge_image_1 != '' && file_exists($cable_bridge_image_1))
            {
                $fpdf->Image($cable_bridge_image_1, $fpdf->GetX(), $fpdf->GetY(), 40, 40);
            }
            $fpdf->Cell(50,6);
            // $fpdf->Ln();

            $cable_bridge_image_2 = config('globals.APP_IMAGES_LOCALE_PATH').$row->cable_bridge_image_2;
            if ($row->cable_bridge_image_2 != '' && file_exists($cable_bridge_image_2))
            {
                $fpdf->Image($cable_bridge_image_2, $fpdf->GetX(), $fpdf->GetY(), 40, 40);
            }
            $fpdf->Ln();
            $fpdf->Cell(60, 6, 'Tarikh Lawatan : ' . $row->visit_date);                         //VISIT  DATE
            $fpdf->Ln();
            $fpdf->Cell(60, 6, 'Voltan : ' . $row->voltage);                                    //VOLTAGE
            $fpdf->Ln();
            $fpdf->Cell(60, 6, 'TO - FROM : ' . $row->end_date . ' - ' . $row->start_date);
            $fpdf->Ln();
            $fpdf->Cell(60, 6, 'Koordinat : '.$row->y . ' , ' . $row->x);                              //COORDINATE
            $fpdf->Ln();
            $fpdf->Cell(60, 6, 'Bil Janggal : ' . $row->total_defects);                         //TOTAL DEFECTS
            $fpdf->Ln();
            $fpdf->Ln();
            $fpdf->Ln();





            $fpdf->SetFont('Arial', 'B', 8);

            $fpdf->SetFillColor(169, 169, 169);

            $fpdf->Cell(54, 7, 'VANDALISM', 1, 0, 'L', true);
            $fpdf->Cell(54, 7, 'Runtuh Status', 1, 0, 'L', true); // colapsed
            $fpdf->Cell(54, 7, 'Berkarat', 1, 0, 'L', true); // Rsuty
            $fpdf->Cell(54, 7, 'Bersemak Status', 1, 0, 'L', true); //BUSHES STATUS
            $fpdf->Cell(54, 7, 'Paip Status', 1, 0, 'L', true);  //PIPE STATUS

            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->Ln();

            $fpdf->Cell(54, 7, $row->vandalism_status=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(54, 7, $row->collapsed_status=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(54, 7, $row->rust_status=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(54, 7, $row->bushes_status=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(54, 7, $row->pipe_staus=='Yes' ?'Ya' : 'Tidak', 1);

            $fpdf->Ln();

            $image_vandalism = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_vandalism;
            if ($row->image_vandalism != '' && file_exists($image_vandalism))
            {
                $fpdf->Image($image_vandalism, $fpdf->GetX(), $fpdf->GetY(), 27, 30);
                $fpdf->Cell(27);
            } else {
                $fpdf->Cell(27, 7, '');
            }

            // $fpdf->Ln();

            $image_vandalism_2 = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_vandalism_2;
            if ($row->image_vandalism_2 != '' && file_exists($image_vandalism_2))
            {
                $fpdf->Image($image_vandalism_2, $fpdf->GetX(), $fpdf->GetY(), 27, 30);
                $fpdf->Cell(27);
            } else {
                $fpdf->Cell(27, 7, '');
            }

            $image_collapsed = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_collapsed;
            if ($row->image_collapsed != '' && file_exists($image_collapsed))
            {
                $fpdf->Image($image_collapsed, $fpdf->GetX(), $fpdf->GetY(), 27, 30);
                $fpdf->Cell(27);
            } else {
                $fpdf->Cell(27, 7, '');
            }

            $image_collapsed_2 = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_collapsed_2;
            if ($row->image_collapsed_2 != '' && file_exists($image_collapsed_2))
            {
                $fpdf->Image($image_collapsed_2, $fpdf->GetX(), $fpdf->GetY(), 27, 30);
                $fpdf->Cell(27);
            } else {
                $fpdf->Cell(27, 7, '');
            }

            $image_rust = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_rust;
            if ($row->image_rust != '' && file_exists($image_rust))
            {
                $fpdf->Image($image_rust, $fpdf->GetX(), $fpdf->GetY(), 27, 30);
                $fpdf->Cell(27);
            } else {
                $fpdf->Cell(27, 7, '');
            }

            $image_rust_2 = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_rust_2;
            if ($row->image_rust_2 != '' && file_exists($image_rust_2))
            {
                $fpdf->Image($image_rust_2, $fpdf->GetX(), $fpdf->GetY(), 27, 30);
                $fpdf->Cell(27);
            } else {
                $fpdf->Cell(27, 7, '');
            }

            $images_bushes = config('globals.APP_IMAGES_LOCALE_PATH').$row->images_bushes;
            if ($row->images_bushes != '' && file_exists($images_bushes))
            {
                $fpdf->Image($images_bushes, $fpdf->GetX(), $fpdf->GetY(), 27, 30);
                $fpdf->Cell(27);
            } else {
                $fpdf->Cell(27, 7, '');
            }

            $images_bushes_2 = config('globals.APP_IMAGES_LOCALE_PATH').$row->images_bushes_2;
            if ($row->images_bushes_2 != '' && file_exists($images_bushes_2))
            {
                $fpdf->Image($images_bushes_2, $fpdf->GetX(), $fpdf->GetY(), 27, 30);
                $fpdf->Cell(27);
            } else {
                $fpdf->Cell(27, 7, '');
            }

            $image_pipe = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_pipe;
            if ($row->image_pipe != '' && file_exists($image_pipe))
            {
                $fpdf->Image($image_pipe, $fpdf->GetX(), $fpdf->GetY(), 27, 30);
                $fpdf->Cell(27);
            } else {
                $fpdf->Cell(27, 7, '');
            }

            $image_pipe_2 = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_pipe_2;
            if ($row->image_pipe_2 != '' && file_exists($image_pipe_2))
            {
                $fpdf->Image($image_pipe_2, $fpdf->GetX(), $fpdf->GetY(), 27, 30);
                $fpdf->Cell(27);
            } else {
                $fpdf->Cell(27, 7, '');
            }

            $fpdf->Ln();
            $fpdf->Ln();
            $fpdf->Ln();
            $fpdf->Ln();
            $fpdf->Ln();

            // Move to the next line for the next row
        }

        $pdfFileName = $req->ba.' - Cable-Bridge - '.$req->visit_date.'.pdf';
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
        if ($req->ajax())
        {

            $result = CableBridge::query();

            $result = $this->filter($result , 'visit_date',$req)->where('qa_status','Accept')->whereNotNull('visit_date');
            if ($req->filled('workPackages'))
            {
                // Fetch the geometry of the work package
                $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');
                $result = $result
                    ->join('tbl_cable_bridge_geom as g', 'tbl_cable_bridge.geom_id', '=', 'g.id')
                    ->whereRaw('ST_Within(g.geom, ?)', [$workPackageGeom]);

            }
            $getResultByVisitDate= $result->select('visit_date',DB::raw("count(*)"))->groupBy('visit_date')->get();  //get total count against visit_date


            $fpdf->AddPage('L', 'A4');
            $fpdf->SetFont('Arial', 'B', 22);
                //add Heading
                $fpdf->Cell(180, 15, strtoupper($req->ba) .' CABLE BRIDGE',0,1);
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

            $pdfFileName = $req->ba.' - Pencawang - Table - Of - Contents - '.$req->from_date.' - '.$req->from_date.'.pdf';

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $pdfFileName . '"');
            $userID = Auth::user()->id;
            $folderName = 'D:/temp/temporary-cable-bridge-folder-'.$userID;
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
            $req['from_date'] = CableBridge::min('visit_date');
        }

        if (empty($req->to_date)) {
            $req['to_date'] = CableBridge::max('visit_date');
        }

        return view('lks.download-lks',[
            'ba'=>$req->ba,
            'from_date'=>$req->from_date,
            'cycle'=>$req->cycle,
            'to_date'=>$req->to_date,
            'url'=>'cable-bridge',
            'workPackages'=>$workPackages
        ]);

    }

}
