<script type="text/javascript">
    var baseLayers
    var identifyme = '';
    var boundary3 = '';
    var marker = '';
    var boundary2 = '';
    map = L.map('map').setView([3.016603, 101.858382], 5);



    var st1 = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map); // satlite map

    var street = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'); // street map

    // ADD MAPS
    baseLayers = {
        "Satellite": st1,
        "Street": street
    };


    boundary3 = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
        layers: 'cite:aero_apks',
        format: 'image/png',
        maxZoom: 21,
        transparent: true
    }, {
        buffer: 10
    })



    // ADD LAYERS GROUPED OVER LAYS
    groupedOverlays = {
        "POI": {
            'BA': boundary3,
        }
    };

    var layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
        collapsed: true,
        position: 'topright'
        // groupCheckboxes: true
    }).addTo(map);



    // add boundary layer on page load
    map.addLayer(boundary3)
    map.setView([2.59340882301331, 101.07054901123], 8);


    // change layer and view when ba change
    function addRemoveBundary(param, paramY, paramX) {

        map.removeLayer(boundary3) // Remove on page load boundary

        if (boundary2 !== '') { // boundary if eesixts then first reomve from map
            map.removeLayer(boundary2)
        }

        boundary2 = L.tileLayer.wms("http://121.121.232.54:7090/geoserver/cite/wms", {
            layers: 'cite:ba',
            format: 'image/png',
            cql_filter: "station='" + param + "'", // add ba name for filter boundary
            maxZoom: 21,
            transparent: true
        }, {
            buffer: 10
        })

        map.addLayer(boundary2) // add filtered boundary
        boundary2.bringToFront()

        map.setView([parseFloat(paramY), parseFloat(paramX)], 10); // set view






    }

    // on click map add marker and bind popup
    function onMapClick(e) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker(e.latlng);
        map.addLayer(marker);
        marker.bindPopup("<b>You clicked the map at " + e.latlng.toString()).openPopup();

        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        $('#lat').val(lat.toFixed(2));
        $('#log').val(lng.toFixed(2));
        var coordinate = $('#coordinate')

        if (coordinate.length > 0) {
            coordinate.val(`${lat.toFixed(2)} , ${lng.toFixed(2)}`)
            return;
        }

        $.ajax({
            url: '/{{app()->getLocale()}}/get-road-name/' + parseFloat(lat) + '/' + parseFloat(lng),
            dataType: 'JSON',
            //data: data,
            method: 'GET',
            async: false,
            success: function callback(data) {
                console.log(data);
                if (data.Success) {


                    if (data[0].road_name == null) {
                        $('#road_name_check').html("Road name is missing Please enter road name")
                    } else {
                        $('#road_name_check').html("")
                    }
                    $('#road_name').val(data[0].road_name)
                } else {
                    $('#road_name_check').html("No Road Found Please select again")
                }
            }
        })
    }

    map.on('click', onMapClick);
</script>


<script>
    // ba their names and their points
    const b10ptions = [
        ['W1', 'KUALA LUMPUR PUSAT', 3.14925905877391, 101.754098819705],
        ['B1', 'PETALING JAYA', 3.1128074178475, 101.605270457169],
        ['B1', 'RAWANG', 3.47839445121726, 101.622905486475],
        ['B1', 'KUALA SELANGOR', 3.40703209426401, 101.317426926947],
        ['B2', 'KLANG', 3.08428642705789, 101.436185279023],
        ['B2', 'PELABUHAN KLANG', 2.98188527916042, 101.324234779569],
        ['B4', 'CHERAS', 3.14197346621987, 101.849883983416],
        ['B4', 'BANTING', 2.82111390453244, 101.505890775541],
        ['B4', 'BANGI', 2.965810949933260, 101.81881303103104],
        ['B4', 'PUTRAJAYA_CYBERJAYA', 2.92875032271019, 101.675338316575],
        ['B4', 'SEPANG', 2.734218580014375, 101.69394518452967],
        ['B4', 'PUCHONG', 2.971632230751114, 101.62918173453126]
    ];

    $(document).ready(function() {


                $("#myForm").validate();

                $('#search_zone').on('change', function() {
                        const selectedValue = this.value;
                        const areaSelect = $('#ba_s');

                        // Clear previous options
                        areaSelect.empty();
                        areaSelect.append(`<option value="" hidden>Select ba</option>`)

                        b10ptions.forEach((data) => {
                            if (selectedValue == data[0]) {
                                areaSelect.append(`<option value="${data}">${data[1]}</option>`);
                            }
                        });

                        $('#search_wp').empty();
                        $('#search_wp').append(`<option value="" hidden>Select Work Package</option>`);

                    })
                });





            function getWp(param) {
                var splitVal = param.value.split(',');
                addRemoveBundary(splitVal[1], splitVal[2], splitVal[3])

                $('#ba').val(splitVal[1])


            }

            function submitFoam() {
                if ($('#lat').val() == '' || $('#log').val() == '') {
                    $('.map-error').html('Please select location')
                    return false;
                } else {
                    $('.map-error').html(' ')
                }
            }
</script>
