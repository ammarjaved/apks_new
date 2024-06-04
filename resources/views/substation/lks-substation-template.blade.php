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
                <th  >SR # : </th>
                <td  >{{ $loop->index + 1 }}</td>
                <th  >Pencawang Gambar 1</th>
                <th  >Pencawang Gambar 2</th>
            </tr>
            <tr>
                <th >NAMA</th>
                <td>{{$item->name}}</td>
                <td rowspan="3" class="text-center">  
                    @if ($item->substation_image_1 != '' && file_exists(public_path($item->substation_image_1)))
                        <img src="data:image/png;base64,{{base64_encode(file_get_contents(public_path($item->substation_image_1)))}}" height="70" alt="" srcset="">
                    @endif
                </td>
                <td rowspan="3" class="text-center">
                     @if ($item->substation_image_2 != '' && file_exists(public_path($item->substation_image_2)))
                        <img src="data:image/png;base64,{{base64_encode(file_get_contents(public_path($item->substation_image_2)))}}" height="70" alt="" srcset="">
                    @endif 
                </td>
            </tr>
            <tr>
                <th>Tarikh Lawatan :</th>
                <td>{{$item->visit_date}}</td>
            </tr>
            <tr>
                <th>Bil Janggal :</th>
                <td>{{$item->total_defects}}</td>
            </tr>
        </table>

        <table class="table-bordered w-100 main-table mb-5" >
        
                <tr>
                    <th colspan="3" class="text-center" >Pintu Pagar</th>
                    <th colspan="2" class="">Compound PE</th>
                    <th colspan="4" class="text-center">Bangunan Rosak</th>
                    <th rowspan="2"> Iklan Haram / Banner</th>
                    <th rowspan="3">Pembersihan iklan Haram/Banner & Menutup Pintu Pencawang atau Pintu Pagar</th>
                </tr>
                <tr>
                    <th>kunci</th>
                    <th>Rosak</th>
                    <th>Lain</th>

                    <th>Bersemak/Rumput Panjang</th>
                    <th>Dahan Pokok</th>

                    <th>Bumbung</th> 
                    <th>Gutter</th>
                    <th>Base</th>
                    <th>Lain</th> 
                </tr>
     
                <tr>
                    <td>{{$item->unlocked}}</td>
                    <td>{{$item->demaged}}</td>
                    <td>{{$item->other_gate == 'Ya' ? $item->gate_other_value : '' }} </td>
                    <td>{{$item->grass_status=='Yes' ?'Ya' : 'Tidak'}} </td>
                    <td>{{$item->tree_branches_status =='Yes' ?'Ya' : 'Tidak'}} </td>
                    <td>{{$item->broken_roof}} </td>
                    <td>{{$item->broken_gutter}} </td>
                    <td>{{$item->broken_base}} </td>
                    <td>{{$item->building_other == 'Ya' ? $item->building_status_other_value : '' }} </td>
                    <td>{{$item->advertise_poster_status=='Yes' ?'Ya' : 'Tidak'}} </td>
                </tr>
                <tr>
                    <td colspan="3" class="p-1 text-center">{!! getImageForPdfHelper($item->image_gate)!!}</td>
                    <td class="p-1 text-center">{!! getImageForPdfHelper($item->image_grass)!!}</td>
                    <td class="p-1 text-center">{!! getImageForPdfHelper($item->image_tree_branches)!!}</td>
                    <td colspan="4" class="p-1 text-center">{!! getImageForPdfHelper($item->image_building)!!}</td>
                    <td class="p-1 text-center">{!! getImageForPdfHelper($item->image_advertisement_before_1)!!}</td>
                    <td class="d-flex">
                        {!! getImageForPdfHelper($item->images_gate_after_lock)!!}
                        {!! getImageForPdfHelper($item->image_advertisement_after_1)!!}
                    </td>
                </tr>
        
        </table> 
        <hr class="w-100 my-4" >
    @endforeach
    
    <!-- Your Blade template content goes here -->
</body>
</html>
