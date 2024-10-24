<?php

namespace App\Http\Controllers\web\FeederPillar;

use App\Constants\FeederPillarConstants;
use App\Http\Controllers\Controller;
use App\Models\FeederPillar;
use Illuminate\Http\Request;
use App\Traits\Filter;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\File;
use App\Models\WorkPackage;


class FeederPillarLKSController extends Controller
{
    use Filter;




    public function generateByVisitDate(Fpdf $fpdf, Request $req)
    {

        $result = FeederPillar::where('ba',Auth::user()->ba)->where('visit_date', $req->visit_date)->where('qa_status','Accept')->where('cycle',$req->cycle);
        $result = $result ->join('tbl_feeder_pillar_geom as g', 'tbl_feeder_pillar.geom_id', '=', 'g.id');

        if ($req->filled('workPackages'))
        {
            // Fetch the geometry of the work package
            $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');

            // Execute the query
            $result = $result ->whereRaw('ST_Within(g.geom, ?)', [$workPackageGeom]);

        }

        $data = $result->select(
                            'tbl_feeder_pillar.id',
                            'guard_status',
                            'image_advertisement_after_1',
                            'paint_status',
                            'image_name_plate',
                            'ba',
                            'feeder_pillar_image_1',
                            'feeder_pillar_image_2',
                            DB::raw("CASE WHEN (gate_status->>'unlocked')::text='true' THEN 'Ya' ELSE 'Tidak' END as unlocked"),
                            DB::raw("CASE WHEN (gate_status->>'demaged')::text='true' THEN 'Ya' ELSE 'Tidak' END as demaged"),
                            DB::raw("CASE WHEN (gate_status->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as other_gate"),
                            DB::raw("CASE WHEN (gate_status->>'other')::text='true' THEN (gate_status->>'other_value')::text ELSE '' END as gate_other_value"),
                            'vandalism_status',
                            'leaning_staus',
                            'rust_status',
                            'advertise_poster_status',
                            'visit_date',
                            'size',
                            'coordinate',
                            'image_gate',
                            'image_gate_2',
                            'total_defects',
                            'image_vandalism',
                            'image_vandalism_2',
                            'image_leaning',
                            'image_leaning_2',
                            'image_rust',
                            'image_rust_2',
                            'images_advertise_poster',
                            'images_advertise_poster_2',
                            DB::raw('ST_X(g.geom) as X'),
                            DB::raw('ST_Y(g.geom) as Y')
                        )->get();


        // $pdf = PDF::loadView('feeder-pillar.lks-feeder-pillar-template',['datas'=>$data,'ba'=>$req->ba , 'visit_date'=>$req->visit_date]);
        // $pdf->setPaper('A4', 'landscape');
        // $pdfFileName = $req->ba.' - Feeder-pillar - '.$req->visit_date.'.pdf';
        // $folderPath = 'temp/'.$req->folder_name .'/'. $pdfFileName;
        // $pdfFilePath = public_path( $folderPath);
        // if (file_exists($pdfFilePath)) {
        //     File::delete($pdfFilePath);
        // }
        // $pdf->save($pdfFilePath);

        // $response = [
        //     'pdfPath' => $pdfFileName,
        // ];

        // return response()->json($response);


        $fpdf->AddPage('L', 'A4');
        $fpdf->SetFont('Arial', 'B', 22);


        $fpdf->Cell(180, 25, Auth::user()->ba .' ' .$req->visit_date );
        $fpdf->Ln();

        $fpdf->SetFont('Arial', 'B', 16);

        $fpdf->Cell(50,7,'Jumlah Rekod',1);
        $fpdf->Cell(20,7,sizeof($data),1);

        $fpdf->Ln();
        $fpdf->Ln();

        $imagePath = public_path('assets/web-images/main-logo.png');
        $fpdf->Image($imagePath, 190, 20, 57, 0);
        $fpdf->SetFont('Arial', 'B', 9);
        $sr_no= 0;

        foreach ($data as $row) {
            if ($sr_no % 2 == 1 && $sr_no > 0) {
                $fpdf->AddPage('L', 'A4');

            }
            $sr_no++;
            $fpdf->Cell(120, 6, 'SR # : '.$sr_no ,0);

            // add feeder pilar images  Header
            $fpdf->Cell(45, 6, 'FEEDER PILLAR Gambar 1' ,0);
            $fpdf->Cell(60, 6, 'FEEDER PILLAR Gambar 2' ,0);
            $fpdf->Cell(40, 6, 'FP Plate' ,0);

            $fpdf->Ln();

            $fpdf->Cell(125, 6, 'ID : FP-' . $row->id);

            // add feeder pillar images
            $feeder_pillar_image_1 = config('globals.APP_IMAGES_LOCALE_PATH').$row->feeder_pillar_image_1;
            if ($row->feeder_pillar_image_1 != '' && file_exists($feeder_pillar_image_1))
            {

                $fpdf->Image($feeder_pillar_image_1, $fpdf->GetX(), $fpdf->GetY(), 30, 30);
            }
            $fpdf->Cell(45,6);
            // $fpdf->Ln();

            $feeder_pillar_image_2 = config('globals.APP_IMAGES_LOCALE_PATH').$row->feeder_pillar_image_2;
            if ($row->feeder_pillar_image_2 != '' && file_exists($feeder_pillar_image_2))
            {
                $fpdf->Image($feeder_pillar_image_2, $fpdf->GetX(), $fpdf->GetY(), 30, 30);
            }

            $fpdf->Cell(50,6);
            $image_name_plate = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_name_plate;
            if ($row->image_name_plate != ''  && file_exists($image_name_plate))
            {
                $fpdf->Image($image_name_plate, $fpdf->GetX(), $fpdf->GetY(), 30, 30);
            }

            $fpdf->Ln();
            $fpdf->Cell(60, 6, 'Tarikh Lawatan : ' . $row->visit_date);     //VISIT  DATE
            $fpdf->Ln();
            $fpdf->Cell(60, 6, 'Saiz : ' . $row->size);                     //SIZE
            $fpdf->Ln();
            $fpdf->Cell(60, 6, 'Koordinat : '.$row->y . ' , ' . $row->x);          //COORDINATE
            $fpdf->Ln();
            $fpdf->Cell(60, 8, 'Bil Janggal : ' . $row->total_defects);     //TOTAL DEFECTS
            $fpdf->Ln();





            $fpdf->SetFont('Arial', 'B', 8);

            $fpdf->SetFillColor(169, 169, 169);

            $fpdf->Cell(70, 7, 'Pintu Pagar', 1, 0, 'C', true);  //GATE
            $fpdf->Cell(130, 7, 'Status Lain', 1, 0, 'C', true);  // OTHERS STATUS
            $fpdf->Cell(60,7,'Iklan Haram ','LTR', 0,'C',true);    // POSTER
        //    $fpdf->Cell(50,7,'Pembersihan iklan Haram/Banner','LTR', 0,'C',true); //GRASS

            $fpdf->Ln();

            $fpdf->Cell(22, 7, 'Berkunci', 1,0,'L',true);   //unlocked
            $fpdf->Cell(20, 7, 'Rosak', 1,0,'L',true);    //damaged
            $fpdf->Cell(28, 7, 'Lain', 1,0,'L',true);      //other

            $fpdf->Cell(30, 7, 'Vandalism', 1, 0, 'L', true); //Vandalism
            $fpdf->Cell(25, 7, 'Condong', 1, 0, 'L', true);   //Leaning
            $fpdf->Cell(25, 7, 'Karat', 1, 0, 'L', true);  //Rusty
            $fpdf->Cell(25, 7, 'FP Guard', 1, 0, 'L', true);  //Rusty
            $fpdf->Cell(25, 7, 'Cat Pudar', 1, 0, 'L', true);  //Rusty


            $fpdf->Cell(60, 7, '/ Banner', 'RBL', 0,'C',true); //advertisement
          //  $fpdf->Cell(50, 7, '& Menutup Pintu Pencawang atau','RL', 0,'C',true); //GRASS



            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->Ln();
            $fpdf->Cell(22, 7, $row->unlocked, 1);
            $fpdf->Cell(20, 7, $row->demaged, 1);
            $fpdf->Cell(28, 7, $row->other_gate == 'Ya' ?$row->gate_other_value : '' , 1);

            $fpdf->Cell(30, 7, $row->vandalism_status=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(25, 7, $row->leaning_staus=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(25, 7, $row->rust_status=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(25, 7, $row->guard_status=='Yes' ?'Ya' : 'Tidak', 1);
            $fpdf->Cell(25, 7, $row->paint_status=='Yes' ?'Ya' : 'Tidak', 1);

            $fpdf->Cell(60, 7, $row->advertise_poster_status=='Yes' ?'Ya' : 'Tidak', 1);


            $fpdf->SetFillColor(169, 169, 169);
            //$fpdf->Cell(50,7,' Pintu Pagar','RBL', 0,'C',true); //GRASS

            $fpdf->Ln();

            $image_gate = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_gate;
            if ($row->image_gate != '' && file_exists($image_gate))
            {
                $fpdf->Image($image_gate, $fpdf->GetX()+5, $fpdf->GetY(), 20, 20);

            }
                $fpdf->Cell(70, 30, '');


            $image_vandalism = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_vandalism;
            if ($row->image_vandalism != ''  && file_exists($image_vandalism))
            {
                $fpdf->Image($image_vandalism, $fpdf->GetX()+2, $fpdf->GetY(), 20, 20);

            }
                $fpdf->Cell(30, 30, '');




            $image_leaning = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_leaning;
            if ($row->image_leaning != ''  && file_exists($image_leaning))
            {
                $fpdf->Image($image_leaning, $fpdf->GetX()+2, $fpdf->GetY(), 20, 20);

            }
                $fpdf->Cell(25, 30, '');
                //$fpdf->Cell(25, 30, '');




            $image_rust = config('globals.APP_IMAGES_LOCALE_PATH').$row->image_rust;
            if ($row->image_rust != '' && file_exists($image_rust))
            {
                $fpdf->Image($image_rust, $fpdf->GetX()+2, $fpdf->GetY(), 20, 20);

            }
                $fpdf->Cell(25, 30, '');
                $fpdf->Cell(25, 30, '');




            $images_advertise_poster = config('globals.APP_IMAGES_LOCALE_PATH').$row->images_advertise_poster;
            if ($row->images_advertise_poster != ''  && file_exists($images_advertise_poster))
            {
                $fpdf->Image($images_advertise_poster, $fpdf->GetX()+4, $fpdf->GetY(), 20, 20);

            }
                $fpdf->Cell(30, 30, '');
             //   $fpdf->Cell(25, 30, '');


            //     if ($row->image_gate_2 != '' && file_exists(public_path($row->image_gate_2)))
            //     {
            //         $fpdf->Image(public_path($row->image_gate_2), $fpdf->GetX(), $fpdf->GetY(), 20, 20);
            //     }
            //     $fpdf->Cell(25, 25);



            // if ($row->image_advertisement_after_1 != '' && file_exists(public_path($row->image_advertisement_after_1))) {
            //     $fpdf->Image(public_path($row->image_advertisement_after_1), $fpdf->GetX()+3, $fpdf->GetY(), 20, 20);

            // }
            //     $fpdf->Cell(25, 25, '');


            $fpdf->Ln();


            // Move to the next line for the next row
        }

        $pdfFileName = Auth::user()->ba.' - Feeder-pillar - '.$req->visit_date.'.pdf';
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFileName . '"');

        $folderPath = $req->folder_name .'/'. $pdfFileName;
        $pdfFilePath = $folderPath;
        if (file_exists($pdfFilePath)) {
            File::delete($pdfFilePath);
        }

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

            $result = FeederPillar::query();

            $result = $this->filter($result , 'visit_date',$req)->where('qa_status','Accept')->whereNotNull('visit_date');
            if ($req->filled('workPackages'))
            {
                // Fetch the geometry of the work package
                $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');

                // Execute the query
                $result = $result
                    ->join('tbl_feeder_pillar_geom as g', 'tbl_feeder_pillar.geom_id', '=', 'g.id')
                    ->whereRaw('ST_Within(g.geom, ?)', [$workPackageGeom]);

            }
            $getResultByVisitDate= $result->select('visit_date',DB::raw("count(*)"))->groupBy('visit_date')->get();  //get total count against visit_date


            $fpdf->AddPage('L', 'A4');
            $fpdf->SetFont('Arial', 'B', 22);
                //add Heading
            $fpdf->Cell(180, 15, strtoupper(Auth::user()->ba) .' FEEDER PILLAR',0,1);
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

            $pdfFileName = Auth::user()->ba.' - Feeder-pillar - Table - Of - Contents - '.$req->from_date.' - '.$req->from_date.'.pdf';

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $pdfFileName . '"');
            $userID = Auth::user()->id;
            $folderName = 'D:/temp/temporary-feeder-pillar-folder-'.$userID;
            $folderPath = $folderName;

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
            $req['from_date'] = FeederPillar::min('visit_date');
        }

        if (empty($req->to_date)) {
            $req['to_date'] = FeederPillar::max('visit_date');
        }

        return view('Documents.download-lks',[
                        'ba'=>$req->ba,
                        'from_date'=>$req->from_date,
                        'cycle'=>$req->cycle,
                        'to_date'=>$req->to_date,
                        'url'=>'feeder-pillar',
                        'workPackage' =>$req->workPackages
                    ]);

    }
}
