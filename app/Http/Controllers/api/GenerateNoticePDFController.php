<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class GenerateNoticePDFController extends Controller
{
    //
    function generateHtml()
    {

        $html = View::make('PDF.notice')->render();

    // Generate PDF from HTML
    $pdf = PDF::loadHTML($html);

    // Save or download the PDF
    // Example 1: Save PDF to a file
    $pdf->save(public_path('pdfs/output.pdf'));

    // Example 2: Download the PDF
    return $pdf->download('output.pdf');
        $test = public_path('assets/PurhaseOrderPDF/html/');
        return $test;
        $htmlContent = '
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    

    <style>
    th {
        font-weight: 500;
    }

    td {
        padding-left: 10px
    }
    hr{

border-style: dotted;
border-width: 1px;
    }
</style>
</head>




<body>

<div class="container   my-5 bg-white ">
          
<section>
    
    <div class="text-end"> No. Siri: SWO 001</div>
    <h6 class="text-center">NOTIS MEMBERHENTIKAN AKTIVITI BAHAYA BERHAMPIRAN PEPASANGAN ELEKTRIK TNB</h6>
    <div class="">
        <div class="row">
            <div class="col-2">Kepada</div>
            <div class="col-4">: …………………………………………</div>
            <div class="col-3"></div>
            <div class="col-3">Tarikh : ............</div>
        </div>

        <div class="row ">
            <div class="col-2"></div>
            <div class="col-4"> ..............................</div>
            <div class="col-3"></div>
            <div class="col-3"> </div>
        </div>
        <div class="row ">
            <div class="col-2"></div>
            <div class="col-4"> ..............................</div>
            <div class="col-3"></div>
            <div class="col-3">Notis : Pertama / Kedua / Ketiga </div>
        </div>

        <div class="row">
            <div class="col-2"></div>
            <div class="col-5"> (Poskod ………………………………..)</div>
            <div class="col-2"></div>
            <div class="col-3"></div>
        </div>

        <div class="row">
            <div class="col-2">Tel</div>
            <div class="col-4">: …………………………………………</div>
            <div class="col-3"></div>
            <div class="col-3"> </div>
        </div>

        <div class="row ">
            <div class="col-2">Fax</div>
            <div class="col-4"> : ……………………………………</div>
            <div class="col-3"></div>
            <div class="col-3"> </div>
        </div>
        <div class="row ">
            <div class="col-4">Nama Pencawang Eletrik</div>
            <div class="col-4">
                <hr>
            </div>

        </div>

        <div class="row  ">
            <div class="col-4">Lokasi</div>
            <div class="col-4">
                <hr>
            </div>

        </div>

        <div class="">Sila ambil maklum bahawa pihak tuan didapati telah menjalankan aktiviti
            berbahaya berhampiran pepasangan elektrik TNB seperti
            berikut:
        </div>

        <div class="row py-4">
            <div class="col-6 bordered" style="border:1px solid black">
                <ul>
                    <li>Meletakkan/Menggunakan/Kenderaan /Jentera Berat</li>
                    <li>Melonggok/Mengorek/Memotong/Kerja Tanah</li>
                    <li>Kerja Taman Kurang 30m dari Menara</li>
                    <li>Membuang Sampah/Sisa Binaan/Bahan</li>
                    <li>Toksid/Melakukan Pembakaran</li>
                    <li>Membina Struktur & Menghuni Penempatan</li>
                </ul>
            </div>
            <div class="col-6 bordered" style="border:1px solid black">
                <ul>
                    <li>Membina/Mengguna Laluan Pepasangan TNB</li>
                    <li>Perniagaan/Bengkel/Simpan Barang</li>
                    <li>Mengusahakan Kebun/Nursery/Lanskap</li>
                    <li>Tumbuhan Melebihi 1.83m (6kaki)</li>
                    <li>Meletakkan barang-barang dalam/sekitar 3m
                        Kawasan kaki Menara</li>

                </ul>
            </div>
        </div>


        <ul>
            <li class="d-flex ">Lain-lain aktiviti :
                <hr class="w-75">
            </li>
        </ul>
        <div class="py-4">
            Sila ambil perhatian bahawa pihak tuan adalah tidak dibenarkan dan dilarang untuk menjalankan
            aktiviti yang dinyatakan di atas
            kerana ia boleh menyebabkan bahaya dan/atau kerosakan pada pepasangan elektrik TNB. Sila ambil
            maklum juga bahawa sebarang
            aktiviti berhampiran dengan apa-apa pepasangan elektrik TNB tanpa kebenaran sah daripada pihak
            berkuasa bekalan atau TNB
            adalah merupakan kesalahan di bawah Seksyen 37(12) Akta Bekalan Elektrik 1990 yang jika
            disabikan kesalahan, boleh dikenakan
            hukuman denda tidak melebihi lima puluh ribu ringgit (RM50,000.00) atau penjara tidak melebihi
            dua (2) tahun atau kedua-duanya
            sekali
        </div>
        <div class="py-4">
            Berdasarkan Seksyen 37(11) Akta Bekalan Elektrik 1990 pula, mana-mana orang yang didapati
            merosakkan apa-apa pepasangan
            elektrik TNB atau mana-mana bahagiannya adalah melakukan kesalahan dan boleh dikenakan hukuman
            denda tidak melebihi lima
            puluh ribu ringgit (RM50,000.00) atau penjara tidak melebihi tiga(3) tahun atau kedua-duanya
            jika disabitkan kesalahan.
        </div>
        <div class="py-4">SILA AMBIL PERHATIAN pihak tuan dengan ini diminta dengan serta-merta
            menghentikan segala aktiviti berhampiran
            pepasangan TNB tersebut yang mana kegagalan berbuat demikian akan mengakibatkan tindakan
            undang-undang diambil terhadap
            pihak tuan tanpa sebarang notis lagi. Sekiranya aktiviti yang dijalankan tersebut menyebabkan
            kerosakan pada pepasangan TNB
            atau mana-mana bahagian pepasangan TNB, pihak tuan juga akan bertanggugan terhadap apa-apa
            tuntutan gantirugi termasuk kos
            guaman melibatkan sebarang kerosakan terhadap pepasangan elektrik TNB termasuk tuntutan
            gantirugi daripada pihak ketiga
            dan/atau pengguna yang terjejas akibat gangguan bekalan elektrik</div>

        <div class="">
            Sila berhubung dengan pegawai TNB ………………………………………………di talian …………………………. Bagi
            mendapatkan kebenaran sah TNB sekiranya pihak tuan ingin meneruskan aktiviti tersebut.
        </div>
        <h6 class="text-center pt-3">“TNB – PENGGERAK KEMAJUAN NEGARA”</h6>

        <div class="row">
            <div class="col-4">Arahan Oleh: <br>
                KETUA JURUTERA</div>
            <div class="col-4">Notis Disampaikan Oleh:</div>
            <div class="col-4">Diterima Oleh:
            </div>
        </div>

        <div class="row">
            <div class="col-4">Unit Operasi dan Senggaraan
               </div>
            <div class="col-4"><hr></div>
            <div class="col-4"><hr>
            </div>
        </div>


        <div class="row">
            <div class="col-4">Zon Selangor & Putrajaya/Cyberjaya
                </div>
            <div class="col-4"> </div>
            <div class="col-4">
            </div>
        </div>


        <div class="row">
            <div class="col-4">Bahagian Pembahagian</div>
            <div class="col-4 d-flex">No. I/C: <hr></div>

            <div class="col-4 d-flex">No. I/C: <hr></div>
        </div>



        <div class="row">
            <div class="col-4">Tenaga Nasional Berhad</div>
            <div class="col-4 d-flex">   </div>

            <div class="col-4 d-flex">  </div>
        </div>


        <div class="row">
            <div class="col-4">s.k. Suruhanjaya Tenaga</div>
            <div class="col-4 d-flex">TandaTangan: <hr></div>

            <div class="col-4 d-flex">TandaTangan: <hr></div>
        </div>
    </div>
</section>


</div>

</div>
    </body>
    </html>
        ';
        $bytesWritten = File::put(public_path('assets/NoticePdf/html/notice.html'), $htmlContent);
    }

    public function generatePdf()
    {
        $htmlcontent = '
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    

    <style>
    th {
        font-weight: 500;
    }

    td {
        padding-left: 10px
    }
    hr{

border-style: dotted;
border-width: 1px;
    }
</style>
</head>




<body>

<div class="container shadow  my-5 bg-white ">
          
<section>
    
    <div class="text-end"> No. Siri: SWO 001</div>
    <h6 class="text-center">NOTIS MEMBERHENTIKAN AKTIVITI BAHAYA BERHAMPIRAN PEPASANGAN ELEKTRIK TNB</h6>
    <div class="">
        <div style = "display:flex">
            <div >Kepada</div>
            <div >: …………………………………………</div>
            <div ></div>
            <div >Kepada : ............</div>
        </div>

        <div class="row ">
            <div class="col-2"></div>
            <div class="col-4"> ..............................</div>
            <div class="col-3"></div>
            <div class="col-3"> </div>
        </div>
        <div class="row ">
            <div class="col-2"></div>
            <div class="col-4"> ..............................</div>
            <div class="col-3"></div>
            <div class="col-3">Notis : Pertama / Kedua / Ketiga </div>
        </div>

        <div class="row">
            <div class="col-2"></div>
            <div class="col-5"> (Poskod ………………………………..)</div>
            <div class="col-2"></div>
            <div class="col-3"></div>
        </div>

        <div class="row">
            <div class="col-2">Tel</div>
            <div class="col-4">: …………………………………………</div>
            <div class="col-3"></div>
            <div class="col-3"> </div>
        </div>

        <div class="row ">
            <div class="col-2">Fax</div>
            <div class="col-4"> : ……………………………………</div>
            <div class="col-3"></div>
            <div class="col-3"> </div>
        </div>
        <div class="row ">
            <div class="col-4">Nama Pencawang Eletrik</div>
            <div class="col-4">
                <hr>
            </div>

        </div>

        <div class="row  ">
            <div class="col-4">Lokasi</div>
            <div class="col-4">
                <hr>
            </div>

        </div>

        <div class="">Sila ambil maklum bahawa pihak tuan didapati telah menjalankan aktiviti
            berbahaya berhampiran pepasangan elektrik TNB seperti
            berikut:
        </div>

        <div class="row py-4">
            <div class="col-6 bordered" style="border:1px solid black">
                <ul>
                    <li>Meletakkan/Menggunakan/Kenderaan /Jentera Berat</li>
                    <li>Melonggok/Mengorek/Memotong/Kerja Tanah</li>
                    <li>Kerja Taman Kurang 30m dari Menara</li>
                    <li>Membuang Sampah/Sisa Binaan/Bahan</li>
                    <li>Toksid/Melakukan Pembakaran</li>
                    <li>Membina Struktur & Menghuni Penempatan</li>
                </ul>
            </div>
            <div class="col-6 bordered" style="border:1px solid black">
                <ul>
                    <li>Membina/Mengguna Laluan Pepasangan TNB</li>
                    <li>Perniagaan/Bengkel/Simpan Barang</li>
                    <li>Mengusahakan Kebun/Nursery/Lanskap</li>
                    <li>Tumbuhan Melebihi 1.83m (6kaki)</li>
                    <li>Meletakkan barang-barang dalam/sekitar 3m
                        Kawasan kaki Menara</li>

                </ul>
            </div>
        </div>


        <ul>
            <li class="d-flex ">Lain-lain aktiviti :
                <hr class="w-75">
            </li>
        </ul>
        <div class="py-4">
            Sila ambil perhatian bahawa pihak tuan adalah tidak dibenarkan dan dilarang untuk menjalankan
            aktiviti yang dinyatakan di atas
            kerana ia boleh menyebabkan bahaya dan/atau kerosakan pada pepasangan elektrik TNB. Sila ambil
            maklum juga bahawa sebarang
            aktiviti berhampiran dengan apa-apa pepasangan elektrik TNB tanpa kebenaran sah daripada pihak
            berkuasa bekalan atau TNB
            adalah merupakan kesalahan di bawah Seksyen 37(12) Akta Bekalan Elektrik 1990 yang jika
            disabikan kesalahan, boleh dikenakan
            hukuman denda tidak melebihi lima puluh ribu ringgit (RM50,000.00) atau penjara tidak melebihi
            dua (2) tahun atau kedua-duanya
            sekali
        </div>
        <div class="py-4">
            Berdasarkan Seksyen 37(11) Akta Bekalan Elektrik 1990 pula, mana-mana orang yang didapati
            merosakkan apa-apa pepasangan
            elektrik TNB atau mana-mana bahagiannya adalah melakukan kesalahan dan boleh dikenakan hukuman
            denda tidak melebihi lima
            puluh ribu ringgit (RM50,000.00) atau penjara tidak melebihi tiga(3) tahun atau kedua-duanya
            jika disabitkan kesalahan.
        </div>
        <div class="py-4">SILA AMBIL PERHATIAN pihak tuan dengan ini diminta dengan serta-merta
            menghentikan segala aktiviti berhampiran
            pepasangan TNB tersebut yang mana kegagalan berbuat demikian akan mengakibatkan tindakan
            undang-undang diambil terhadap
            pihak tuan tanpa sebarang notis lagi. Sekiranya aktiviti yang dijalankan tersebut menyebabkan
            kerosakan pada pepasangan TNB
            atau mana-mana bahagian pepasangan TNB, pihak tuan juga akan bertanggugan terhadap apa-apa
            tuntutan gantirugi termasuk kos
            guaman melibatkan sebarang kerosakan terhadap pepasangan elektrik TNB termasuk tuntutan
            gantirugi daripada pihak ketiga
            dan/atau pengguna yang terjejas akibat gangguan bekalan elektrik</div>

        <div class="">
            Sila berhubung dengan pegawai TNB ………………………………………………di talian …………………………. Bagi
            mendapatkan kebenaran sah TNB sekiranya pihak tuan ingin meneruskan aktiviti tersebut.
        </div>
        <h6 class="text-center pt-3">“TNB – PENGGERAK KEMAJUAN NEGARA”</h6>

        <div class="row">
            <div class="col-4">Arahan Oleh: <br>
                KETUA JURUTERA</div>
            <div class="col-4">Notis Disampaikan Oleh:</div>
            <div class="col-4">Diterima Oleh:
            </div>
        </div>

        <div class="row">
            <div class="col-4">Unit Operasi dan Senggaraan
               </div>
            <div class="col-4"><hr></div>
            <div class="col-4"><hr>
            </div>
        </div>


        <div class="row">
            <div class="col-4">Zon Selangor & Putrajaya/Cyberjaya
                </div>
            <div class="col-4"> </div>
            <div class="col-4">
            </div>
        </div>


        <div class="row">
            <div class="col-4">Bahagian Pembahagian</div>
            <div class="col-4 d-flex">No. I/C: <hr></div>

            <div class="col-4 d-flex">No. I/C: <hr></div>
        </div>



        <div class="row">
            <div class="col-4">Tenaga Nasional Berhad</div>
            <div class="col-4 d-flex">   </div>

            <div class="col-4 d-flex">  </div>
        </div>


        <div class="row">
            <div class="col-4">s.k. Suruhanjaya Tenaga</div>
            <div class="col-4 d-flex">TandaTangan: <hr></div>

            <div class="col-4 d-flex">TandaTangan: <hr></div>
        </div>
    </div>
</section>


</div>

</div>
    </body>
    </html>
        ';
        PDF::loadHTML($htmlcontent)
            ->setPaper('a4')
            ->setOrientation('landscape')
            ->setOption('margin-bottom', 0)
            ->save('myfile.pdf');

        $pdf = App::make('snappy.pdf.wrapper');

        $pdf->loadHTML($htmlcontent);
        return $pdf->inline();

        $html = View::make('welcome')->render();

        // Generate PDF from HTML
        $pdf = PDF::loadHTML($html);

        // Get the temporary HTML file path
        $tempHtmlPath = tempnam(sys_get_temp_dir(), 'knp_snappy');

        // Save the HTML to the temporary file
        file_put_contents($tempHtmlPath, $html);

        // Get the public path for the output PDF file
        $outputPdfPath = public_path('pdfs/output.pdf');

        // Generate the PDF using the temporary HTML file
        $pdf->generate($tempHtmlPath, $outputPdfPath);

        // Clean up the temporary HTML file
        unlink($tempHtmlPath);

        // Return the PDF as a download
        return response()->download($outputPdfPath, 'output.pdf');
    }
}
