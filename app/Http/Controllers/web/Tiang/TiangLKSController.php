<?php

namespace App\Http\Controllers\web\Tiang;

use App\Constants\TiangConstants;
use App\Http\Controllers\Controller;
use App\Models\Tiang;
use App\Models\WorkPackage;
use Illuminate\Http\Request;
use App\Traits\Filter;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class TiangLKSController extends Controller
{
    //

    use Filter;



    public function generateByVisitDate(Fpdf $fpdf, Request $req){

        // dd($req);

        // return Tiang::first();


        $result = Tiang::where('ba',Auth::user()->ba)->where('review_date', $req->visit_date)->where('qa_status','Accept')->where('cycle',$req->cycle);



          $data = $result

           ->select('tbl_savr.id','fp_road','fp_name','tiang_no','review_date','section_from','section_to','total_defects','talian_utama_connection','talian_utama',
           'pole_image_1','pole_image_2','pole_image_3','pole_image_4','pole_image_5',
           'size_tiang','jenis_tiang','abc_span','pvc_span','bare_span',
           'jarak_kelegaan','talian_spec','arus_pada_tiang',
           'tiang_defect_image','talian_defect_image','umbang_defect_image','ipc_defect_image','blackbox_defect_image','jumper_image','kilat_defect_image',
           'servis_defect_image','pembumian_defect_image','bekalan_dua_defect_image','kaki_lima_defect_image', 'tapak_road_img','tapak_no_vehicle_entry_img',
           'tapak_no_vehicle_entry_img','kawasan_bend_img','kawasan_road_img','kawasan_forest_img','kawasan_other_img','coords',
            DB::raw("ST_Y(tbl_savr.geom) as Y"),
            DB::raw("ST_X(tbl_savr.geom) as X"),
            DB::raw("CASE WHEN (tiang_defect->>'cracked')::text='true' THEN 'Ya' ELSE 'Tidak' END as tiang_defect_cracked"),
            DB::raw("CASE WHEN (tiang_defect->>'leaning')::text='true' THEN 'Ya' ELSE 'Tidak' END as tiang_defect_leaning"),
            DB::raw("CASE WHEN (tiang_defect->>'dim')::text='true' THEN 'Ya' ELSE 'Tidak' END as tiang_defect_dim"),
            DB::raw("CASE WHEN (tiang_defect->>'creepers')::text='true' THEN 'Ya' ELSE 'Tidak' END as tiang_defect_creepers"),
            DB::raw("CASE WHEN (tiang_defect->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as tiang_defect_other"),
            DB::raw("tiang_defect->>'other_value' as tiang_defect_other_value"),

            DB::raw("CASE WHEN (talian_defect->>'joint')::text='true' THEN 'Ya' ELSE 'Tidak' END as talian_defect_joint"),
            DB::raw("CASE WHEN (talian_defect->>'need_rentis')::text='true' THEN 'Ya' ELSE 'Tidak' END as talian_defect_need_rentis"),
            DB::raw("CASE WHEN (talian_defect->>'ground')::text='true' THEN 'Ya' ELSE 'Tidak' END as talian_defect_ground"),
            DB::raw("CASE WHEN (talian_defect->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as talian_defect_other"),
            DB::raw("talian_defect->>'other_value' as talian_defect_other_value"),

            DB::raw("CASE WHEN (umbang_defect->>'breaking')::text='true' THEN 'Ya' ELSE 'Tidak' END as umbang_defect_breaking"),
            DB::raw("CASE WHEN (umbang_defect->>'creepers')::text='true' THEN 'Ya' ELSE 'Tidak' END as umbang_defect_creepers"),
            DB::raw("CASE WHEN (umbang_defect->>'cracked')::text='true' THEN 'Ya' ELSE 'Tidak' END as umbang_defect_cracked"),
            DB::raw("CASE WHEN (umbang_defect->>'stay_palte')::text='true' THEN 'Ya' ELSE 'Tidak' END as umbang_defect_stay_palte"),
            DB::raw("CASE WHEN (umbang_defect->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as umbang_defect_other"),
            DB::raw("umbang_defect->>'other_value' as umbang_defect_other_value"),

            DB::raw("CASE WHEN (ipc_defect->>'burn')::text='true' THEN 'Ya' ELSE 'Tidak' END as ipc_defect_burn"),
            DB::raw("CASE WHEN (ipc_defect->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as ipc_defect_other"),
            DB::raw("ipc_defect->>'other_value' as ipc_defect_other_value"),

            DB::raw("CASE WHEN (blackbox_defect->>'cracked')::text='true' THEN 'Ya' ELSE 'Tidak' END as blackbox_defect_cracked"),
            DB::raw("CASE WHEN (blackbox_defect->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as blackbox_defect_other"),
            DB::raw("tiang_defect->>'other_value' as tiang_defect_other_value"),

            DB::raw("CASE WHEN (jumper->>'sleeve')::text='true' THEN 'Ya' ELSE 'Tidak' END as jumper_sleeve"),
            DB::raw("CASE WHEN (jumper->>'burn')::text='true' THEN 'Ya' ELSE 'Tidak' END as jumper_burn"),
            DB::raw("CASE WHEN (jumper->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as jumper_other"),
            DB::raw("jumper->>'other_value' as jumper_other_value"),

            DB::raw("CASE WHEN (kilat_defect->>'broken')::text='true' THEN 'Ya' ELSE 'Tidak' END as kilat_defect_broken"),
            DB::raw("CASE WHEN (kilat_defect->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as kilat_defect_other"),
            DB::raw("kilat_defect->>'other_value' as kilat_defect_other_value"),

            DB::raw("CASE WHEN (servis_defect->>'roof')::text='true' THEN 'Ya' ELSE 'Tidak' END as servis_defect_roof"),
            DB::raw("CASE WHEN (servis_defect->>'won_piece')::text='true' THEN 'Ya' ELSE 'Tidak' END as servis_defect_won_piece"),
            DB::raw("CASE WHEN (servis_defect->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as servis_defect_other"),
            DB::raw("servis_defect->>'other_value' as servis_defect_other_value"),

            DB::raw("CASE WHEN (pembumian_defect->>'netural')::text='true' THEN 'Ya' ELSE 'Tidak' END as pembumian_defect_netural"),
            DB::raw("CASE WHEN (pembumian_defect->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as pembumian_defect_other"),
            DB::raw("pembumian_defect->>'other_value' as pembumian_defect_other_value"),

            DB::raw("CASE WHEN (bekalan_dua_defect->>'damage')::text='true' THEN 'Ya' ELSE 'Tidak' END as bekalan_dua_defect_damage"),
            DB::raw("CASE WHEN (bekalan_dua_defect->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as bekalan_dua_defect_other"),
            DB::raw("bekalan_dua_defect->>'other_value' as bekalan_dua_defect_other_value"),

            DB::raw("CASE WHEN (kaki_lima_defect->>'date_wire')::text='true' THEN 'Ya' ELSE 'Tidak' END as kaki_lima_defect_date_wire"),
            DB::raw("CASE WHEN (kaki_lima_defect->>'burn')::text='true' THEN 'Ya' ELSE 'Tidak' END as kaki_lima_defect_burn"),
            DB::raw("CASE WHEN (kaki_lima_defect->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as kaki_lima_defect_other"),
            DB::raw("kaki_lima_defect->>'other_value' as kaki_lima_defect_other_value"),

            DB::raw("CASE WHEN (tapak_condition::json->>'road')::text='true' THEN 'Ya' ELSE 'Tidak' END as tapak_condition_road"),
            DB::raw("CASE WHEN (tapak_condition::json->>'side_walk')::text='true' THEN 'Ya' ELSE 'Tidak' END as tapak_condition_side_walk"),
            DB::raw("CASE WHEN (tapak_condition::json->>'vehicle_entry')::text='true' THEN 'Ya' ELSE 'Tidak' END as tapak_condition_vehicle_entry"),
            DB::raw("CASE WHEN (kawasan::json->>'bend')::text='true' THEN 'Ya' ELSE 'Tidak' END as kawasan_bend"),
            DB::raw("CASE WHEN (kawasan::json->>'road')::text='true' THEN 'Ya' ELSE 'Tidak' END as kawasan_road"),
            DB::raw("CASE WHEN (kawasan::json->>'forest')::text='true' THEN 'Ya' ELSE 'Tidak' END as kawasan_forest"),
            DB::raw("CASE WHEN (kawasan::json->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as kawasan_other"),
            DB::raw("kawasan::json->>'other_value' as kawasan_other_value"),
            DB::raw('ST_X(g.geom) as X'), DB::raw('ST_Y(g.geom) as Y')

        );

        $data = $data->join('tbl_savr_geom as g', 'tbl_savr.geom_id', '=', 'g.id');
    if ($req->filled('workPackages'))
    {
        // Fetch the geometry of the work package
        $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');

        // Execute the query
        $data = $data  ->whereRaw('ST_Within(g.geom, ?)', [$workPackageGeom]);

    }

     $data =  $data->get();



            $fpdf->AddPage('L', 'A4');
            $fpdf->SetFont('Arial', 'B', 22);

            $fpdf->Cell(180, 25, Auth::user()->ba .' ' .$req->visit_date );
            $fpdf->Ln();
            $fpdf->SetFont('Arial', 'B', 16);

            $fpdf->Cell(50,7,'Jumlah Rekod',1);
            $fpdf->Cell(20,7,sizeof($data),1);


            $fpdf->Ln();
            $fpdf->Ln();
            $images = TiangConstants::TIANG_IMAGES;

            $imagePath = public_path('assets/web-images/main-logo.png');
            $fpdf->Image($imagePath, 190, 20, 57, 0);
            $fpdf->SetFont('Arial', 'B', 9);


        $sr_no =0;
        foreach ($data as $row) {

            if ($sr_no > 0) {
                $fpdf->AddPage('L', 'A4');
            }
            $sr_no++;
            $fpdf->Cell(100, 6, 'SR # : '.$sr_no ,0);
            $fpdf->Ln();

            $fpdf->Cell(100, 6, 'ID : SAVR-'.$row->id );


            $fpdf->Ln();
            $fpdf->Cell(60, 6, 'Tarikh Lawatan : '.$row->review_date);          //VISIT  DATE
            $fpdf->Ln();
            $fpdf->Cell(60, 6, 'TIANG NO : '.$row->tiang_no);
            $fpdf->Ln();
            $fpdf->Cell(60, 6, 'TO - FROM : '.$row->section_from .' - ' .  $row->section_to);
            $fpdf->Ln();
        //    $fpdf->Cell(60, 6, 'Koordinat : '.$row->x .' , '. $row->y);         //COORDINATE
            $fpdf->Cell(60, 6, 'Koordinat : '.$row->y . ' , ' . $row->x);
            $fpdf->Ln();
            $fpdf->Cell(60, 6, 'Bil Janggal : ' .$row->total_defects);          //TOTAL DEFECTS
            $fpdf->Ln();

           $newArr = $row;


            // set font for table header
            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->SetFillColor(169, 169, 169);

            // table 0 header # 1/2 bare span table header # 1 start

            $fpdf->Cell(40, 6, 'Talian Utama (M) / Servis (S)' ,1,0,'C',true );         //Main Line (M) / Servis (S)
            $fpdf->Cell(45, 6, 'Bilangan Perkhidmatan Terlibat' ,1,0,'C',true );        //Number of Services Involves
            $fpdf->Cell(40, 6, 'Jarak Kelegaan (meter)' ,1,0,'C',true );                //Clearance Distance
            $fpdf->Cell(65, 6, 'Spesifikasi Kelegaan Talian' ,1,0,'C',true );           //Line clearance specifications
            $fpdf->Cell(80, 6, 'Pemeriksaan Kebocoran Arus pada Tiang' ,1,0,'C',true ); //Inspection of current leakage on the pole

            // table 0 header # 1/2 bare span table header # 1 end
            $fpdf->Ln();

            $fpdf->SetFillColor(255, 255, 255);
            //table # 0 body values start

            $fpdf->Cell(40, 6, $row->talian_utama_connection ,1,0,'C',true );
            $fpdf->Cell(45, 6, $row->talian_utama ,1,0,'C',true );
            $fpdf->Cell(40, 6, $row->jarak_kelegaan ,1,0,'C',true );
            $fpdf->Cell(65, 6, $row->talian_spec ,1,0,'C',true );
            $fpdf->Cell(80, 6, $row->arus_pada_tiang ,1,0,'C',true );


            //table # 0 body values end

            $fpdf->Ln();
            $fpdf->Ln();



            //header # 1/2 bare span table header end
            $fpdf->Ln();


           // set font for table header
           $fpdf->SetFillColor(169, 169, 169);

           // header # 1/1 bare span table header # 1 start

           $fpdf->Cell(40, 6, 'TIANG' ,1,0,'C',true );
           $fpdf->Cell(92, 6, 'ABC (SPAN)' ,1,0,'C',true );
           $fpdf->Cell(69, 6, 'PVC (SPAN)' ,1,0,'C',true );
           $fpdf->Cell(69, 6, 'BARE (SPAN)' ,1,0,'C',true );
           //header # 1/1 bare span table header end
           $fpdf->Ln();

           //header # 1/2 bare span table header # 1 start

           $fpdf->Cell(20, 6, 'Size Tiang' ,1,0,'L',true );  // size tiang header
           $fpdf->Cell(20, 6, 'Jenis Tiang' ,1,0,'L',true ); // jenis tiang header

           $fpdf->Cell(23, 6, '3 X 185' ,1,0,'L',true ); // abc span header start
           $fpdf->Cell(23, 6, '3 X 95' ,1,0,'L',true );
           $fpdf->Cell(23, 6, '3 X 16' ,1,0,'L',true );
           $fpdf->Cell(23, 6, '1 X 16' ,1,0,'L',true ); // abc span header end

            // pvc span header start
           $fpdf->Cell(23, 6, '19/064' ,1,0,'L',true );
           $fpdf->Cell(23, 6, '7/083' ,1,0,'L',true );
           $fpdf->Cell(23, 6, '7/044' ,1,0,'L',true ); // pvc span header end

           // bare span header start
           $fpdf->Cell(23, 6, '7/173' ,1,0,'L',true );
           $fpdf->Cell(23, 6, '7/122' ,1,0,'L',true );
           $fpdf->Cell(23, 6, '3/132' ,1,0,'L',true ); // bare span header end

            //header # 1/2 bare span table header # 1 end

           $fpdf->Ln();


            // table #1 body
           $fpdf->Cell(20, 6, $row->size_tiang ,1 );
           $fpdf->Cell(20, 6,$row->jenis_tiang,1);

           if ($row->abc_span != '') {
                $abc_span = json_decode($row->abc_span);
                $fpdf->Cell(23, 6, $abc_span->s3_185 ,1);
                $fpdf->Cell(23, 6, $abc_span->s3_95 ,1 );
                $fpdf->Cell(23, 6, $abc_span->s3_16 ,1 );
                $fpdf->Cell(23, 6, $abc_span->s1_16 ,1 );
           }

           if ($row->pvc_span != '') {
                $pvc_span = json_decode($row->pvc_span);
                $fpdf->Cell(23, 6, $pvc_span->s19_064 ,1 );
                $fpdf->Cell(23, 6, $pvc_span->s7_083 ,1 );
                $fpdf->Cell(23, 6, $pvc_span->s7_044 ,1);
           }

           if ($row->bare_span != '') {
                $bare_span = json_decode($row->bare_span);
                $fpdf->Cell(23, 6, $bare_span->s7_173 ,1);
                $fpdf->Cell(23, 6, $bare_span->s7_122 ,1);
                $fpdf->Cell(23, 6, $bare_span->s3_132 ,1);
           }

            // table #1 body end


           $fpdf->Ln();
           $fpdf->Ln();
           $fpdf->Ln();

           // table # 2 header 1/2

           $fpdf->Cell(100, 6, "Tiang" ,1,0,'C',true);           //Pole
           $fpdf->Cell(120, 6, "Talian (Utama / Servis)" ,1,0,'C',true);  //Line (Main / Service)
           $fpdf->Cell( 50, 6, "Umbang" ,1,0,'C',true);     //Umbang

           $fpdf->Ln();

           // table # 2 header 2/2
                                                                                //POLE
           $fpdf->Cell(15, 6, "Reput" ,1,0,'C',true);                               //Cracked
           $fpdf->Cell(15, 6, "Condong" ,1,0,'C',true);                             //Leaning
           $fpdf->Cell(30, 6, "No Tiang Pudar" ,1,0,'C',true);                      //No. Dim Post
           $fpdf->Cell(15, 6, "Creepers" ,1,0,'C',true);                            //Creepers
           $fpdf->Cell(25, 6, "Lain-lain" ,1,0,'C',true);                           //Others
                                                                                // Line (Main / Service)
           $fpdf->Cell(15, 6, "IPC" ,1,0,'C',true);                               //Joint
           $fpdf->Cell(20, 6, "Perlu Rentis" ,1,0,'C',true);                        //Need Rentis
           $fpdf->Cell(45, 6, "Tidak Patuh Ground Clearance" ,1,0,'C',true);        //Not Comply With Ground Clearance
           $fpdf->Cell(40, 6, "Lain-lain" ,1,0,'C',true);                           //Others
                                                                                // Umbang 1/2
           $fpdf->Cell(25, 6, "Kendur/Putus" ,1,0,'C',true);                        //Sagging/Breaking
           $fpdf->Cell(25, 6, "Ulan (Creepers)" ,1,0,'C',true);                     //Creepers

           // table # 2 header 2/2 end


           $fpdf->Ln();
           $fpdf->SetFillColor(255, 255, 255);

           // table # 2 body values start


           $fpdf->Cell(15, 6, $row->tiang_defect_cracked,1,0,'C',true);         // Pole Values
           $fpdf->Cell(15, 6, $row->tiang_defect_leaning ,1,0,'C',true);
           $fpdf->Cell(30, 6, $row->tiang_defect_dim ,1,0,'C',true);
           $fpdf->Cell(15, 6, $row->tiang_defect_creepers ,1,0,'C',true);
           $fpdf->Cell(25, 6, $row->tiang_defect_other_value ,1,0,'C',true);

           $fpdf->Cell(15, 6, $row->talian_defect_joint ,1,0,'C',true);                           // Line (Main / Service) Values
           $fpdf->Cell(20, 6, $row->talian_defect_need_rentis ,1,0,'C',true);
           $fpdf->Cell(45, 6, $row->talian_defect_ground ,1,0,'C',true);
           $fpdf->Cell(40, 6, $row->talian_defect_other_value ,1,0,'C',true);

           $fpdf->Cell(25, 6, $row->umbang_defect_breaking ,1,0,'C',true);    // Umbang 1/2 Values
           $fpdf->Cell(25, 6, $row->umbang_defect_creepers ,1,0,'C',true);

           // table # 2 body values start


           $fpdf->Ln();
           $fpdf->Ln();

           $fpdf->SetFillColor(169, 169, 169);


           // tbale # 3 header 1/2
           $fpdf->Cell(105, 6, "Umbang" ,1,0,'C',true);     //Umbang
           $fpdf->Cell(45, 6, "IPC" ,1,0,'C',true);         //IPC
           $fpdf->Cell(45, 6, "Black Box" ,1,0,'C',true);   //Black Box
           $fpdf->Cell(75, 6, "Jumper" ,1,0,'C',true);      //Jumper

           $fpdf->Ln();

           // tbale # 3 header 2/2
                                                                                        // Umbagan
           $fpdf->Cell(40, 6, "Tiada Stay Insulator/Rosak" ,1,0,'C',true);                  //No Stay Insulator/Damaged
           $fpdf->Cell(45, 6, "Stay Plate Terbongkah" ,1,0,'C',true);          //Stay Plate / Base Stay Blocked
           $fpdf->Cell(20, 6, "Lain-lain" ,1,0,'C',true);                                   //Others
                                                                                        //IPC
           $fpdf->Cell(25, 6, "Kesan Bakar" ,1,0,'C',true);                                 //Burn Effect
           $fpdf->Cell(20, 6, "Lain-lain" ,1,0,'C',true);                                   //Others
                                                                                        // Black Box
           $fpdf->Cell(25, 6, "Kesan Bakar" ,1,0,'C',true);                                 //Kesan Bakar
           $fpdf->Cell(20, 6, "Lain-lain" ,1,0,'C',true);                                   //Others
                                                                                        // Jumper
           $fpdf->Cell(30, 6, "Tiada UV Sleeve" ,1,0,'C',true);                             //No UV Sleeve
           $fpdf->Cell(25, 6, "Kesan Bakar" ,1,0,'C',true);                                 //Burn Effect
           $fpdf->Cell(20, 6, "Lain-lain" ,1,0,'C',true);                                   //Others

           // tbale # 3 header 2/2 end


           $fpdf->Ln();
           $fpdf->SetFillColor(255, 255, 255);

           // tbale # 3 body values start

           $fpdf->Cell(40, 6, $row->umbang_defect_cracked ,1,0,'C',true);      // Umbagan Vlaues
           $fpdf->Cell(45, 6, $row->umbang_defect_stay_palte ,1,0,'C',true);
           $fpdf->Cell(20, 6, $row->umbang_defect_other_value ,1,0,'C',true);

           $fpdf->Cell(25, 6, $row->ipc_defect_burn ,1,0,'C',true);             //IPC values
           $fpdf->Cell(20, 6, $row->ipc_defect_other_value ,1,0,'C',true);

           $fpdf->Cell(25, 6, $row->blackbox_defect_cracked ,1,0,'C',true);   // Black Box values
           $fpdf->Cell(20, 6, $row->blackbox_defect_other_value ,1,0,'C',true);

           $fpdf->Cell(30, 6, $row->jumper_sleeve ,1,0,'C',true);        //Jumper values
           $fpdf->Cell(25, 6, $row->jumper_burn ,1,0,'C',true);
           $fpdf->Cell(20, 6, $row->jumper_other_value ,1,0,'C',true);

           // tbale # 3 body values start

           $fpdf->Ln();
           $fpdf->Ln();

           // table # 4 header 1/2

           $fpdf->SetFillColor(169, 169, 169);

           $fpdf->Cell(40, 6, "Penangkap Kilat" ,1,0,'C',true);                             //Lightning catcher
           $fpdf->Cell(95, 6, "Servis" ,1,0,'C',true);                                      //Service
           $fpdf->Cell(60, 6, "Pembumian" ,1,0,'C',true);                                   //Grounding
           $fpdf->Cell(75, 6, "Papan Tanda - OFF Point / Bekalan Dua Hala" ,1,0,'C',true);  //Signage - OFF Point / Two Way Supply

           $fpdf->Ln();

           // table # 4 header 2/2
                                                                                            //Lightning catcher
           $fpdf->Cell(20, 6, "Rosak" ,1,0,'C',true);                                           //Broken
           $fpdf->Cell(20, 6, "Lain-lain" ,1,0,'C',true);                                       //Others
                                                                                            //Service
           $fpdf->Cell(45, 6, "Talian servis atas bumbung" ,1,0,'C',true);            //The Service Line Is On The Roof
           $fpdf->Cell(30, 6, "Won piece Tanggal" ,1,0,'C',true);                               //Won Piece Date
           $fpdf->Cell(20, 6, "Lain-lain" ,1,0,'C',true);                                       //Others
                                                                                            //Grounding
           $fpdf->Cell(40, 6, "Tiada Sambungan ke Neutral" ,1,0,'C',true);                      //No Connection To Neutral
           $fpdf->Cell(20, 6, "Lain-lain" ,1,0,'C',true);                                       //Others
                                                                                            //Signage - OFF Point / Two Way Supply
           $fpdf->Cell(55, 6, "Papan Tanda Pudar / Rosak / Tiada" ,1,0,'C',true);               //Faded / Damaged / Missing Signage
           $fpdf->Cell(20, 6, "Lain-lain" ,1,0,'C',true);                                       //Others
           // table # 4 header 2/2 end



           $fpdf->Ln();

           // table # 4 body values start
           $fpdf->SetFillColor(255, 255, 255);

           $fpdf->Cell(20, 6, $row->kilat_defect_broken ,1,0,'C',true);   //Lightning catcher values
           $fpdf->Cell(20, 6, $row->kilat_defect_other_value ,1,0,'C',true);

           $fpdf->Cell(45, 6, $row->servis_defect_roof ,1,0,'C',true); //Service values
           $fpdf->Cell(30, 6, $row->servis_defect_won_piece ,1,0,'C',true);
           $fpdf->Cell(20, 6, $row->servis_defect_other_value ,1,0,'C',true);

           $fpdf->Cell(40, 6, $row->pembumian_defect_netural ,1,0,'C',true); //Grounding values
           $fpdf->Cell(20, 6, $row->pembumian_defect_other_value ,1,0,'C',true);

           $fpdf->Cell(55, 6, $row->bekalan_dua_defect_damage ,1,0,'C',true);   //Signage - OFF Point / Two Way Supply values
           $fpdf->Cell(20, 6, $row->bekalan_dua_defect_other_value ,1,0,'C',true);
            // table # 4 body values end




           $fpdf->Ln();
           $fpdf->Ln();

           // table # 5 header 1/2
           $fpdf->SetFillColor(169, 169, 169);

           $fpdf->Cell(95, 6, "Sesalur Kaki Lima" ,1,0,'C',true);         //Main Street
           $fpdf->Cell(95, 6, "Keadaan di Tapak" ,1,0,'C',true);          //Site Conditions
           $fpdf->Cell(80, 6, "Kawasan" ,1,0,'C',true);                   //Area




           $fpdf->Ln();
           // table # 5 header 1/2
                                                                                        // Main Street
           $fpdf->Cell(28, 6, "Wayar Tanggal" ,1,0,'C',true);                               // Date Wire
           $fpdf->Cell(49, 6, "Junction Box Tanggal / Kesan Bakar" ,1,0,'C',true);           //Junction Box Date / Burn Effect
           $fpdf->Cell(18, 6, "Lain-lain" ,1,0,'C',true);                                   //Others
                                                                                        //Site Conditions
           $fpdf->Cell(35, 6, "Melintasi Jalanraya" ,1,0,'C',true);                         //Crossing the Road
           $fpdf->Cell(20, 6, "Bahu Jalan" ,1,0,'C',true);                                  //Sidewalk
           $fpdf->Cell(40, 6, "Tidak Dimasuki Kenderaan" ,1,0,'C',true);                    //No vehicle entry area
                                                                                        // Area
           $fpdf->Cell(20, 6, "Bendang" ,1,0,'C',true);                                     //Bend
           $fpdf->Cell(20, 6, "Jalanraya" ,1,0,'C',true);                                   //Road
           $fpdf->Cell(20, 6, "Hutan" ,1,0,'C',true);                                       //Forest
           $fpdf->Cell(20, 6, "Lain-lain" ,1,0,'C',true);                                   //Others




           // table # 5 header 1/2 end
           $fpdf->Ln();


           // table # 5 body start
           $fpdf->SetFillColor(255, 255, 255);

           $fpdf->Cell(28, 6, $row->kaki_lima_defect_date_wire ,1,0,'C',true);                           // Main Street Values
           $fpdf->Cell(49, 6, $row->kaki_lima_defect_burn ,1,0,'C',true);
           $fpdf->Cell(18, 6, $row->kaki_lima_defect_other_value ,1,0,'C',true);

           $fpdf->Cell(35, 6, $row->tapak_condition_road ,1,0,'C',true);       //Site Conditions values
           $fpdf->Cell(20, 6, $row->tapak_condition_side_walk ,1,0,'C',true);
           $fpdf->Cell(40, 6, $row->tapak_condition_vehicle_entry ,1,0,'C',true);

           $fpdf->Cell(20, 6, $row->kawasan_bend ,1,0,'C',true);        // Area values
           $fpdf->Cell(20, 6, $row->kawasan_road ,1,0,'C',true);
           $fpdf->Cell(20, 6, $row->kawasan_forest ,1,0,'C',true);
           $fpdf->Cell(20, 6, $row->kawasan_other_value ,1,0,'C',true);

           // table # 5 body end






           $fpdf->Ln();
           $fpdf->Ln();








           $fpdf->SetFont('Arial', 'B', 6);
           $fpdf->SetFillColor(169, 169, 169);

           $fpdf->Cell(35, 6, 'Tiang Gambar 1' ,1,0,'C',true);
           $fpdf->Cell(35, 6, 'Tiang Gambar 2' ,1,0,'C',true);
           $fpdf->Cell(35, 6, 'Tiang Gambar 3' ,1,0,'C',true);
           $fpdf->Cell(35, 6, 'Tiang Gambar 4' ,1,0,'C',true);
           $fpdf->Cell(35, 6, 'Tiang Gambar 5' ,1,0,'C',true);
           $fpdf->Cell(35, 6, 'Gambar Kebocoran Arus' ,1,0,'C',true);
           $fpdf->Cell(35, 6, 'Gambar Pembersihan Creepers' ,1,0,'C',true);
           $fpdf->Cell(35, 6, 'Gambar Pembersihan Banners' ,1,0,'C',true);


           $fpdf->Ln();


           foreach($images as $img){

                $imageLocalPath = config('globals.APP_IMAGES_LOCALE_PATH').$row->{$img};
                if ($row->{$img} != '' && file_exists($imageLocalPath))
                {
                    $fpdf->Cell(1,6);
                    $fpdf->Image($imageLocalPath, $fpdf->GetX(), $fpdf->GetY(), 33, 33);
                    $fpdf->Cell(33,6);
                }

           }





        }

        $pdfFileName = Auth::user()->ba.' - Tiang - '.$req->visit_date.'.pdf';
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFileName . '"');
        $folderPath = $req->folder_name .'/'. $pdfFileName;
        $pdfFilePath =  $folderPath;
        $fpdf->output('F', $pdfFilePath);

        return response()->json(['pdfPath' => $pdfFileName]);
    }

    public function gene(Fpdf $fpdf, Request $req)
    {
        if ($req->ajax())
        {
            // return $req;

            $result = Tiang::query();

            $result = $this->filter($result , 'review_date',$req)->where('qa_status','Accept');

            if ($req->filled('workPackages'))
            {
                // Fetch the geometry of the work package
                $workPackageGeom = WorkPackage::where('id', $req->workPackages)->value('geom');

                // Execute the query
                $result = $result
                    ->join('tbl_savr_geom as g', 'tbl_savr.geom_id', '=', 'g.id')
                    ->whereRaw('ST_Within(g.geom, ?)', [$workPackageGeom]);

            }
            $getResultByVisitDate= $result->select('tbl_savr.review_date as visit_date',DB::raw("count(*)"))->groupBy('tbl_savr.review_date')->get();  //get total count against visit_date


            $fpdf->AddPage('L', 'A4');
            $fpdf->SetFont('Arial', 'B', 22);
                //add Heading
                $fpdf->Cell(180, 15, strtoupper(Auth::user()->ba) .' LKS LV',0,1);
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

            $pdfFileName = Auth::user()->ba.' - Tiang - Table - Of - Contents - '.$req->from_date.' - '.$req->from_date.'.pdf';

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $pdfFileName . '"');
            $userID = Auth::user()->id;
            // $folderName = 'temporary-tiang-folder-'.$userID;
            // $folderPath = public_path('temp/'.$folderName);
            $folderName = 'D:/temp/temporary-tiang-folder-'.$userID;
            //$folderPath = public_path('temp/'.$folderName);
            $folderPath= $folderName;

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
            $req['from_date'] = Tiang::min('review_date');
        }

        if (empty($req->to_date)) {
            $req['to_date'] = Tiang::max('review_date');
        }


        // return $req;
        return view('Documents.download-lks', [
            'ba'=>$req->ba,
            'from_date'=>$req->from_date,
            'cycle'=>$req->cycle,
            'to_date'=>$req->to_date,
            'url'=>'tiang-talian-vt-and-vr',
            'workPackage' =>$req->workPackages
        ]);

    }
}



