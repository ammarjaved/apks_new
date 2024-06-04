<?php

namespace App\Http\Controllers\web\FeederPillar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class FPpdfFromHtmlController extends Controller
{
    public function generatePDF()
{
    // HTML content to be converted to PDF
    $result = FeederPillar::where('ba','BANGI')->where('visit_date', $req->visit_date)->where('qa_status','Accept');

    $data = $result->select('id','guard_status','image_advertisement_after_1','paint_status','image_name_plate', 'ba','feeder_pillar_image_1','feeder_pillar_image_2', DB::raw("CASE WHEN (gate_status->>'unlocked')::text='true' THEN 'Ya' ELSE 'Tidak' END as unlocked"), DB::raw("CASE WHEN (gate_status->>'demaged')::text='true' THEN 'Ya' ELSE 'Tidak' END as demaged"), DB::raw("CASE WHEN (gate_status->>'other')::text='true' THEN 'Ya' ELSE 'Tidak' END as other_gate"),DB::raw("CASE WHEN (gate_status->>'other')::text='true' THEN (gate_status->>'other_value')::text ELSE '' END as gate_other_value") , 'vandalism_status', 'leaning_staus', 'rust_status', 'advertise_poster_status', 'visit_date', 'size', 'coordinate', 'image_gate', 'image_gate_2', 'total_defects', 'image_vandalism', 'image_vandalism_2', 'image_leaning', 'image_leaning_2', 'image_rust', 'image_rust_2', 'images_advertise_poster', 'images_advertise_poster_2'  , DB::raw('ST_X(geom) as X'), DB::raw('ST_Y(geom) as Y'))->get();




    $html = '
        <html>
        <head>
            <title>PDF with Bootstrap</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <h1>RONDAAN 22 FEBRUARY 2024</h1>
            <p class="lead">This is a paragraph with Bootstrap styling.</p>
            <button class="btn btn-primary">Bootstrap Button</button>
        </body>
        </html>
    ';

    // Configure Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);

    // Instantiate Dompdf with options
    $dompdf = new Dompdf($options);

    // Load HTML content
    $dompdf->loadHtml($html);

    // (Optional) Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF (inline or download)
    return $dompdf->stream('document.pdf');
}

}
