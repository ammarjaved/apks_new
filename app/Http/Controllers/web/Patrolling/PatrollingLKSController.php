<?php

namespace App\Http\Controllers\web\Patrolling;

use App\Http\Controllers\Controller;
use App\Models\Patroling;
use App\Traits\Filter;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

use function PHPUnit\Framework\returnSelf;

class PatrollingLKSController extends Controller
{
    //
    use Filter;
    public function index(){
        $button =[];
        $button=[ 
            ['url'=>'generate-patrolling-lks' , 'name'=>'Generate LKS'],
        ];
        return view('lks.generate-lks',['title'=>'patrolling','buttons'=>$button]);
    }

    public function genet(Request $req)
    {

        $from_date = empty($req->from_date)?  date('Y-m-d',   strtotime(Patroling::min('vist_date'))) : $req->from_date;
        $to_date = empty($req->to_date)?  date('Y-m-d',   strtotime(Patroling::max('vist_date'))) : $req->to_date;



        $result = Patroling::query();
        $result = $this->filter($result, 'vist_date', $req)->whereNotNull('km')->where('km', '!=', '0');

        $datas = $result->with(['firstPatrollingLines' => function ($query) {
                $query->select(
                    'patroling_id',
                    DB::raw('st_x(st_centroid(ST_Envelope(geom))) as x'),
                    DB::raw('st_y(st_centroid(ST_Envelope(geom))) as y'
                    )
                );
            }])
            ->select(
                'id',
                'time',
                'vist_date',
                'wp_name',
                'cycle',
                'reading_end',
                'reading_start',
                'image_reading_start',
                'image_reading_end'
            )
            ->whereNotNull('km')
            ->where('km', '!=', '0')
            ->get();

        // return $datas;


        //   $datas = $result->select('id','time' ,'vist_date' , 'wp_name','cycle','reading_end','reading_start','image_reading_start','image_reading_end' ,DB::raw('st_x(st_centroid(ST_Envelope(geom))) as x'),DB::raw('st_y(st_centroid(ST_Envelope(geom))) as y'))->get();
        //   return view('patrolling.pdf-template', ['data'=>$datas,'ba'=>$req->ba , 'from_date' =>$from_date , 'to_date'=>$to_date]);
        $html = View::make('patrolling.pdf-template', ['data'=>$datas,'ba'=>$req->ba , 'from_date' =>$from_date , 'to_date'=>$to_date])->render();

        $pdf = SnappyPdf::loadHTML($html);
        $pdf->setOption('javascript-delay', 5000);
        // $pdf->setOption('no-images', false);


        return $pdf->download($req->ba.'-Patroling-'.$req->from_date.' - '.$req->to_date.'.pdf');



        $htmlContent = "<!DOCTYPE html>
        <html>
        <head>
            <!-- Include Bootstrap CSS -->
            <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
            <link rel='stylesheet' href='https://unpkg.com/leaflet/dist/leaflet.css' />
            <script src='https://unpkg.com/leaflet/dist/leaflet.js'></script>

        </head>
        </head>
        <body>
            <div class='container-'>
                <h1 class='text-center'>etc ba LKS</h1>";

                foreach($datas as $data){
                $htmlContent.="



                <table class='table table-bordered'>
                    <thead>
                        <th>ID</th>
                        <th>WP NAME</th>
                        <th>VISIT DATE</th>
                        <th>TIME</th>
                        <th>READING START</th>
                        <th>READING END</th>
                        <th>CYCLE</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>$data->id</td>
                            <td>$data->wp_name</td>
                            <td> ". date('Y-m-d', strtotime($data->visit_date)) ."</td>
                            <td> ". date('H:i:s', strtotime($data->time)) ."</td>
                            <td>$data->reading_start</td>
                            <td>$data->reading_end </td>
                            <td>$data->cycle</td>
                        </tr>
                    </tbody>
                </table>

                <div class='container p-5 ms-auto'>
                    <div id='map-$data->id' class='map' style='height: 300px;width:800px; marign :20px ;'></div>
                </div>



            <script>
            var map = L.map('map-$data->id').setView([3.016603, 101.858382], 5);
                document.getElementById('map-$data->id').style.cursor = 'pointer'
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

            </script>


        " ;
                }
                $htmlContent .= "
        </body>
        </html>
";
// File::put(public_path('assets/html/testing ujsf.html'), $htmlContent);

// if (file_exists(public_path('assets/html/testing ujsf.html'))) {


//     # code...
// }
// return "sad";


        // $pdf = app(PDF::class);
        // $pdf->loadHTML(View::make('example', ['data'=>$datas]));

    }



}
