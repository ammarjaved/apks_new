<?php

namespace App\Http\Controllers\web\FeederPillar;

use App\Http\Controllers\Controller;
use App\Models\FeederPillar;
use App\Traits\Filter;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use DateTime;
use Illuminate\Support\Facades\View;

use Barryvdh\Snappy\Facades\SnappyPdf;


class OPSFeederPillarController extends Controller
{
    //
    use Filter;

    public function generateOPS(Fpdf $fpdf,Request $req) {

        $query = $this->filter(FeederPillar::query(), 'visit_date',$req)->where('qa_status','Accept')->where('total_defects','!=',0);
        $data = $query->orderBy('visit_date','desc')->get();
        //   return view('feeder-pillar.OPS-pdf-template', ['data'=>$data]);
        $html = View::make('feeder-pillar.OPS-pdf-template', ['data'=>$data ])->render();

        $pdf = SnappyPdf::loadHTML($html);
        return $pdf->download($req->ba.'-Patroling-'.$req->from_date.' - '.$req->to_date.'.pdf');

        // $pdf->setOption('javascript-delay', 1000);

        // Assuming you have an instance of FPDF named $pdf
 
// $fpdf->AddPage();

// // Set font
// $fpdf->SetFont('Arial', '', 12);

// // Cell with multiple lines
// $text1 = "This is the first line.\n";
// $text1 .= "This is the second line.\n";
// $text1 .= "This is the third line.";

// // MultiCell for the first set of text
// $fpdf->MultiCell(0, 10, $text1);

// // Set the position for the second MultiCell
// $fpdf->SetXY(60, 10); // Adjust the X and Y coordinates as needed

// // Another set of text
// $text2 = "This is another line.\n";
// $text2 .= "This is yet another line.\n";
// $text2 .= "This is the last line.";

// // MultiCell for the second set of text
// $fpdf->MultiCell(0, 10, $text2);

// // Output the PDF
// $fpdf->output('D');
// return ;


        $fpDefects = [
            ['title' => 'Vandlism', 'name' => 'vandalism_status', 'image' => ['image_vandalism', 'image_vandalism_2']],
            ['title' => 'Leaning', 'name' => 'leaning_staus', 'image' => ['image_leaning', 'image_leaning_2']],
            ['title' => 'Rusty', 'name' => 'rust_status', 'image' => ['image_rust', 'image_rust_2']],
            ['title' => 'FP Guard', 'name' => 'guard_status', 'image' => []],
            ['title' => 'Paint', 'name' => 'paint_status', 'image' => []],
            ['title' => 'Iklan Haram/Banner', 'name' => 'advertise_poster_status', 'image' => ['images_advertise_poster', 'images_advertise_poster_2']],
            // ['title' => 'Gate', 'name' => 'gate_status', 'image' => ['image_gate', 'image_gate_2'], 'keys' => ['unlocked', 'demaged', 'other']],
        ];
        
       
        


        // $fpdf->AddPage('L', 'A4');

        // $imagePath = public_path('assets/web-images/main-logo.png');
        // $fpdf->Image($imagePath, 190, 20, 57, 0);
        $fpdf->SetFont('Arial', 'B', 16);
        $sr_no= 0;
        $preVIsitDate = '';
        $x = 0;
        $y = 0;
        foreach ($data as $row) {
            
            if ( $preVIsitDate != $row->visit_date ) {
                $fpdf->AddPage('L', 'A4');
                // return $fpdf->GetX();
                $x = 0;  $y = 0;
                $date = new DateTime($row->visit_date);

                // Format the date as "d F Y" (day, month, year)
                $formattedDate = $date->format("d F Y");
                $fpdf->SetFont('Arial', 'B', 25);

                $fpdf->Cell(270, 10, 'RONDAAN  '.$formattedDate ,0 ,1,'C');
                $fpdf->SetFont('Arial', 'B', 16);

            }
            // return $fpdf->GetPageHeight();
            $preVIsitDate = $row->visit_date;

           

            foreach ($fpDefects as $defect) {
                
                if ($row->{$defect['name']} == true) {
                    if ($x == 0 ) {
                        $y= $fpdf->GetY();
                        $fpdf->SetXY(4, $y);
                        $x = 135;
                    }elseif($x == 135 ){
                        $y = $fpdf->GetY();
                        $fpdf->SetXY(141, $y -70);
                        $x == 0;
                    }
                    $text = "FP JALAN JENJARUM\n";
                    $text .= $row->coords . "\n";
                    $text .= 'KEJANGGALAN : ' . $defect['title']; // Assuming you want to use the 'title' key instead of 'name'

                    $fpdf->MultiCell(135, 10, $text ,1);
                    $imgX = 0;
                    foreach ($defect['image'] as $img) {
                         
                        
                        if ($x == 0) {
                            $imgX = 143;
                        }elseif($imgX != 143 || $imgX != 4){
                            $imgX = $imgX + 30;
                        }else{
                            $imgX = 4;
                        }
                        if ($row->{$img} != '' && file_exists(public_path($row->{$img})))
                        {
                            $fpdf->Image(public_path($row->{$img}), $imgX, $fpdf->GetY(), 30, 30);
                        }
                    }
                    
                        $fpdf->Ln();
                        $fpdf->Ln();
                        $fpdf->Ln();
                        $fpdf->Ln();
                     
                     

                }
            }
            
            
            // return;
            // $sr_no++;

            // // add feeder pilar images  Header
            // $fpdf->Cell(45, 6, 'FEEDER PILLAR Gambar 1' ,0);
            // $fpdf->Cell(40, 6, 'FEEDER PILLAR Gambar 2' ,0);
            // $fpdf->Cell(40, 6, 'FP Plate' ,0);

            // $fpdf->Ln();

            // $fpdf->Cell(125, 6, 'ID : FP-' . $row->id);

            // // add feeder pillar images
            // if ($row->feeder_pillar_image_1 != '' && file_exists(public_path($row->feeder_pillar_image_1)))
            // {

            //     $fpdf->Image(public_path($row->feeder_pillar_image_1), $fpdf->GetX(), $fpdf->GetY(), 20, 20);
            // }
            // $fpdf->Cell(45,6);
            // $fpdf->Ln();

    }
    $fpdf->output('D');
}
}
