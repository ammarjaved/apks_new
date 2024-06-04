
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
    ['B4', 'PUTRAJAYA & CYBERJAYA', 2.92875032271019, 101.675338316575],
    ['B4', 'SEPANG', 2.734218580014375, 101.69394518452967],
    ['B4', 'PUCHONG', 2.971632230751114, 101.62918173453126]
];


var from_date = $('#excel_from_date').val();
var to_date = $('#excel_to_date').val();
var excel_ba = $('#excelBa').val();

var table = '';
var patroling = '';

var patrol = [];
var from_date = $('#excel_from_date').val();
var to_date = $('#excel_to_date').val();
var excel_ba = $('#search_ba').val();

$(function(){


    $('#search_ba').on('change', function() {
        excel_ba = $(this).val();
    
        table.ajax.reload(function() {
            table.draw('page');
        });
    })
    
    
    $('#excel_from_date').on('change', function() {
        from_date = $(this).val();
        table.ajax.reload(function() {
            table.draw('page');
        });
        filterByPatrollingDate(this)
    })
    
    $('#excel_to_date').on('change', function() {
        to_date = $(this).val();
        table.ajax.reload(function() {
            table.draw('page');
        });
        filterByPatrollingDate(this)
    });
    });
    
    
    
    function setMinDate(minDate){
        $('#excel_to_date').attr('min',minDate);
    }

    function setMaxDate(maxDate){
        $('#excel_from_date').attr('max',maxDate);
    }




    function getBa (selectedValue) {

        const areaSelect = $('#excelBa');
        // Clear previous options
        areaSelect.empty();
        areaSelect.append(`<option value="" hidden>Select ba</option>`)

        b10ptions.forEach((data) => {
            if (selectedValue == data[0]) {
                areaSelect.append(`<option value="${data[1]}">${data[1]}</option>`);
            }
        });
 

    }




    


    function updateQaStatus(status, id ) {

        $.ajax({
            url: `/${lang}/${url}-update-QA-Status?status=${status}&&id=${id}`,
            dataType: 'JSON',
            method: 'GET',
            async: false,
            success: function callback(data) {
                 alert('Request Success');
                if (data.status == 'Accept') {
                    $('#status-' + id).html(`<span class="badge bg-success">Accept</span>`);

                } else if (data.status == 'Reject') {
                    $('#status-' + id).html(`<span class="badge bg-danger">Reject</span>`);

                }
            }
        });
    }


function renderDropDownActions(data, type, full) {
    var id = full.id;
    return `<button type="button" class="btn  " data-toggle="dropdown">
        <i class="fa fa-eye" aria-hidden="true"></i>
</button>
<div class="dropdown-menu" role="menu">


        <button type="button" onclick="getGeoJson(${full.id})" class="dropdown-item pl-3 w-100 text-left">Full Path</button>


        <button type="button" class="btn btn-primary dropdown-item" onclick="showPoint(${full.start_x} , ${full.start_y})"  >
        Starting Point
    </button>
        <button type="button" onclick="showPoint(${full.end_x} , ${full.end_y})" class="dropdown-item pl-3 w-100 text-left">End Point</button>


</div>


`;


}


function renderQaStatus(data, type, full) {
    
    if (full.qa_status === 'Accept' || full.qa_status === 'Reject') {
        if (full.qa_status == 'Accept') {
            return `<span class="badge bg-success">Accept</span>`;

        }
        return `<span class="badge bg-danger">Reject</span>`;

    } else {

        return `<div class="d-flex text-center" id="status-${full.id}"> 
                    <a type="button" class="btn btn-sm btn-success  " onclick="updateQaStatus('Accept',` + full.id + `)">
                                Accept
                    </a>
                     / 
                    <a type="button" class="btn btn-sm btn-danger " onclick="updateQaStatus('Reject',` + full.id + `)">
                                Reject
                    </a>
                </div>`;
    }
}

function getGeoJson(param) {
$.ajax({
    url: `/${lang}/get-patrolling-json/` + param,
    dataType: 'JSON',
    //data: data,
    method: 'GET',
    async: false,
    success: function callback(data) {
        var data1 = JSON.parse(data[0].geojson)


        if (patrol) {
            for (let i = 0; i < patrol.length; i++) {
                if (patrol[i] != '') {
                    map.removeLayer(patrol[i])
                }
            }
        }
        for (var i = 0; i < data1.features.length; i++) {
            var geom = L.GeoJSON.coordsToLatLngs(data1.features[i].geometry.coordinates);
            var line = L.polyline(geom);
            if (i == data1.features.length - 1) {
                map.fitBounds(line.getBounds());
            }

            patrol[i] = L.geoJSON(data1.features[i].geometry);
            map.addLayer(patrol[i])
        }

    }
})
}

var marker = [];
var layer_index = 0;

function showPoint(param_x, param_y) {

marker[layer_index] = new L.Marker([param_y, param_x]);
map.addLayer(marker[layer_index]);
layer_index++;
map.flyTo([parseFloat(param_y), parseFloat(param_x)], 18, {
    duration: 1.5, // Animation duration in seconds
    easeLinearity: 0.25,
});

}

function removePoint() {
for (let i = 0; i < layer_index; i++) {
    if (marker[i] != '') {
        map.removeLayer(marker[i])
    }
}
}

function removeLines() {
for (let i = 0; i < patrol.length; i++) {
    if (patrol[i] != '') {
        map.removeLayer(patrol[i])
    }
}
}

function callPatrlloingLayer(param) {
var userBa = '';
for (const data of b1Options) {
    if (data[1] == param) {
        userBa = data;
        break;
    }
}
zoom = 11;
excel_ba = param;

table.ajax.reload(function() {
    table.draw('page');
});
addRemoveBundary(userBa[1], userBa[2], userBa[3])
}


function resetPatrlloingMapFilters() {

from_date = '';
to_date = '';
excel_ba = '';
$('#excel_from_date , #excel_to_date ').val('')

if (ba == '') {
    zoom= 8;
    addRemoveBundary('', 2.75101756479656, 101.304931640625)
    $('#search_ba').empty().append(`<option value="" hidden>Select ba</option>`);
} else {
    callPatrlloingLayer(ba);
}

table.ajax.reload(function() {
    table.draw('page');
});

}

function filterByPatrollingDate(param) {
var inBa = $('#search_ba').val()
if (param.id == 'excel_from_date') {
    from_date = param.value;
} else if (param.id == 'excel_to_date') {
    to_date = param.value;
}
callPatrlloingLayer(inBa)

}
