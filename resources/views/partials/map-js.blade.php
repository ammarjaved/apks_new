<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
<link rel="stylesheet" href="{{ URL::asset('map/draw/leaflet.draw.css') }}" />

<script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>

<script src="{{ URL::asset('map/draw/leaflet.draw-custom.js') }}"></script>

<script src="{{ URL::asset('map/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.js') }}"></script>

<script src="{{ URL::asset('assets/js/geoserver-layers.js') }}"></script>




<script type="text/javascript">
    var baseLayers = '';
    var identifyme = '';
    var sel_lyr = '';
    var layerControl = '';
    var boundary = '';
    var zoom = 8;
    var from_date = ''
    var to_date = ''




/// layers

var substation = '';
var substation_with_defects = '';
var substation_without_defects ='';
var sub_pending = '';
var sub_reject = '';

var fp_unsurveyed = '';
var fp_without_defects ='';
var fp_with_defects ='';
var fp_reject ='';
var fp_pending = '';
var feeder_pillar = '';

var ts_without_defects = '';
var ts_unsurveyed ='';
var ts_with_defects ='';
var ts_pending ='';
var ts_reject = '';

var cb_without_defects = '';
var cb_unsurveyed ='';
var cb_with_defects ='';
var cb_pending = '';
var cb_reject = '';

var lb_without_defects = '';
var lb_unsurveyed ='';
var lb_with_defects ='';
var lb_pending = '';
var lb_reject = '';

var savt_without_defects = '';
var savt_unsurveyed ='';
var savt_with_defects ='';
var savt_pending = '';
var savt_reject = '';

var road = '';
var unservey = '';
var pano_layer = '';

var work_package = '';
var kiv = '';


var substation = '';
// var substation_with_defects = '';
var substation_without_defects ='';
var sub_pending = '';
var sub_reject = '';





var g5_x_5_grid = '';







    var popup = L.popup();


    map = L.map('map').setView([2.75101756479656, 101.304931640625], 8);

    var st1 = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);
    var street = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

    // ADD MAPS
    baseLayers = {
        "Satellite": st1,
        "Street": street
    };










    function selectLayer(param) {
        if (param == 'pano') {
            addpanolayer();
        } else {

            if (param == 'sub_reject') {
                sel_lyr = sub_reject;

            }
            else if (param == 'substation_without_defects') {
                sel_lyr = substation_without_defects;

            }

            else if (param == 'sub_pending') {
                sel_lyr = sub_pending;

            }
            else if (param == 'substation_without_defects') {
                sel_lyr = substation_without_defects;

            }
            else if (param == 'sub_kiv') {
                sel_lyr = kiv;

            }
            else if (param == 'substation_with_defects') {
                sel_lyr = substation_with_defects;

            }
            else if (param == 'fp_unsurveyed') {
                sel_lyr = fp_unsurveyed;

            }
            else if (param == 'fp_without_defects') {
                sel_lyr = fp_without_defects;

            }

            else if (param == 'fp_with_defects') {
                sel_lyr = fp_with_defects;

            }

            else if (param == 'fp_pending') {
                sel_lyr = fp_pending;

            }
            else if (param == 'fp_reject') {
                sel_lyr = fp_reject;

            }

            else if (param == 'main_substation') {
                sel_lyr = substation;

            } else if (param == 'tbl_savr') {
                sel_lyr = tbl_savr;

            }  else if (param == 'cb_unsurveyed') {
                sel_lyr = cb_unsurveyed;

            }
            else if (param == 'cb_with_defects') {
                sel_lyr = cb_with_defects;

            }
            else if (param == 'cb_without_defects') {
                sel_lyr = cb_without_defects;

            }

            else if (param == 'cb_pending') {
                sel_lyr = cb_pending;

            }
            else if (param == 'cb_reject') {
                sel_lyr = cb_reject;

            }


            else if (param == 'ts_unsurveyed') {
                sel_lyr = ts_unsurveyed;

            }
            else if (param == 'ts_with_defects') {
                sel_lyr = ts_with_defects;

            }
            else if (param == 'ts_without_defects') {
                sel_lyr = ts_without_defects;

            }

            else if (param == 'lb_unsurveyed') {
                sel_lyr = lb_unsurveyed;

            }
            else if (param == 'lb_with_defects') {
                sel_lyr = lb_with_defects;

            }
            else if (param == 'lb_without_defects') {
                sel_lyr = lb_without_defects;

            }
            else if (param == 'lb_pending') {
                sel_lyr = lb_pending;

            }
            else if (param == 'lb_reject') {
                sel_lyr = lb_reject;

            }


            else if (param == 'savt_unsurveyed') {
                sel_lyr = savt_unsurveyed;

            }
            else if (param == 'savt_with_defects') {
                sel_lyr = savt_with_defects;

            }
            else if (param == 'savt_without_defects') {
                sel_lyr = savt_without_defects;

            }
            else if (param == 'savt_pending') {
                sel_lyr = savt_pending;

            }
            else if (param == 'savt_reject') {
                sel_lyr = savt_reject;

            }
            else if (param == 'road') {
                sel_lyr = road;

            }else if(param == 'unsurveyed'){
                sel_lyr =  unservey;
            }
            else if(param == 'ts_pending'){
                sel_lyr =  ts_pending;
            }
            else if(param == 'ts_reject'){
                sel_lyr =  ts_reject;
            }
            callSelfLayer(param);
        }
    }




    function callSelfLayer(param) {
        // console.log("asdasdasdasdas");
        // console.log(sel_lyr);
        map.off('click');
        map.on('click', function(e) {
            popup
        .setLatLng(e.latlng)
        .setContent("You clicked the map at " + e.latlng.toString())
        .openOn(map);
            var url = getFeatureInfoUrl(
                map,
                sel_lyr,
                e.latlng, {
                    'info_format': 'application/json',
                    'propertyName': 'NAME,AREA_CODE,DESCRIPTIO'
                }
            );
            var secondUrl = encodeURIComponent(url)
            $.ajax({
                url: '/{{ app()->getLocale() }}/proxy/' + encodeURIComponent(secondUrl),
                dataType: 'JSON',
                //data: data,
                method: 'GET',
                async: false,
                success: function callback(data1) {


                    data = JSON.parse(data1)
                    console.log(data.features[0].properties.id);
                    // console.log(data.features[0].id);
                    if (data.features.length != 0) {
                        if (param == 'substation') {
                            substationModal(data.features[0].properties, data.features[0].properties.id);

                        }else if(param == 'road') {
                            roadModal(data.features[0].properties, data.features[0].properties.id)

                        }else {
                            showModalData(data.features[0].properties, data.features[0].properties.id);
                        }
                    }

                }
            });




        });
    }

    function substationModal(data, id) {
        var str = '';
        var idSp = id.split('.');

        $('#exampleModalLabel').html("Substation Info")
        str = ` <tr><th>Zone</th><td>${data.zone}</td> </tr>
        <tr><th>Ba</th><td>${data.ba}</td> </tr>
        <tr><th>Type</th><td>${data.type}</td> </tr>
        <tr><th>Voltage</th><td>${data.voltage}</td> </tr>
        <tr><th>Coordinate</th><td>${data.coordinate}</td> </tr>
        <tr><th>Created At</th><td>${data.created_at}</td> </tr>
        <tr><th>Detail</th><td class="text-center">    <a href="/{{ app()->getLocale() }}/substation/${idSp[1]}" target="_blank" class="btn btn-sm btn-secondary">Detail</a>
            </td> </tr>
        `

        $("#my_data").html(str);
        $('#myModal').modal('show');
        // console.log(data);
    }

    function getFeatureInfoUrl(map, layer, latlng, params) {

        var point = map.latLngToContainerPoint(latlng, map.getZoom()),
            size = map.getSize(),

            params = {
                request: 'GetFeatureInfo',
                service: 'WMS',
                srs: 'EPSG:4326',
                styles: layer.wmsParams.styles,
                transparent: layer.wmsParams.transparent,
                version: layer._wmsVersion,
                format: layer.wmsParams.format,
                bbox: map.getBounds().toBBoxString(),
                height: size.y,
                width: size.x,
                layers: layer.wmsParams.layers,
                query_layers: layer.wmsParams.layers,
                info_format: 'application/json'
            };

        params[params.version === '1.3.0' ? 'i' : 'x'] = parseInt(point.x);
        params[params.version === '1.3.0' ? 'j' : 'y'] = parseInt(point.y);

        // return this._url + L.Util.getParamString(params, this._url, true);

        var url = layer._url + L.Util.getParamString(params, layer._url, true);
        if (typeof layer.wmsParams.proxy !== "undefined") {


            // check if proxyParamName is defined (instead, use default value)
            if (typeof layer.wmsParams.proxyParamName !== "undefined")
                layer.wmsParams.proxyParamName = 'url';

            // build proxy (es: "proxy.php?url=" )
            _proxy = layer.wmsParams.proxy + '?' + layer.wmsParams.proxyParamName + '=';

            url = _proxy + encodeURIComponent(url);

        }

        return url.toString();

    }



    function addpanolayer() {


        map.off('click');

        map.on('click', function(e) {
            //map.off('click');
            $("#wg").html('');
            $('.windowTitle').html('Pano Images')
            // Build the URL for a GetFeatureInfo
            var url = getFeatureInfoUrl(
                map,
                pano_layer,
                e.latlng, {
                    'info_format': 'application/json',
                    'propertyName': 'NAME,AREA_CODE,DESCRIPTIO'
                }
            );

            var secondUrl = encodeURIComponent(url)

            $.ajax({
                    url: '/{{ app()->getLocale() }}/proxy/' + encodeURIComponent(secondUrl),
                    dataType: 'json',
                    method: 'GET',
                })
                .done(function(data) {
                    var deco = JSON.parse(data)
                    console.log(deco.features[0]);
                    if (deco && deco.features && deco.features.length !== undefined) {
                        // Create the panorama viewer

                        var str = '<div id="window1" class="window">' +
                            '<div class="green">' +
                            '<p class="windowTitle">Pano Images</p>' +
                            '</div>' +
                            '<div class="mainWindow">' +

                            '<div id="panorama" width="400px" height="480px"></div>' +
                            '<div class="row"><button style="margin-left: 30%;" onclick=preNext("pre") class="btn btn-success">Previous</button><button  onclick=preNext("next")  style="float: right;margin-right: 35%;" class="btn btn-success">Next</button></div>'

                        '</div>' +
                        '</div>'

                        $("#wg").html(str);

                        createWindow(1);
                        selectedId = deco.features[0].id.split('.')[1];
                        var windowPosition = map.latLngToContainerPoint(e.latlng);
        // $('#window1').css({
        //     'position': 'absolute',
        //     'left': windowPosition.x -50 + 'px',
        //     'top': windowPosition.y-50 + 'px'
        // });

                        pannellum.viewer('panorama', {
                            "type": "equirectangular",
                            "panorama": deco.features[0].properties.photo,
                            "compass": true,
                            "autoLoad": true
                        });

                        if (identifyme !== '') {
                            map.removeLayer(identifyme);
                        }

                        identifyme = L.geoJSON(deco.features[0].geometry).addTo(map);
                        $('.windowTitle').html(`Pano Images ( LAT : ${(e.latlng.lat).toFixed(2)} , LNG : ${(e.latlng.lng).toFixed(2)} )`)
                    } else {
                        console.log(
                            'Data or data.features is undefined or does not have a valid length property.'
                        );
                    }
                })
                .fail(function(error) {
                    console.log('Error: ', error);
                });


        });
    }

    var selectedId = '';

    function preNext(status) {
        $("#wg").html('');
        $.ajax({
            url: '/{{ app()->getLocale() }}/preNext/' + selectedId + '/' + status,
            dataType: 'JSON',
            //data: data,
            method: 'GET',
            async: false,
            success: function callback(data) {

                //  alert(data
                var str = '<div id="window1" class="window">' +
                    '<div class="green">' +
                    '<p class="windowTitle">Pano Images</p>' +
                    '</div>' +
                    '<div class="mainWindow">' +
                    // '<canvas id="canvas" width="400" height="480">' +
                    // '</canvas>' +
                    '<div id="panorama" width="400px" height="480px"></div>' +
                    '<div class="row"><button style="margin-left: 30%;" onclick=preNext("pre") class="btn btn-success">Previous</button><button  onclick=preNext("next")  style="float: right;margin-right: 35%;" class="btn btn-success">Next</button></div>'
                '</div>' +
                '</div>'

                $("#wg").html(str);

                createWindow(1);
                console.log(data)
                selectedId = data[0].gid
                pannellum.viewer('panorama', {
                    "type": "equirectangular",
                    "panorama": data[0].photo,
                    "compass": true,
                    "autoLoad": true
                });

                if (identifyme != '') {
                    map.removeLayer(identifyme)
                }
                identifyme = L.geoJSON(JSON.parse(data[0].geom)).addTo(map);


            }
        });

    }
</script>



<script>
    const b1Options = [
        ['W1', 'KUALA LUMPUR PUSAT', 3.14925905877391, 101.754098819705],
        ['B1', 'PETALING JAYA', 3.1128074178475, 101.605270457169],
        ['B1', 'RAWANG', 3.47839445121726, 101.622905486475],
        ['B1', 'KUALA SELANGOR', 3.40703209426401, 101.317426926947],
        ['B2', 'KLANG', 3.08428642705789, 101.436185279023],
        ['B2', 'PELABUHAN KLANG', 2.98188527916042, 101.324234779569],
        ['B4', 'CHERAS', 3.14197346621987, 101.849883983416],
        ['B4', 'BANTING', 2.82111390453244, 101.505890775541],
        ['B4', 'BANGI', 2.965810949933260, 101.81881303103104],
        ['B4', 'PUTRAJAYA & CYBERJAYA', 2.92875032271019, 101.675338316575],
        ['B4', 'SEPANG', 2.734218580014375, 101.69394518452967],
        ['B4', 'PUCHONG', 2.971632230751114, 101.62918173453126]

    ];

  var xyObj={
    'KUALA LUMPUR PUSAT':'3.14925905877391, 101.754098819705',
    'PETALING JAYA':'3.1128074178475, 101.605270457169',
    'RAWANG':'3.47839445121726, 101.622905486475',
    'KUALA SELANGOR': '3.40703209426401, 101.317426926947',
    'KLANG': '3.08428642705789, 101.436185279023',
    'PELABUHAN KLANG': '2.98188527916042, 101.324234779569',
    'CHERAS': '3.14197346621987, 101.849883983416',
    'BANTING': '2.82111390453244, 101.505890775541',
    'BANGI':'2.965810949933260, 101.81881303103104',
    'PUTRAJAYA & CYBERJAYA': '2.92875032271019, 101.675338316575',
    'SEPANG': '2.734218580014375, 101.69394518452967',
    'PUCHONG':'2.971632230751114, 101.62918173453126'
  }

    var ba = "{{ Auth::user()->ba }}";
    if(ba==='PUTRAJAYA &amp; CYBERJAYA'){
        ba='PUTRAJAYA & CYBERJAYA';
    }
    var f_ba = '';
    var cycle = $('#cycle').val()


    // on page load
    $(document).ready(function() {

        // check ba is empty or not
        if (ba == '') {
            addRemoveBundary('', 2.75101756479656, 101.304931640625)
        } else {
            callLayers(ba);
        }

        $('body').addClass('sidebar-collapse');

    });


    // if ba is not empty
    function callLayers(param) {

            var userBa = '';
        for (const data of b1Options) {
            if (data[1] == param) {
                userBa = data;
                break;
            }

        }
        if (userBa == '') {
            addRemoveBundary('', 2.75101756479656, 101.304931640625)
        }else{
        zoom = 11;
        addRemoveBundary(userBa[1], userBa[2], userBa[3])
        }

    }


    function onChangeZone(param) {
        const areaSelect = $('#search_ba');

        // Clear previous options
        areaSelect.empty();
        areaSelect.append(`<option value="" hidden>Select ba</option>`)


        b1Options.map((data) => {
            if (data[0] == param) {
                areaSelect.append(`<option value="${data[1]}">${data[1]}</option>`)
            }
        });

    }


    function filterByDate(param) {
        var inBa = $('#search_ba').val() ??'';
        if (param.id == 'from_date') {
            from_date = param.value;
        } else if (param.id == 'to_date') {
            to_date = param.value;
        }
        callLayers(inBa)

    }

    function setCycle(param){
        var inBa = $('#search_ba').val() ??'';
        cycle = param
        callLayers(inBa)
    }

    function  resetMapFilters() {

            from_date = '';
            to_date = '';
            $('#from_date , #to_date , .tt-input').val('')

            if (ba == '') {
                zoom = 8
                addRemoveBundary('', 2.75101756479656, 101.304931640625)
                $('#search_ba').val('');
            } else {
                callLayers(ba);
            }
            if (marker != '') {
            map.removeLayer(marker)
        }
    }
</script>
