<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Title</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-geoserver-request"></script>
    <style>
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <h4 class="text-center">{{$ba}} PATROLLING LKS  ( {{ $from_date }} - {{ $to_date }} )</h4>
        @foreach ($data as $datas)



        <div class="container py-4">

            <table>
                <tr>
                    <th>READING START</th>
                    <th class="pl-5">READING END</th>
                </tr>
                <tr>
                    <td>
                        @if ($datas->image_reading_start != '')
                          <img src="{{ config('globals.APP_IMAGES_URL').$datas->image_reading_start }}" alt="" height="300"  class="adjust-height ml-5  " >
                        @else
                          <strong>{{ __('messages.no_image_found') }}</strong>
                        @endif
                    </td>
                    <td class="pl-5">
                        @if ($datas->image_reading_end != '')
                          <img src="{{ config('globals.APP_IMAGES_URL').$datas->image_reading_end }}" alt="" height="300"   class="adjust-height ml-5  ">
                        @else
                          <strong>{{ __('messages.no_image_found') }}</strong>
                        @endif
                    </td>
                </tr>
            </table>

            <table class="table table-bordered">
                <thead>
                    <th>ID</th>
                    <th>WP NAME</th>
                    <th>VISIT DATE</th>
                    <th>TIME</th>
                    <th>READING START</th>
                    <th>READING END</th>
                    <th>CYCLE</th>
                    {{-- <th>READING START</th>
                    <th>READING END</th> --}}
                </thead>
                <tbody>
                    <tr>
                        <td>{{$datas->id}}</td>
                        <td>{{$datas->wp_name}}</td>
                        <td>{{ date('Y-m-d', strtotime($datas->vist_date)) }} </td>
                        <td>{{ date('H:i:s', strtotime($datas->time)) }}  </td>
                        <td>{{$datas->reading_start}} </td>
                        <td>{{$datas->reading_end}} </td>
                        <td>{{$datas->cycle}} </td>
                    </tr>
                </tbody>
            </table>
            <div class="container px-5 ms-auto">
                <div id="map-{{$datas->id}}" class="map" style="height: 400px; width: 800px; "></div>
            </div>

            <script>
                var id = {{$datas->id}};
                var x = '{{$datas->firstPatrollingLines}}' != '' ? '{{$datas->firstPatrollingLines->x}}' : '3.016603';
                var y = '{{$datas->firstPatrollingLines}}' != '' ? '{{$datas->firstPatrollingLines->y}}' : '101.858382';

                var map = L.map("map-{{$datas->id}}").setView([y, x], 14);
                document.getElementById("map-{{$datas->id}}").style.cursor = "pointer";

                // Add OpenStreetMap as a base layer
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);

                // Add GeoServer WMS layer
                var geoServerLayer = L.tileLayer.wms('http://121.121.232.54:7090/geoserver/cite/wms', {
                    layers: 'cite:patroling_lines',
                    format: 'image/png',
                    cql_filter: "patroling_id =" + id,
                    transparent: true
                }).addTo(map);


            </script>
        </div>
        <div class="page-break"></div>
    @endforeach

</body>
</html>
