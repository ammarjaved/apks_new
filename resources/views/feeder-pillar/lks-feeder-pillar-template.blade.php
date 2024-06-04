<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PDF Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Include any CSS styles or Bootstrap styles here -->
    <style>
       .main-table th ,.main-table td {border: 1px solid black !important}
       .main-table th{background-color: gray !important ; color: black}
       .main-table th{padding: 5px !important}
       
    </style>
</head>
<body>
    <h1>{{$ba}} ( {{ $visit_date }} )</h1>

    <table class="table-bordered">
        <th>Jumlah Rekod</th>
        <td  >{{sizeof($datas)}}</td>
    </table>

    @foreach ($datas as $item)
        
        <table class="my-4">
            <tr>
                <th>SR # : </th>
                <td>{{ $loop->index + 1 }}</td>
                <th class="px-3">Feeder Pillar Gambar 1</th>
                <th class="px-3" >Feeder Pillar Gambar 2</th>
                <th class="px-3">FP Plate</th>
            </tr>
            <tr>
                <th>ID : </th>
                <td>FP-{{$item->id}}</td>
            </tr>
            <tr>
                <th >NAMA</th>
                <td>{{$item->name}}</td>
                <td rowspan="4" class="text-center">  
                    @if ($item->feeder_pillar_image_1 != '' && file_exists(public_path($item->feeder_pillar_image_1)))
                        <img src="data:image/png;base64,{{base64_encode(file_get_contents(public_path($item->feeder_pillar_image_1)))}}" height="70" alt="" srcset="">
                    @endif
                </td>
                <td rowspan="4" class="text-center">
                     @if ($item->feeder_pillar_image_2 != '' && file_exists(public_path($item->feeder_pillar_image_2)))
                        <img src="data:image/png;base64,{{base64_encode(file_get_contents(public_path($item->feeder_pillar_image_2)))}}" height="70" alt="" srcset="">
                    @endif 
                </td>
                <td rowspan="4" class="text-center">
                    @if ($item->image_name_plate != '' && file_exists(public_path($item->image_name_plate)))
                        <img src="data:image/png;base64,{{base64_encode(file_get_contents(public_path($item->image_name_plate)))}}" height="70" alt="" srcset="">
                    @endif 
                </td>
            </tr>
            <tr>
                <th>Tarikh Lawatan :</th>
                <td>{{$item->visit_date}}</td>
            </tr>
            <tr>
                <th>Saiz :</th>
                <td>{{$item->size}}</td>
            </tr>
            <tr>
                <th>Koordinat</th>
                <td>{{$item->coordinate}}</td>
            </tr>
            <tr>
                <th>Bil Janggal :</th>
                <td>{{$item->total_defects}}</td>
            </tr>
        </table>

        <table class="table-bordered w-100 main-table mb-5" >
            {{-- <thead> --}}
                <tr>
                    <th colspan="3" class="text-center" >Pintu Pagar</th>
                    <th colspan="5" class="text-center">STATUS LAIN</th> 
                    <th rowspan="2"> Iklan Haram / Banner</th>
                    <th rowspan="3">Pembersihan iklan Haram/Banner & <br> Menutup Pintu Pencawang atau Pintu Pagar</th>
                </tr>
                <tr>
                    <th>Kunci</th>
                    <th>Rosak</th>
                    <th>Lain</th>
                    <th>Vandalism</th>
                    <th>Condong</th>
                    <th>Karat</th> 
                    <th>FP Gaurd</th>
                    <th>Cat Pudar</th>
                </tr>
            {{-- </thead> --}}
                <tr>
                    <td>{{$item->unlocked}}</td>
                    <td>{{$item->demaged}}</td>
                    <td>{{$item->other_gate == 'Ya' ?$item->gate_other_value : '' }} </td>
                    <td>{{$item->vandalism_status=='Yes' ?'Ya' : 'Tidak'}} </td>
                    <td>{{$item->leaning_staus=='Yes' ?'Ya' : 'Tidak'}} </td>
                    <td>{{$item->rust_status=='Yes' ?'Ya' : 'Tidak'}} </td>
                    <td>{{$item->guard_status=='Yes' ?'Ya' : 'Tidak'}} </td>
                    <td>{{$item->paint_status}} </td>
                    <td>{{$item->advertise_poster_status=='Yes' ?'Ya' : 'Tidak'}}</td>
                </tr>
                <tr>
                    <td colspan="3" class="p-1 text-center">{!! getImageForPdfHelper($item->image_gate)!!}</td>
                    <td class="p-1 text-center">{!! getImageForPdfHelper($item->image_vandalism)!!}</td>
                    <td class="p-1 text-center">{!! getImageForPdfHelper($item->image_leaning)!!}</td>
                    <td   class="p-1 text-center">{!! getImageForPdfHelper($item->image_rust)!!}</td>
                    <td class="p-1 text-center"></td>
                    <td>{!! getImageForPdfHelper($item->images_advertise_poster)!!}</td>
                    <td></td>
                    <td class="d-flex">{!! getImageForPdfHelper($item->image_advertisement_after_1)!!}</td>
                </tr>
        </table> 
        <hr class="w-100 my-4" >
    @endforeach
    
    <!-- Your Blade template content goes here -->
</body>
</html>
