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
    <link rel="shortcut icon" href="{{ asset('assets/web-images/main-logo.ico') }}">
    <style>
        @media print {
            #print-button {
                display: none;
            }

            header,
            footer {
                display: none;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                /*Chrome, Safari */
                color-adjust: exact !important;
                /*Firefox*/
            }

            .container {
                box-shadow: none !important;
                padding: 0% !important;
                margin: 0px !important;
                max-width: 99% !important;
                width: 100% !important;
            }

            section {
                page-break-before: always;

            }
        }

        th {
            font-weight: 500;
        }

        td {
            padding-left: 10px
        }

        hr {

            border-style: dotted;
            border-width: 1px;
        }
    </style>
</head>


<body>

    <div class="">

        <div class="container shadow p-4   my-5 bg-white ">
            <section class="pb-5 mb-5">
                <img src="{{ URL::asset('assets/web-images/pdfimg.png') }}" alt="">

                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-secondary" id="print-button"
                        onclick="exportToPDF()">Export to
                        PDF</button>
                </div>
                <h5>BORANG RONDAAN / LAWATAN TAPAK KERJA KOREKAN DI LALUAN KABEL TENAGA NASIONAL BERHAD</h5>

                <table class="table-bordered w-100 caption-top">
                    <tr>
                        <th class="col-6">Tarikh Rondaan / Lawatan Tapak</th>
                        <td class="col-6">{{ $data->survey_date }}</td>
                    </tr>

                    <tr>
                        <th>Masa Rondaan / Lawatan Tapak</th>
                        <td>{{ $data->patrolling_time }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Nama Projek</th>
                        <td>{{ $data->project_name }}</td>
                    </tr>
                    <tr>
                        <th>Feeder Terlibat / Bil. Litar</th>
                        <td>{{ $data->feeder_involved }}</td>
                    </tr>
                </table>

                <table class="table-bordered w-100 caption-top">
                    <caption>MAKLUMAT PEMAJU / KONTRAKTOR UTAMA</caption>
                    <tr>
                        <th class="col-6">Nama Syarikat</th>
                        <td>{{ $data->company_name }}</td>
                    </tr>
                    <tr>
                        <th>No Telefon Pejabat</th>
                        <td>{{ $data->office_phone_no }}</td>
                    </tr>
                    <tr>
                        <th>Wakil Pemaju / Kontraktor Utama</th>
                        <td>{{ $data->main_contractor }}</td>
                    </tr>
                    <tr>
                        <th>No Telefon Wakil Pemaju / Kontraktor Utama</th>
                        <td>{{ $data->developer_phone_no }}</td>
                    </tr>

                </table>

                <table class="table-bordered w-100 caption-top">
                    <caption>MAKLUMAT KONTRAKTOR</caption>
                    <tr>
                        <th>Nama Syarikat Kontraktor</th>
                        <td>{{ $data->contractor_company_name }}</td>
                    </tr>
                    <tr>
                        <th>Nama Penyelia Tapak</th>
                        <td>{{ $data->site_supervisor_name }}</td>
                    </tr>
                    <tr>
                        <th class="col-6">No Telefon Penyelia Tapak</th>
                        <td>{{ $data->site_supervisor_phone_no }}</td>
                    </tr>
                </table>
                <table class="table-bordered w-100 caption-top">
                    <caption class="caption-top">MAKLUMAT JENTERA PROJEK</caption>
                    <tr>
                        <th class="col-6">Nama Pengendali Jentera Pengorek</th>
                        <td>{{ $data->excavator_operator_name }}</td>
                    </tr>
                    <tr>
                        <th>No Pendaftaran Jentera Pengorek</th>
                        <td>{{ $data->excavator_machinery_reg_no }}</td>
                    </tr>
                </table>

                <p><strong>PERAKUAN</strong></p>
                <ol class="mb-5 pb-3">
                    <li> Saya dengan ini telah dimaklumkan dan faham bahawa terdapat / mungkin kabel TNB di tapak kerja
                        korekan sedang dilaksanakan.</li>
                    <li> Saya berjanji akan berhati-hati dan akan mengambil langkah yang sewajarnya bagi mengelakkan
                        kerosakan
                        di mana kerja berhampiran kabel TNB.</li>
                    <li> Saya juga faham akan bertanggungjawab sepenuhnya ke atas sebarang kerosakan kabel termasuk kos
                        pembaikan akibat kerja korekan yang diselia oleh saya.</li>
                    <li> Saya juga telah menerima Notis Pemberitahuan daripada TNB.</li>
                </ol>

                <table class="w-100 ">
                    <tr>
                        <td class="col-4">Wakil Pemaju / Kontraktor Utama</td>
                        <td class="col-4 text-center">Wakil Kontraktor</td>
                        <td class="col-4 text-center">Wakil TNB</td>
                    </tr>
                    <tr>
                        <td class="col-4">NAMA:</td>
                        <td>NAMA:</td>
                        <td>NAMA:</td>
                    </tr>
                    <tr class="col-4">
                        <td>NO IC:</td>
                        <td>NO IC:</td>
                        <td>NO IC:</td>
                    </tr>
                    <tr>
                        <td>NO H/P:</td>
                        <td>NO H/P:</td>
                        <td>NO H/P:</td>
                    </tr>

                </table>
            </section>
            <hr>
            <section>
                <img src="{{ URL::asset('assets/web-images/pdfimg.png') }}" alt="">
                <div class="text-end"> No. Siri: SWO 001</div>
                <h6 class="text-center">NOTIS MEMBERHENTIKAN AKTIVITI BAHAYA BERHAMPIRAN PEPASANGAN ELEKTRIK TNB</h6>
                <div class="p-4">
                    <div class="row ">
                        <div class="col-2">Kepada</div>
                        <div class="col-4">: {{$data->company_name}}</div>
                        <div class="col-3"></div>
                        <div class="col-3">Kepada : ............</div>
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
                        <div class="col-4">: {{$data->office_phone_no}}</div>
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
                        <div class="col-2">Lokasi</div>
                        <div class="col-4">
                           {{$data->road_name}}
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
                        <div class="col-4">
                            <hr>
                        </div>
                        <div class="col-4">
                            <hr>
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
                        <div class="col-4 d-flex">No. I/C:
                            <hr>
                        </div>

                        <div class="col-4 d-flex">No. I/C:
                            <hr>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-4">Tenaga Nasional Berhad</div>
                        <div class="col-4 d-flex"> </div>

                        <div class="col-4 d-flex"> </div>
                    </div>


                    <div class="row">
                        <div class="col-4">s.k. Suruhanjaya Tenaga</div>
                        <div class="col-4 d-flex">TandaTangan:
                            <hr>
                        </div>

                        <div class="col-4 d-flex">TandaTangan:
                            <hr>
                        </div>
                    </div>
                </div>
            </section>


        </div>

    </div>
    <script>
        $(document).ready(function() {

            window.addEventListener('afterprint', function() {
                // window.close()
            })
        })

        function exportToPDF() {


            window.print()
        }
    </script>

</body>

</html>
