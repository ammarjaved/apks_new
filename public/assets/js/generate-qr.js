

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




var from_date = $('#excel_from_date').val()  ?? "" ;
var to_date = $('#excel_to_date').val() ??"";
var excel_ba = $('#excelBa').val() ??'';
var qa_status = $('#qa_status').val() ?? '';
var f_status = $('#status').val() ?? '';
var cycle = $('#cycle').val() ?? '';




var url_split = '';



var table = '';


$(function(){


    url_split = url.split('-');

    to_date= localStorage[url_split[0] + '_to']??'';
    from_date= localStorage[url_split[0] + '_from']??'';
    cycle= localStorage[url_split[0] + '_cycle']??1;

    $('#excel_from_date').val(from_date)
    $('#excel_to_date').val(to_date)
    $('#cycle').val(cycle)


 qa_status = '';


//  $('.table').on('page.dt', function() {

//     console.log(table.page.info().page);

// });

    $('#excelBa').on('change', function() {
        excel_ba = $(this).val();
        table.ajax.reload(function() {
            // table.draw('page');
        });
    })


    $('#excel_from_date').on('change', function() {
        from_date = $(this).val();
        table.ajax.reload(function() {
            // table.draw('page');
        });
    })

    $('#excel_to_date').on('change', function() {
        to_date = $(this).val();
        table.ajax.reload(function() {
            // table.draw('page');
        });
    });

    $('#status').on('change', function() {
        f_status = $(this).val();
        table.ajax.reload(function() {
            // table.draw('page');
        });
    });

    $('#qa_status').on('change', function() {
        qa_status = $(this).val();
        table.ajax.reload(function() {
            // table.draw('page');
        });
    });

    $('#cycle').on('change', function() {
        cycle = $(this).val();
        table.ajax.reload(function() {
            // table.draw('page');
        });
    });


    $('#myModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        $('#remove-foam').attr('action', `/${lang}/${url}/${id}`)
        });


          //submit foam using ajax
        //   $jq('#reject-foam').ajaxForm({
        //     success: function(rs) {
        //         $('#rejectReasonModal').modal('hide');
        //         $('#reject_remakrs').val('');
        //         table.ajax.reload(); // Reload without changing the page
        //     }
        // });






        $('#rejectReasonModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            $('#reject-foam').attr('action', `/${lang}/${url}-update-QA-Status`)
            $('#reject-id').val(id);
        });

        $('#rejectReasonModalShow').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var remarks = button.data('reject_remarks');
            $('#reject_remakrs_show').val(remarks);


        });
});



    function setMinDate(minDate,type){

        localStorage.setItem(type+'_from', minDate);
        $('#excel_to_date').attr('min',minDate);
    }

    function setMaxDate(maxDate,type){
        localStorage.setItem(type+'_to', maxDate);
        $('#excel_from_date').attr('max',maxDate);
    }


    function setCycle(maxDate,type){
        localStorage.setItem(type+'_cycle', maxDate);
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

        $('#search_wp').empty();
        $('#search_wp').append(`<option value="" hidden>Select Work Package</option>`);

    }




    function collapseFilter() {
    // $('#collapseQr').collapse('hide');
    }


    function updateQaStatus(status, id ) {

        var confirmation = confirm(`Are you sure you want to ${status}?`);

    if (confirmation) {


        $.ajax({
            url: `/${lang}/${url}-update-QA-Status?status=${status}&&id=${id}`,
            dataType: 'JSON',
            method: 'GET',
            async: false,
            success: function callback(data) {
                //  alert('Request Success');
                // if (data.status == 'Accept') {
                //     $('#status-' + id).html(`<span class="badge bg-success">Accept</span>`);

                // } else if (data.status == 'Reject') {
                //     $('#status-' + id).html(`<span class="badge bg-danger">Reject</span>`);

                // }
                location.reload();
            }
        });
    }
    }


function renderDropDownActions(data, type, full) {

    var id = full.id;
    return `<button type="button" class="btn  " data-toggle="dropdown">
        <i class="fas fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu" role="menu">
        <form action="/${lang}/${url}/${id}" method="get">

            <button type="submit" class="dropdown-item pl-3 w-100 text-left">Detail</button>
        </form>
        <form action="/${lang}/${url}/${id}/edit" method="get">

            <button type="submit" class="dropdown-item pl-3 w-100 text-left">Edit</button>
        </form>
        <button type="button" class="btn btn-primary dropdown-item" data-id="${id}" data-toggle="modal"
            data-target="#myModal">
            Remove
        </button>
    </div>`

}


function renderQaStatus(data, type, full) {

    if (full.qa_status === 'Accept' || full.qa_status === 'Reject') {
        if (full.qa_status == 'Accept') {
            return `
            <span class="badge bg-success">Accept</span>`;

        }



        return `
        <a type="button" class=" " data-reject_remarks="${full.reject_remarks}" data-toggle="modal" data-target="#rejectReasonModalShow">
            <span class="badge bg-danger">${full.reject_remarks.substring(0, 8)}...</span>
        </a>`;

    } else {
        if ( full.qa_status == 'pending') {


        return `<div class="d-flex text-center" id="status-${full.id}">
                    <a  class="btn btn-sm btn-success  " href="/${lang}/${url}-update-QA-Status?status=Accept&&id=${full.id}" onclick="return confirm('are you sure?')" >
                                Accept
                    </a>
                     /
                     <a type="button" class="btn btn-danger  btn-sm" data-id="${full.id}" data-toggle="modal"
            data-target="#rejectReasonModal">
            Reject
        </a>

                </div>`;
                }
    }
    return '';
}


function resetIndex(){
    from_date = '';
    to_date = '';

    qa_status = '' ;
    f_status = '' ;

    if (auth_ba == '') {
        excel_ba = '';
        $('#excelBa').val('');

    }
    $('#excel_from_date').val('');
    $('#excel_to_date').val('');
    $('#qa_status').val('');
    $('#status').val('');

    localStorage.removeItem(url_split[0]+"_to");
    localStorage.removeItem(url_split[0]+"_from");

    table.ajax.reload();

}
